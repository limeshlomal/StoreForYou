<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoicePayment;

new
#[Layout('components.layouts.pos')]
class extends Component {

    /* =======================
     |  STATE
     =======================*/
    public $categories;
    public $search = '';
    public $barcode = '';
    public $cart = [];

    public $subtotal = 0;
    public $discount = 0;
    public $total = 0;
    public $paymentAmount = 0;
    public $change = 0;

    public $paymentMethod = 'Cash';

    public $invoiceNumber;

    // Stock confirmation
    public $showStockConfirmation = false;
    public $pendingProductId = null;

    // Invoice modal
    public $showInvoiceModal = false;
    public $lastInvoice = null;

    /* =======================
     |  MOUNT
     =======================*/
    public function mount()
    {
        $this->categories = Category::all();
        $this->invoiceNumber = 'TMP-' . now()->format('His');
    }

    /* =======================
     |  COMPUTED
     =======================*/
    #[Computed]
    public function products()
    {
        return Product::query()
            ->withSum('inventories', 'quantity')
            ->when($this->search, function ($q) {
                $term = '%' . strtolower($this->search) . '%';
                $q->whereRaw('LOWER(name) LIKE ?', [$term])
                  ->orWhere('barcode', 'like', "%{$this->search}%");
            })
            ->limit(60)
            ->get();
    }

    /* =======================
     |  BARCODE
     =======================*/
    public function updatedBarcode()
    {
        $this->scanBarcode(false);
    }

    public function scanBarcode($notify = true)
    {
        if (!$this->barcode) return;

        $product = Product::where('barcode', $this->barcode)->first();

        if ($product) {
            $this->addToCart($product->id);
            $this->barcode = '';
        } elseif ($notify) {
            $this->dispatch('notify', 'Product not found');
        }
    }

    /* =======================
     |  CART
     =======================*/
    public function addToCart($productId, $confirmed = false)
    {
        $product = Product::withSum('inventories', 'quantity')->find($productId);
        if (!$product) return;

        $existingKey = null;
        $currentQty = 0;

        foreach ($this->cart as $k => $item) {
            if ($item['id'] === $productId) {
                $existingKey = $k;
                $currentQty = $item['quantity'];
                break;
            }
        }

        if (
            !$confirmed &&
            ($currentQty + 1) > ($product->inventories_sum_quantity ?? 0)
        ) {
            $this->pendingProductId = $productId;
            $this->showStockConfirmation = true;
            return;
        }

        if ($existingKey !== null) {
            $this->cart[$existingKey]['quantity']++;
            $this->cart[$existingKey]['total'] =
                $this->calculateItemTotal($this->cart[$existingKey]);
        } else {
            $this->cart[] = [
                'id' => $product->id,
                'name' => $product->name,
                'quantity' => 1,
                'price' => $product->retail_price,
                'discount' => 0,
                'total' => $product->retail_price,
            ];
        }

        $this->calculateTotals();
    }

    public function confirmStockOverride()
    {
        $this->addToCart($this->pendingProductId, true);
        $this->pendingProductId = null;
        $this->showStockConfirmation = false;
    }

    public function cancelStockOverride()
    {
        $this->pendingProductId = null;
        $this->showStockConfirmation = false;
    }

    public function updateQuantity($index, $qty)
    {
        if (!isset($this->cart[$index])) return;

        $this->cart[$index]['quantity'] = max(1, (int)$qty);
        $this->cart[$index]['total'] =
            $this->calculateItemTotal($this->cart[$index]);

        $this->calculateTotals();
    }

    public function updatePrice($index, $price)
    {
        if (!isset($this->cart[$index])) return;

        $this->cart[$index]['price'] = (float)$price;
        $this->cart[$index]['total'] =
            $this->calculateItemTotal($this->cart[$index]);

        $this->calculateTotals();
    }

    public function updateItemDiscount($index, $discount)
    {
        if (!isset($this->cart[$index])) return;

        $this->cart[$index]['discount'] = (float)$discount;
        $this->cart[$index]['total'] =
            $this->calculateItemTotal($this->cart[$index]);

        $this->calculateTotals();
    }

    public function removeFromCart($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
        $this->calculateTotals();
    }

    private function calculateItemTotal($item)
    {
        $subtotal = $item['quantity'] * $item['price'];
        return $subtotal - ($subtotal * ($item['discount'] / 100));
    }

    /* =======================
     |  TOTALS
     =======================*/
    public function calculateTotals()
    {
        $this->subtotal = collect($this->cart)->sum('total');
        $this->total =
            $this->subtotal - ($this->subtotal * ($this->discount / 100));
        $this->calculateChange();
    }

    public function calculateChange()
    {
        $this->change = max(0, (float)$this->paymentAmount - $this->total);
    }

    /* =======================
     |  PAYMENT
     =======================*/
    public function processPayment()
    {
        if (!$this->cart) {
            $this->dispatch('notify', 'Cart is empty');
            return;
        }

        if (
            $this->paymentMethod === 'Cash' &&
            $this->paymentAmount < $this->total
        ) {
            $this->dispatch('notify', 'Insufficient cash payment');
            return;
        }

        DB::transaction(function () {

            $invoice = Invoice::create([
                'invoice_number' => 'INV-' . now()->timestamp,
                'subtotal' => $this->subtotal,
                'discount' => $this->discount,
                'total' => $this->total,
                'payment_amount' =>
                    $this->paymentMethod === 'Cash'
                        ? $this->paymentAmount
                        : $this->total,
                'change_amount' => $this->change,
                'status' => Invoice::STATUS_COMPLETED,
                'user_id' => auth()->id(),
            ]);

            foreach ($this->cart as $item) {

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'discount' => $item['discount'],
                    'total' => $item['total'],
                ]);

                $qty = $item['quantity'];

                $stocks = Inventory::where('product_id', $item['id'])
                    ->where('quantity', '>', 0)
                    ->orderBy('created_at')
                    ->lockForUpdate()
                    ->get();

                foreach ($stocks as $stock) {
                    if ($qty <= 0) break;

                    $deduct = min($stock->quantity, $qty);
                    $stock->decrement('quantity', $deduct);
                    $qty -= $deduct;
                }

                if ($qty > 0) {
                    Inventory::updateOrCreate(
                        [
                            'product_id' => $item['id'],
                            'is_adjustment' => true,
                        ],
                        [
                            'quantity' => DB::raw("quantity - {$qty}"),
                        ]
                    );
                }
            }

            InvoicePayment::create([
                'invoice_id' => $invoice->id,
                'amount' =>
                    $this->paymentMethod === 'Cash'
                        ? $this->paymentAmount
                        : $this->total,
                'payment_method' => strtolower($this->paymentMethod),
            ]);

            $this->lastInvoice = $invoice;
            $this->showInvoiceModal = true;
        });

        // Reset
        $this->reset([
            'cart',
            'subtotal',
            'discount',
            'total',
            'paymentAmount',
            'change',
            'barcode',
        ]);
    }

    /* =======================
     |  HELPERS
     =======================*/
    public function getProductStockCount($productId, $stock)
    {
        $cartQty = collect($this->cart)
            ->where('id', $productId)
            ->sum('quantity');

        return max(0, ($stock ?? 0) - $cartQty);
    }

    public function closeInvoiceModal()
    {
        $this->showInvoiceModal = false;
        $this->lastInvoice = null;
    }
};
?>



<div class="w-full h-full bg-gray-100 overflow-hidden relative"> <!-- Added relative for modal positioning -->
    
    <!-- STOCK CONFIRMATION MODAL -->
    @if($showStockConfirmation ?? false)
    <div class="absolute inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6 animate-in fade-in zoom-in duration-200">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Insufficient Stock</h3>
                <p class="text-sm text-gray-500 mb-6">
                    This item has 0 or insufficient stock available. Do you want to proceed adding it anyway?
                </p>
                <div class="flex gap-3 justify-center">
                    <button wire:click="cancelStockOverride" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium transition-colors">
                        Cancel
                    </button>
                    <button wire:click="confirmStockOverride" 
                            class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 font-medium transition-colors">
                        Yes, Add to Cart
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif


    <!-- INVOICE SUCCESS MODAL -->
    @if(($showInvoiceModal ?? false) && ($lastInvoice ?? null))
    <div class="absolute inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full p-6 animate-in fade-in zoom-in duration-200 flex flex-col h-[80vh]">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="text-lg font-bold text-gray-900">Invoice Generated</h3>
                <button wire:click="closeInvoiceModal" class="text-gray-400 hover:text-gray-600">×</button>
            </div>
            
            <div class="flex-1 overflow-y-auto font-mono text-sm">
                <div class="text-center mb-4">
                    <div class="font-bold text-lg">STORE FOR YOU</div>
                    <div class="text-xs text-gray-500">{{ $lastInvoice->created_at->format('Y-m-d H:i:s') }}</div>
                    <div class="text-xs text-gray-500">#{{ $lastInvoice->invoice_number }}</div>
                </div>

                <div class="border-b border-dashed border-gray-300 mb-2"></div>

                <div class="space-y-1 mb-2">
                    @foreach($lastInvoice->items as $item)
                    <div class="flex justify-between">
                        <div>
                            <div>{{ $item->product->name ?? 'Item' }}</div>
                            <div class="text-xs text-gray-500">{{ $item->quantity }} x {{ number_format($item->price, 2) }}</div>
                        </div>
                        <div class="font-bold">{{ number_format($item->total, 2) }}</div>
                    </div>
                    @endforeach
                </div>

                <div class="border-b border-dashed border-gray-300 mb-2"></div>

                <div class="flex justify-between font-bold">
                    <span>Subtotal</span>
                    <span>{{ number_format($lastInvoice->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-gray-500">
                    <span>Discount</span>
                    <span>-{{ number_format($lastInvoice->subtotal - $lastInvoice->total, 2) }}</span>
                </div>
                <div class="flex justify-between font-bold text-lg mt-2">
                    <span>Total</span>
                    <span>{{ number_format($lastInvoice->total, 2) }}</span>
                </div>
                 <div class="flex justify-between text-gray-500 mt-1">
                    <span>Paid ({{ ucfirst($lastInvoice->payments->first()->payment_method ?? 'cash') }})</span>
                    <span>{{ number_format($lastInvoice->payment_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-gray-500">
                    <span>Change</span>
                    <span>{{ number_format($lastInvoice->change_amount, 2) }}</span>
                </div>
            </div>

            <div class="mt-4 flex gap-3">
                 <button wire:click="closeInvoiceModal" 
                        class="flex-1 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium transition-colors">
                    Close
                </button>
                <button onclick="window.print()" 
                        class="flex-1 py-2 bg-zinc-900 text-white rounded-lg hover:bg-zinc-800 font-medium transition-colors">
                    Print
                </button>
            </div>
        </div>
    </div>
    @endif

    <div class="flex h-full w-full p-3 gap-3">

        <!-- LEFT SIDE (2/3) -->
        <div class="flex flex-col w-1/2 bg-white rounded-xl shadow-lg p-4">

            <!-- Title -->
            <div class="text-xl font-semibold mb-3">
                Sales Invoice #{{ $invoiceNumber ?? '00000' }}
            </div>

            <!-- Category + Search -->
            <div class="flex gap-3 mb-3">
                <select class="border p-2 rounded w-48">
                    <option>Retail</option>
                </select>

                <input type="text" 
                       placeholder="Scan Barcode / Product Code" 
                       wire:model.live.debounce.300ms="barcode"
                       wire:keydown.enter="scanBarcode"
                       class="border p-2 rounded w-full" 
                       autofocus />
            </div>

            <!-- CART TABLE -->
            <div class="border rounded-lg overflow-hidden flex-1 overflow-y-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-200 sticky top-0 z-10">
                        <tr>
                            <th class="p-2 w-10">#</th>
                            <th class="p-2">Name</th>
                            <th class="p-2 w-16">Qty</th>
                            <th class="p-2 w-24">Price</th>
                            <th class="p-2 w-20">Disc(%)</th>
                            <th class="p-2 w-24">Total</th>
                            <th class="p-2 w-8"></th>
                        </tr>
                    </thead>


                    <tbody>
                        @forelse($this->cart as $index => $item)
                        <tr class="border-b hover:bg-gray-50" wire:key="cart-item-{{ $index }}">
                            <td class="p-2 text-gray-500">{{ $loop->iteration }}</td>
                            <td class="p-2 font-medium">
                                {{ $item['name'] }}
                            </td>
                            <td class="p-2">
                                <input type="number" 
                                       class="border border-gray-300 w-16 p-1 rounded text-center focus:ring-1 focus:ring-zinc-500 outline-none" 
                                       wire:change="updateQuantity({{ $index }}, $event.target.value)"
                                       value="{{ $item['quantity'] }}" 
                                       min="1" />
                            </td>
                            <td class="p-2">
                                <input type="number" 
                                       step="0.01"
                                       class="border border-gray-300 w-20 p-1 rounded text-right focus:ring-1 focus:ring-zinc-500 outline-none" 
                                       wire:change="updatePrice({{ $index }}, $event.target.value)"
                                       value="{{ $item['price'] }}" />
                            </td>
                            <td class="p-2">
                                <input type="number" 
                                       step="0.1"
                                       class="border border-gray-300 w-16 p-1 rounded text-center focus:ring-1 focus:ring-zinc-500 outline-none" 
                                       wire:change="updateItemDiscount({{ $index }}, $event.target.value)"
                                       value="{{ $item['discount'] }}" />
                            </td>
                            <td class="p-2 font-bold text-zinc-800 text-right pr-4">
                                {{ number_format($item['total'], 2) }}
                            </td>
                            <td class="p-2 text-center">
                                <button type="button" 
                                        class="text-red-400 hover:text-red-600 font-bold px-2 py-1 rounded hover:bg-red-50 transition-colors"
                                        wire:click="removeFromCart({{ $index }})">
                                    ×
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <p>Cart is empty</p>
                                    <p class="text-xs text-gray-400 mt-1">Scan barcode or select product</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- TOTALS SECTION -->
            <div class="mt-auto border-t pt-4">
                <div class="space-y-3">
                    
                    <!-- Subtotal / Discount Row -->
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Global Discount (%)</label>
                            <input type="number" 
                                   wire:model.live.debounce.500ms="discount"
                                   class="w-full border-gray-300 rounded-xl shadow-sm focus:border-zinc-500 focus:ring-zinc-500 text-right font-bold text-lg p-3" 
                                   placeholder="0" />
                        </div>
                        <div class="flex-1 text-right">
                            <div class="text-sm text-gray-500 mb-1">Subtotal</div>
                            <div class="text-2xl font-bold text-gray-900">{{ number_format($subtotal, 2) }}</div>
                        </div>
                    </div>

                    <!-- Payment Method Row -->
                    <div class="flex gap-4">
                        <div class="w-1/3">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Payment</label>
                            <select class="w-full border-gray-300 rounded-xl shadow-sm focus:border-zinc-500 focus:ring-zinc-500 text-lg p-3 font-medium"
                                    wire:model="paymentMethod">
                                <option value="Cash">Cash</option>
                                <option value="Card">Card</option>
                                <option value="Transfer">Transfer</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Amount Paid</label>
                            <input type="number" 
                                   wire:model.live.debounce.500ms="paymentAmount"
                                   class="w-full border-gray-300 rounded-xl shadow-sm focus:border-zinc-500 focus:ring-zinc-500 text-right font-bold text-xl p-3" 
                                   placeholder="0.00" 
                                   step="0.01" />
                        </div>
                    </div>

                    <!-- Grand Total & Change -->
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div class="bg-zinc-900 text-white p-5 rounded-2xl shadow-lg">
                            <div class="flex justify-between items-start mb-1">
                                <div class="text-zinc-400 text-xs uppercase tracking-wider font-medium">Total Payable</div>
                                <div class="bg-zinc-800 text-zinc-300 text-xs px-2 py-1 rounded-md font-bold">{{ count($cart) }} Items</div>
                            </div>
                            <div class="text-3xl font-bold tracking-tight">{{ number_format($total, 2) }}</div>
                        </div>
                        <div class="bg-gray-100 text-gray-800 p-5 rounded-2xl shadow-inner border border-gray-200">
                            <div class="text-gray-500 text-xs uppercase tracking-wider font-medium">Change</div>
                            <div class="text-3xl font-bold tracking-tight">{{ number_format($change, 2) }}</div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-4">
                        <button class="w-full py-3 px-4 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors shadow-sm"
                                wire:click="processPayment">
                            Complete Order (F3)
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- RIGHT SIDE (1/3) -->
        <div class="w-1/2 bg-white rounded-xl shadow-lg flex flex-col p-4 overflow-hidden">
            <div class="text-xl font-semibold mb-4 text-zinc-800">Product List</div>

            <!-- Search -->
            <div class="relative mb-4">
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Search Items (Name or Code)..."
                       class="w-full border-gray-300 rounded-xl shadow-sm focus:border-zinc-500 focus:ring-zinc-500 p-3 pl-10" />
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>


            <!-- PRODUCT GRID -->
            <div class="grid grid-cols-3 gap-3 overflow-y-auto pr-1 pb-2 content-start">
            @forelse($this->products as $product)      
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md hover:border-zinc-300 transition-all cursor-pointer p-3 flex flex-col gap-2 group h-auto min-h-[100px]"
                     wire:click="addToCart({{ $product->id }})">
                    <div class="flex justify-between items-start">
                        <div class="text-xs {{ $this->getProductStockCount($product->id, $product->inventories_sum_quantity) <= 0 ? 'bg-red-100 text-red-600' : 'bg-zinc-100 text-zinc-500' }} px-2 py-1 rounded-md font-medium group-hover:bg-zinc-200 transition-colors">
                            Qty: {{ $this->getProductStockCount($product->id, $product->inventories_sum_quantity ?? 0) }}
                        </div>
                    </div>
                    <div>
                        <div class="text-lg font-bold text-zinc-900">Rs. {{ number_format($product->retail_price, 2) }}</div>
                        <div class="text-sm font-medium text-zinc-600 leading-tight mt-1 group-hover:text-zinc-900 transition-colors break-words">
                            {{ $product->name }} <br><span class="text-xs text-gray-400">{{ $product->barcode }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center text-gray-500 py-10">
                    No products found matching "{{ $search }}"
                </div>
            @endforelse
            </div>
        </div>

    </div>
</div>

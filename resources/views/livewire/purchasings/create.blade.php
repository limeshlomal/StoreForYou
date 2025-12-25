<?php

use Livewire\Volt\Component;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\User;
use App\Models\Purchasing;
use App\Models\Inventory;

new class extends Component {
    public $supplier_id;
    public $product_id;
    public $quantity;
    public $date;
    public $cost_price;

    public $items = [];
    public $supplier_search = '';
    public $product_search = '';

    public function with()
    {
        $suppliers = Supplier::where('name', 'like', "%{$this->supplier_search}%")
            ->limit(20)
            ->get();

        $products = Product::where('is_active', true)
            ->where(function($query) {
                $query->where('name', 'like', "%{$this->product_search}%")
                      ->orWhere('barcode', 'like', "%{$this->product_search}%");
            })
            ->limit(20)
            ->get();

        return [
            'suppliers' => $suppliers,
            'products' => $products,
        ];
    }
    
    public function selectSupplier($id, $name)
    {
        $this->supplier_id = $id;
        $this->supplier_search = $name;
    }

    public function selectProduct($id, $name)
    {
        $this->product_id = $id;
        $this->product_search = $name;
    }

    public function updatedSupplierSearch()
    {
        $this->supplier_id = null;
    }

    public function updatedProductSearch()
    {
        $this->product_id = null;
    }
    
    public function addItem() 
    {
        $this->validate([
            'supplier_id' => 'required',
            'date' => 'required',
            'product_id' => 'required',
            'quantity' => 'required|numeric|min:1',
            'cost_price' => 'required|numeric|min:0',
        ]);

        $product = Product::find($this->product_id);

        $this->items[] = [
            'product_id' => $this->product_id,
            'name' => $product->name,
            'barcode' => $product->barcode,
            'quantity' => $this->quantity,
            'cost_price' => $this->cost_price,
            'total' => $this->quantity * $this->cost_price,
        ];

        $this->reset(['product_id', 'quantity', 'cost_price', 'product_search']);
    }

    public function removeItem($index) 
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function save()
    {
        $this->validate([
            'supplier_id' => 'required',
            'date' => 'required|date',
            'items' => 'required|array|min:1',
        ]);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $purchasing = Purchasing::create([
                'supplier_id' => $this->supplier_id,
                'purchase_date' => $this->date, 
                'created_by' => auth()->id(), 
                'updated_by' => auth()->id(), 
            ]);

            foreach ($this->items as $item) {
                Inventory::create([
                    'purchasing_id' => $purchasing->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'cost_price' => $item['cost_price'],
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();

            $this->reset();
            $this->dispatch('show-success', message: 'Purchasing created successfully');
            $this->dispatch('close-modal');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            $this->dispatch('show-error', message: 'Failed to create purchasing: ' . $e->getMessage());
        }
    }



    public function getTotalProperty()
    {
        return collect($this->items)->sum('total');
    }

}; ?>

<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-8 px-4 sm:px-6 lg:px-8" x-data="{ showConfirm: false }" x-on:close-modal.window="showConfirm = false">
    <div class="max-w-7xl mx-auto space-y-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <flux:heading size="xl" class="text-slate-900 dark:text-white font-bold">Purchasing Management</flux:heading>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Create and manage your purchase orders efficiently.</p>
            </div>
            <div class="flex items-center gap-3">
                <flux:button variant="ghost" class="text-slate-600 dark:text-slate-300">Back to List</flux:button>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Entry Form (Left Column) -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <flux:heading size="lg" class="mb-6 border-b border-slate-100 dark:border-slate-700 pb-4">Add Item</flux:heading>

                    <form class="space-y-5">
                        <flux:input type="date" label="Date" class="w-full" wire:model="date" :disabled="count($items) > 0"/>

                        <div class="relative" x-data="{ open: false }">
                            <flux:input 
                                label="Supplier" 
                                placeholder="Search Supplier..." 
                                wire:model.live="supplier_search"
                                :disabled="count($items) > 0"
                                autocomplete="off"
                                x-on:focus="open = true"
                                x-on:click.outside="open = false"
                            />
                            @if(!$supplier_id)
                                <div x-show="open" x-cloak class="absolute z-50 w-full mt-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                    @forelse($suppliers as $supplier)
                                        <div 
                                            wire:click="selectSupplier({{ $supplier->id }}, '{{ $supplier->name }}')"
                                            x-on:click="open = false"
                                            class="px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-700 cursor-pointer text-sm text-slate-700 dark:text-slate-200"
                                        >
                                            {{ $supplier->name }}
                                        </div>
                                    @empty
                                        <div class="px-4 py-2 text-sm text-slate-500 dark:text-slate-400">No suppliers found.</div>
                                    @endforelse
                                </div>
                            @endif
                            @error('supplier_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="relative" x-data="{ open: false }">
                            <flux:input 
                                label="Product" 
                                placeholder="Search Product (Name or Barcode)..." 
                                wire:model.live="product_search"
                                autocomplete="off"
                                x-on:focus="open = true"
                                x-on:click.outside="open = false"
                            />
                            @if(!$product_id)
                                <div x-show="open" x-cloak class="absolute z-50 w-full mt-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                    @forelse($products as $product)
                                        <div 
                                            wire:click="selectProduct({{ $product->id }}, '{{ $product->name }}')"
                                            x-on:click="open = false"
                                            class="px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-700 cursor-pointer text-sm text-slate-700 dark:text-slate-200"
                                        >
                                            <div class="font-medium">{{ $product->name }}</div>
                                            <div class="text-xs text-slate-500">{{ $product->barcode }}</div>
                                        </div>
                                    @empty
                                        <div class="px-4 py-2 text-sm text-slate-500 dark:text-slate-400">No products found.</div>
                                    @endforelse
                                </div>
                            @endif
                            @error('product_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <flux:input type="number" min="1" label="Quantity" placeholder="0" wire:model="quantity"/>
                            <flux:input type="number" step="0.01" min="0" label="Cost Price" placeholder="0.00" icon="currency-dollar" wire:model="cost_price"/>
                        </div>

                        <div class="pt-2">
                            <flux:button variant="primary" class="w-full shadow-md hover:shadow-lg transition-shadow" wire:click.prevent="addItem">
                                Add to List
                            </flux:button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Items Table (Right Column) -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 flex flex-col h-full">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                        <flux:heading size="lg">Order Summary</flux:heading>
                        <span class="px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-700 text-xs font-medium text-slate-600 dark:text-slate-300">{{ count($items) }} Items</span>
                    </div>

                    <div class="flex-1 overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                            <thead class="bg-slate-50 dark:bg-slate-900/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Qty</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">CostPrice</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                                @forelse($items as $index => $item)
                                    <tr wire:key="item-{{ $index }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-slate-100">
                                            <div class="font-medium">{{ $item['name'] }}</div>
                                            <div class="text-slate-500 text-xs">{{ $item['barcode'] }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-slate-600 dark:text-slate-400">
                                            {{ $item['quantity'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-slate-600 dark:text-slate-400">
                                            {{ number_format($item['cost_price'], 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-slate-900 dark:text-slate-100">
                                            {{ number_format($item['quantity'] * $item['cost_price'], 2) }} 
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <button wire:click="removeItem({{ $index }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                                <flux:icon name="trash" class="w-4 h-4" />
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <!-- Empty State -->
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500 italic">
                                            No items added yet. Start by adding products from the form.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-slate-50 dark:bg-slate-900/50 font-semibold text-slate-900 dark:text-white">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right">Grand Total</td>
                                    <td class="px-6 py-4 text-right text-lg text-primary-600 dark:text-primary-400">Rs. {{ number_format($this->total, 2) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="p-6 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/30 rounded-b-2xl">
                        <flux:button 
                            @click="showConfirm = true" 
                            variant="primary" 
                            class="w-full justify-center py-3 text-lg shadow-lg shadow-primary-500/20 hover:shadow-primary-500/30 transition-all"
                            :disabled="count($items) === 0"
                        >
                            Complete Purchase Order
                        </flux:button>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- Confirm Save Modal -->
    <div x-show="showConfirm" x-cloak class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-500 bg-opacity-75 transition-opacity" @click="showConfirm = false"></div>

        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-slate-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium leading-6 text-slate-900 dark:text-white" id="modal-title">Confirm Purchase Order</h3>
                    <div class="mt-2">
                        <p class="text-sm text-slate-500 dark:text-slate-400">Are you sure you want to complete this purchase order? This action cannot be undone.</p>
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-900/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                    <flux:button wire:click="save" variant="primary" class="w-full sm:w-auto">Confirm</flux:button>
                    <flux:button @click="showConfirm = false" variant="ghost" class="w-full sm:w-auto mt-3 sm:mt-0">Cancel</flux:button>
                </div>
            </div>
        </div>
    </div>
</div>
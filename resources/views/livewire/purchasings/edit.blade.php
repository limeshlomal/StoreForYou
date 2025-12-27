<?php

use Livewire\Volt\Component;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\User;
use App\Models\Purchasing;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public Purchasing $purchasing;
    
    public $supplier_id;
    public $product_id;
    public $quantity;
    public $date;
    public $cost_price;

    public $items = [];
    public $supplier_search = '';
    public $product_search = '';
    
    // For delete confirmation
    public $showDeleteConfirm = false;

    public function mount(Purchasing $purchasing)
    {
        $this->purchasing = $purchasing->load('supplier', 'inventories.product');
        $this->supplier_id = $purchasing->supplier_id;
        $this->supplier_search = $purchasing->supplier->name ?? '';
        $this->date = $purchasing->purchase_date;
        
        // Load existing items
        foreach ($purchasing->inventories as $inventory) {
            $this->items[] = [
                'inventory_id' => $inventory->id, // Track existing ID
                'product_id' => $inventory->product_id,
                'name' => $inventory->product->name ?? 'Unknown Product',
                'barcode' => $inventory->product->barcode ?? '',
                'quantity' => $inventory->quantity,
                'cost_price' => $inventory->cost_price,
                'total' => $inventory->quantity * $inventory->cost_price,
            ];
        }
    }

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
            'product_id' => 'required',
            'quantity' => 'required|numeric|min:1',
            'cost_price' => 'required|numeric|min:0',
        ]);

        $product = Product::find($this->product_id);

        $this->items[] = [
            'inventory_id' => null, // New item
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

    public function update()
    {
        $this->validate([
            'supplier_id' => 'required',
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.cost_price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Update parent Purchasing record
            $this->purchasing->update([
                'supplier_id' => $this->supplier_id,
                'purchase_date' => $this->date,
                'updated_by' => auth()->id(),
            ]);

            // Get existing inventory IDs to track what needs to be deleted
            $existingInventoryIds = $this->purchasing->inventories()->pluck('id')->toArray();
            $processedInventoryIds = [];

            foreach ($this->items as $item) {
                if (!empty($item['inventory_id'])) {
                    // Update existing
                    Inventory::where('id', $item['inventory_id'])->update([
                        'quantity' => $item['quantity'],
                        'cost_price' => $item['cost_price'],
                        'updated_by' => auth()->id(),
                    ]);
                    $processedInventoryIds[] = $item['inventory_id'];
                } else {
                    // Create new
                    Inventory::create([
                        'purchasing_id' => $this->purchasing->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'cost_price' => $item['cost_price'],
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);
                }
            }

            // Delete removed items
            $idsToDelete = array_diff($existingInventoryIds, $processedInventoryIds);
            if (!empty($idsToDelete)) {
                Inventory::destroy($idsToDelete);
            }

            DB::commit();

            $this->dispatch('show-success', message: 'Purchasing updated successfully');
            $this->redirect(route('purchasings.index'), navigate: true);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-error', message: 'Failed to update purchasing: ' . $e->getMessage());
        }
    }
    
    public function deletePurchasing()
    {
        try {
            DB::beginTransaction();
            
            // Delete all inventories first (though cascade might handle this, better explicit)
            $this->purchasing->inventories()->delete();
            
            // Delete purchasing
            $this->purchasing->delete();
            
            DB::commit();
            
            $this->dispatch('show-success', message: 'Purchasing deleted successfully');
            $this->redirect(route('purchasings.index'), navigate: true);
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-error', message: 'Failed to delete: ' . $e->getMessage());
        }
    }

    public function getTotalProperty()
    {
        return collect($this->items)->sum(fn($item) => $item['quantity'] * $item['cost_price']);
    }

}; ?>

<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-8 px-4 sm:px-6 lg:px-8" x-data="{ showConfirm: false, showDeleteConfirm: @entangle('showDeleteConfirm') }">
    <div class="max-w-7xl mx-auto space-y-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <flux:heading size="xl" class="text-slate-900 dark:text-white font-bold">Edit Purchasing #{{ $purchasing->id }}</flux:heading>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Update details, modify items, or manage this order.</p>
            </div>
            <div class="flex items-center gap-3">
                <flux:button href="{{ route('purchasings.index') }}" variant="ghost" class="text-slate-600 dark:text-slate-300">Back to List</flux:button>
                <flux:button @click="showDeleteConfirm = true" variant="danger" class="bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50">Delete Order</flux:button>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Entry Form (Left Column) -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Order Details Card -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <flux:heading size="lg" class="mb-6 border-b border-slate-100 dark:border-slate-700 pb-4">Order Details</flux:heading>
                    <div class="space-y-4">
                        <flux:input type="date" label="Date" class="w-full" wire:model="date"/>
                        
                        <div class="relative" x-data="{ open: false }">
                            <flux:input 
                                label="Supplier" 
                                placeholder="Search Supplier..." 
                                wire:model.live="supplier_search"
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
                    </div>
                </div>

                <!-- Add New Item Card -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <flux:heading size="lg" class="mb-6 border-b border-slate-100 dark:border-slate-700 pb-4">Add New Item</flux:heading>

                    <form class="space-y-5">
                        <div class="relative" x-data="{ open: false }">
                            <flux:input 
                                label="Product" 
                                placeholder="Search Product..." 
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
                            <flux:button variant="primary" class="w-full shadow-md" wire:click.prevent="addItem">
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
                        <flux:heading size="lg">Order Items</flux:heading>
                        <span class="px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-700 text-xs font-medium text-slate-600 dark:text-slate-300">{{ count($items) }} Items</span>
                    </div>

                    <div class="flex-1 overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                            <thead class="bg-slate-50 dark:bg-slate-900/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Qty</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Cost</th>
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
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <input type="number" min="1" wire:model="items.{{ $index }}.quantity" class="w-20 text-right text-sm border-slate-200 rounded-md focus:ring-primary-500 focus:border-primary-500 dark:bg-slate-900 dark:border-slate-700 dark:text-slate-200">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                             <input type="number" step="0.01" min="0" wire:model="items.{{ $index }}.cost_price" class="w-24 text-right text-sm border-slate-200 rounded-md focus:ring-primary-500 focus:border-primary-500 dark:bg-slate-900 dark:border-slate-700 dark:text-slate-200">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-slate-900 dark:text-slate-100">
                                            {{ number_format((float)$item['quantity'] * (float)$item['cost_price'], 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <button wire:click="removeItem({{ $index }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                                <flux:icon name="trash" class="w-4 h-4" />
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500 italic">
                                            No items. Add products to this order.
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
                            class="w-full justify-center py-3 text-lg shadow-lg hover:shadow-xl transition-all"
                        >
                            Update Purchasing
                        </flux:button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirm Update Modal -->
    <div x-show="showConfirm" x-cloak class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-500 bg-opacity-75 transition-opacity" @click="showConfirm = false"></div>

        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-slate-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium leading-6 text-slate-900 dark:text-white" id="modal-title">Save Changes?</h3>
                    <div class="mt-2">
                        <p class="text-sm text-slate-500 dark:text-slate-400">Are you sure you want to update this purchase order?</p>
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-900/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                    <flux:button wire:click="update" variant="primary" class="w-full sm:w-auto">Save Changes</flux:button>
                    <flux:button @click="showConfirm = false" variant="ghost" class="w-full sm:w-auto mt-3 sm:mt-0">Cancel</flux:button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Entire Order Modal -->
    <div x-show="showDeleteConfirm" x-cloak class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-500 bg-opacity-75 transition-opacity" @click="showDeleteConfirm = false"></div>

        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-slate-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium leading-6 text-red-600 dark:text-red-400 flex items-center gap-2">
                        <flux:icon name="exclamation-triangle" class="w-6 h-6" />
                        Delete Purchase Order
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-slate-500 dark:text-slate-400">Are you sure you want to delete this entire purchase order? <br><br><strong>This action will remove the record and all its {{ count($items) }} associated items permanently.</strong></p>
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-900/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                    <flux:button wire:click="deletePurchasing" variant="danger" class="w-full sm:w-auto">Yes, Delete Everything</flux:button>
                    <flux:button @click="showDeleteConfirm = false" variant="ghost" class="w-full sm:w-auto mt-3 sm:mt-0">Cancel</flux:button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

use Livewire\Volt\Component;
use App\Models\Purchasing;
use App\Models\Inventory;
use Livewire\WithPagination;
use App\Models\Supplier;

new class extends Component {
    use WithPagination;
    public $search = '';
    public $perPage = 10;
    
    public function with()
    {
        return [
            'purchasings' => Purchasing::query()
                ->with('inventories')
                ->withCount('inventories')
                ->where('id', 'like', "%{$this->search}%")
                ->orWhere('supplier_id', 'like', "%{$this->search}%")
                ->latest()
                ->paginate($this->perPage)
        ]; 
    }

    public function totalAmount($purchasing)
    {
        return $purchasing->inventories->sum(function($inventory) {
            return $inventory->quantity * $inventory->cost_price;
        });
    }

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

}; ?>

<div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-8 px-4">
    <div class="max-w-7xl mx-auto">
        {{-- Page Header --}}
        <div class="mb-6">
            <flux:heading size="xl">Purchasing List</flux:heading>
            <p class="text-gray-600 mt-1">Manage Your Purchasings</p>
        </div>

        <div class="overflow-x-auto bg-white shadow rounded-lg p-4">
               <div class="flex justify-between items-center mb-4">
                    <flux:input 
                        wire:model.live="search" 
                        placeholder="Search categories..." 
                        class="w-64"
                    />
                </div>
            <table class="min-w-full border border-gray-200">
                <thead class="bg-gray-100 text-gray-700 text-sm uppercase">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">#</th>
                        <th class="px-6 py-3 text-left font-semibold">Purchasing No</th>
                        <th class="px-6 py-3 text-left font-semibold">Supplier Name</th>
                        <th class="px-6 py-3 text-left font-semibold">Purchase Date</th>
                        <th class="px-6 py-3 text-left font-semibold">No of Items</th>
                        <th class="px-6 py-3 text-left font-semibold">Total Amount</th>
                        <th class="px-6 py-3 text-left font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                    @forelse ($purchasings as $purchasing)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-medium">{{ $purchasing->id }}</td>
                        <td class="px-6 py-4">{{ $purchasing->supplier->name }}</td>
                        <td class="px-6 py-4">{{ $purchasing->purchase_date }}</td>
                        <td class="px-6 py-4">{{ $purchasing->inventories_count }}</td>
                        <td class="px-6 py-4">{{ number_format($this->totalAmount($purchasing), 2) }}</td>
                        <td class="px-6 py-4 flex space-x-2">
                            <a class="text-blue-600 hover:text-blue-800 font-medium" href="{{ route('purchasings.edit', $purchasing->id) }}">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No purchasings found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Pagination --}}
        
        @if ($purchasings->hasPages())
            <div class="mt-4">
                {{ $purchasings->links() }}
            </div>
        @endif
        
    </div>
</div>

<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Product;

new class extends Component {
    use WithPagination;
    public $search = '';
    public $perPage = 10;

    public function with()
    {
       return [
           'products' => Product::query()
               ->where('name', 'like', "%{$this->search}%")
               ->orWhere('barcode', 'like', "%{$this->search}%")
               ->latest()
               ->paginate($this->perPage)
       ]; 
    }
}; ?>

<div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-8 px-4">
    <div class="max-w-7xl mx-auto">
        {{-- Page Header --}}
        <div class="mb-6">
            <flux:heading size="xl">Product List</flux:heading>
            <p class="text-gray-600 mt-1">Manage Your inventory</p>
        </div>


        <div class="overflow-x-auto bg-white shadow rounded-lg p-4">
    <table class="min-w-full border border-gray-200">
        <thead class="bg-gray-100 text-gray-700 text-sm uppercase">
            <tr>
                <th class="px-6 py-3 text-left font-semibold">#</th>
                <th class="px-6 py-3 text-left font-semibold">Product Barcode/Code</th>
                <th class="px-6 py-3 text-left font-semibold">Product Name</th>
                <th class="px-6 py-3 text-left font-semibold">Category</th>
                <th class="px-6 py-3 text-left font-semibold">Alert Quantity</th>
                <th class="px-6 py-3 text-left font-semibold">Current Stock</th>
                <th class="px-6 py-3 text-left font-semibold">Price (Rs)</th>
                <th class="px-6 py-3 text-left font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 text-sm text-gray-700">        
                @forelse ($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 font-medium">{{ $product->barcode }}</td>
                    <td class="px-6 py-4">{{ $product->name }}</td>
                    <td class="px-6 py-4">{{ $product->category->name }}</td>
                    <td class="px-6 py-4">{{ $product->alert_quantity }}</td>
                    <td class="px-6 py-4">{{ $product->stock }}</td>
                    <td class="px-6 py-4">150</td>
                    <td class="px-6 py-4 flex space-x-2">
                        <a class="text-blue-600 hover:text-blue-800 font-medium">Edit</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">No products found.</td>
                </tr>
                @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if ($products->hasPages())
    <div class="mt-4">
        {{ $products->links() }}
    </div>
@endif

    </div>
</div>

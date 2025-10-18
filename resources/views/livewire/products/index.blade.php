<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-8 px-4">
    <div class="max-w-4xl mx-auto">
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

                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">1</td>
                    <td class="px-6 py-4 font-medium">124</td>
                    <td class="px-6 py-4">Laptop</td>
                    <td class="px-6 py-4">Tech</td>
                    <td class="px-6 py-4">5</td>
                    <td class="px-6 py-4">200</td>
                    <td class="px-6 py-4">150</td>
                    <td class="px-6 py-4 flex space-x-2">
                        <a class="text-blue-600 hover:text-blue-800 font-medium">Edit</button>
                    </td>
                </tr>
        </tbody>
    </table>
</div>

    </div>
</div>

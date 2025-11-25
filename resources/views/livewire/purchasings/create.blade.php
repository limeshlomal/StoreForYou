<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-8 px-4">
    <div class="max-w-7xl mx-auto space-y-8">

        <div>
            <flux:heading size="xl">Purchasing Management</flux:heading>
            <p class="text-gray-600 mt-1">Create and manage your Purchasings</p>
        </div>

        <!-- Horizontal Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-6">
            <flux:heading size="lg" class="mb-4">Add New Purchasing</flux:heading>

            <form class="grid grid-cols-1 xl:grid-cols-6 md:grid-cols-3 sm:grid-cols-2 gap-4">

                <flux:input type="date" label="Date" />

                <flux:select label="Supplier">
                    <option value="">Select Supplier</option>
                </flux:select>

                <flux:select label="Product">
                    <option value="">Select Product</option>
                </flux:select>

                <flux:input type="number" min="1" label="Qty" placeholder="1" />

                <flux:input type="number" step="0.01" min="0" label="Price" placeholder="0.00" />

                <div class="flex items-end">
                    <flux:button variant="primary" class="w-full">
                        Add
                    </flux:button>
                </div>

            </form>
        </div>

        <!-- Table Below Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-6">
            <flux:heading size="lg" class="mb-4">Items Added</flux:heading>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Supplier</th>
                            <th class="px-4 py-2">Product</th>
                            <th class="px-4 py-2">Qty</th>
                            <th class="px-4 py-2">Price</th>
                            <th class="px-4 py-2">Total</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        
                    </tbody>
                </table>
            </div>

            <!-- Final Submit Button -->
            <flux:button variant="primary" class="w-full justify-center">
                Submit Purchasing
            </flux:button>
        </div>

    </div>
</div>

<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-8 px-4 sm:px-6 lg:px-8">
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
                        <flux:input type="date" label="Date" class="w-full" />

                        <flux:select label="Supplier" placeholder="Select Supplier">
                            <option value="">Select Supplier</option>
                        </flux:select>

                        <flux:select label="Product" placeholder="Search Product...">
                            <option value="">Select Product</option>
                        </flux:select>

                        <div class="grid grid-cols-2 gap-4">
                            <flux:input type="number" min="1" label="Quantity" placeholder="0" />
                            <flux:input type="number" step="0.01" min="0" label="Unit Price" placeholder="0.00" icon="currency-dollar" />
                        </div>

                        <div class="pt-2">
                            <flux:button variant="primary" class="w-full shadow-md hover:shadow-lg transition-shadow">
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
                        <span class="px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-700 text-xs font-medium text-slate-600 dark:text-slate-300">0 Items</span>
                    </div>

                    <div class="flex-1 overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                            <thead class="bg-slate-50 dark:bg-slate-900/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Qty</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                                <!-- Empty State -->
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500 italic">
                                        No items added yet. Start by adding products from the form.
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-slate-50 dark:bg-slate-900/50 font-semibold text-slate-900 dark:text-white">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right">Grand Total</td>
                                    <td class="px-6 py-4 text-right text-lg text-primary-600 dark:text-primary-400">Rs. 0.00</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="p-6 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/30 rounded-b-2xl">
                        <flux:button variant="primary" class="w-full justify-center py-3 text-lg shadow-lg shadow-primary-500/20 hover:shadow-primary-500/30 transition-all">
                            Complete Purchase Order
                        </flux:button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

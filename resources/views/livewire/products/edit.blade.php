<?php

use Livewire\Volt\Component;

new class extends Component {
    
}; ?>

<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-8 px-4 sm:px-6 lg:px-8" x-data="{ showDeleteConfirm: @entangle('showDeleteConfirm') }">
    <div class="max-w-7xl mx-auto space-y-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <flux:heading size="xl" class="text-slate-900 dark:text-white font-bold">Edit Product</flux:heading>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Manage product details, pricing, and settings.</p>
            </div>
            <div class="flex items-center gap-3">
                <flux:button href="{{ route('products.index') }}" variant="ghost" class="text-slate-600 dark:text-slate-300">Back to List</flux:button>
                <flux:button @click="showDeleteConfirm = true" variant="danger" class="bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50">Delete Product</flux:button>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Main Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Details Card -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <flux:heading size="lg" class="mb-6 border-b border-slate-100 dark:border-slate-700 pb-4">Basic Information</flux:heading>
                    
                    <div class="space-y-6">
                        <flux:input label="Product Name" wire:model="name" placeholder="e.g. Wireless Mouse" />
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <flux:input label="Barcode / SKU" wire:model="barcode" icon="qr-code" placeholder="Scan or enter barcode" />
                             <!-- Placeholder for categories for now if Category model exists but I don't want to break it if it's missing or empty. Using native select or flux select if available. 
                                  User codebase has 'categories' table implied by Product model relationship.
                             -->
                             <div>
                                <flux:label>Category</flux:label>
                                <select wire:model="category_id" class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 py-2 px-3">
                                    <option value="">Select Category</option>
                                
                                </select>
                            </div>
                        </div>

                        <flux:textarea label="Description" wire:model="description" rows="4" placeholder="Enter detailed product description..." />
                    </div>
                </div>
            </div>

            <!-- Right Column: Pricing & Meta -->
            <div class="lg:col-span-1 space-y-6 form-sidebar">
                
                <!-- Pricing Card -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <flux:heading size="lg" class="mb-6 border-b border-slate-100 dark:border-slate-700 pb-4">Pricing</flux:heading>
                    
                    <div class="space-y-4">
                        <flux:input type="number" step="0.01" label="Retail Price" wire:model="retail_price" icon="currency-dollar" placeholder="0.00" />
                        
                        <!-- Cost price is not in fillable, but often requested. Leaving it out as per explicit model fields for now to avoid errors, or adding as a disabled field if needed? 
                             The user said "remove functionalities", implies simplifying. I'll stick to what's in the Model.
                        -->
                    </div>
                </div>

                <!-- Inventory & Status Card -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <flux:heading size="lg" class="mb-6 border-b border-slate-100 dark:border-slate-700 pb-4">Inventory & Status</flux:heading>
                    
                    <div class="space-y-4">
                        <flux:input type="number" label="Alert Quantity" wire:model="alert_quantity" placeholder="e.g. 10" />
                        
                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg border border-slate-100 dark:border-slate-700">
                            <div>
                                <span class="font-medium text-slate-900 dark:text-white block">Active Status</span>
                                <span class="text-xs text-slate-500 dark:text-slate-400">Hide from POS if inactive</span>
                            </div>
                            <!-- Simple toggle implementation -->
                            <button 
                                type="button" 
                                wire:click="$toggle('is_active')" 
                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                                :class="{ 'bg-primary-600': @entangle('is_active'), 'bg-slate-200 dark:bg-slate-700': !@entangle('is_active') }"
                            >
                                <span class="sr-only">Use setting</span>
                                <span 
                                    aria-hidden="true" 
                                    class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                    :class="{ 'translate-x-5': @entangle('is_active'), 'translate-x-0': !@entangle('is_active') }"
                                ></span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Action Card -->
                 <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                     <flux:button wire:click="save" variant="primary" class="w-full justify-center py-3 text-lg shadow-lg hover:shadow-xl transition-all">
                        Save Changes
                     </flux:button>
                 </div>

            </div>

        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteConfirm" x-cloak class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-500 bg-opacity-75 transition-opacity" @click="showDeleteConfirm = false"></div>

        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-slate-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium leading-6 text-red-600 dark:text-red-400 flex items-center gap-2">
                        <flux:icon name="exclamation-triangle" class="w-6 h-6" />
                        Delete Product
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-slate-500 dark:text-slate-400">Are you sure you want to delete this product? This action cannot be undone.</p>
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-900/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                    <flux:button wire:click="delete" variant="danger" class="w-full sm:w-auto">Yes, Delete</flux:button>
                    <flux:button @click="showDeleteConfirm = false" variant="ghost" class="w-full sm:w-auto mt-3 sm:mt-0">Cancel</flux:button>
                </div>
            </div>
        </div>
    </div>
</div>

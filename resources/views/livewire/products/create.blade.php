<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>
<div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-8 px-4">
    <div class="max-w-4xl mx-auto">
        {{-- Page Header --}}
        <div class="mb-6">
            <flux:heading size="xl">Add New Product</flux:heading>
            <p class="text-gray-600 mt-1">Create a new product for your inventory</p>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- Product Form --}}
        <form wire:submit="save" class="space-y-6">

            {{-- Basic Information Card --}}
            <div class="bg-white rounded-lg shadow p-6 space-y-4">
                <flux:heading size="lg" class="mb-4">Basic Information</flux:heading>

                {{-- Barcode/Code --}}
                <flux:input wire:model="barcode" label="Barcode/Code" placeholder="Enter Product Barcode/Code"
                    description="Product Barcode/Code - must be unique" required />

                {{-- Product Name --}}
                <flux:input wire:model="name" label="Product Name" placeholder="Enter product name" required />

                {{-- Category --}}
                <flux:select wire:model="category_id" label="Category">
                    <option value="">Select Category</option>
                </flux:select>             


                {{-- Stock --}}
                <flux:input wire:model="stock" type="number" min="0" label="Stock Quantity" placeholder="0"
                    required />

                {{-- Price --}}
                <flux:input wire:model="price" type="number" step="0.01" min="0" label="Price"
                    placeholder="0.00" required />
            </div>


            {{-- Action Buttons --}}
            <div class="flex gap-3 justify-end">
                <flux:button type="button" variant="ghost" wire:click="cancel">
                    Cancel
                </flux:button>

                <flux:button type="submit" variant="primary">
                    Save Product
                </flux:button>
            </div>
        </form>
    </div>
</div>

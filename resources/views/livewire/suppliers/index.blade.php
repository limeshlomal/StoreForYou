<?php

use Livewire\Volt\Component;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public $supplier_code;
    public $supplier_name;
    public $supplier_mobile;
    public $supplier_address;
    public $search = '';

    public function save() 
    {
        $this->validate([
            'supplier_code' => 'required',
            'supplier_name' => 'required|string'
        ]);

        try {
            if(Supplier::where('code', $this->supplier_code)->exists()){
                $this->dispatch('show-error', message: 'Supplier code already exists. Please use a different code');
                return;
            }

            Supplier::create([
                'code' => $this->supplier_code,
                'name' => $this->supplier_name,
                'mobile' => $this->supplier_mobile,
                'address' => $this->supplier_address,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            $this->reset();
            $this->dispatch('show-success', message:'Supplier created successfully!');

        } catch (\Exception $e) {
            \Log::error('Supplier creation error: ' . $e->getMessage());
            $this->dispatch('show-error', message: 'An error occured while creating supplier. Please try again. Error: ' . $e->getMessage());
        }
    }
    
}; ?>

<div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-8 px-4">
    <div class="max-w-7xl mx-auto">
        {{-- Page Header --}}
        <div class="mb-8">
            <flux:heading size="xl">Supplier Management</flux:heading>
            <p class="text-gray-600 mt-1">Create and manage your suppliers</p>
        </div>

        {{-- Main Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Left side - Create Supplier --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                <flux:heading size="lg" class="mb-4">Add New Supplier</flux:heading>

                <form wire:submit="save" class="space-y-6">
                     {{-- Supplier Code--}}
                    <flux:input wire:model="supplier_code" label="Code" placeholder="Enter Supplier Code" required/>

                    {{-- Supplier Name--}}
                    <flux:input wire:model="supplier_name" label="Name" placeholder="Enter Supplier Name" required/> 

                    {{-- Supplier mobile--}}
                    <flux:input wire:model="supplier_mobile" label="Mobile" placeholder="Enter Supplier Mobile" required/>

                    {{-- Supplier Address--}}
                    <flux:input wire:model="supplier_address" label="Address" placeholder="Enter Supplier Address" required/>

                    {{-- Action Buttons--}}
                    <div class="flex gap-3 justify-end">
                        <flux:button type="button" variant="ghost" wire:click="cancel">
                            Cancel
                        </flux:button>

                        <flux:button type="submit" variant="primary">
                            Save
                        </flux:button>
                    </div>

                </form>
            </div>
            {{-- Right side - Suppliers List--}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-4">      
                <div class="flex justify-between items-center">
                    <flux:input wire:model="search" placeholder="Search suppliers..." class="w-64"/>
                </div>
                <div class="overflow-x-auto">
                    
                </div>      
            </div>
        </div>


    </div>
</div>

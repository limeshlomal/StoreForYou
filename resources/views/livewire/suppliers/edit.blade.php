<?php

use Livewire\Volt\Component;
use App\Models\Supplier;
use Illuminate\Validation\Rule;

new class extends Component {
    public ?Supplier $supplier = null;
    public $code = '';
    public $name = '';
    public $mobile = '';
    public $address = '';
    
    public $showModal = false;

    protected $listeners = ['edit-supplier' => 'loadSupplier'];

    public function loadSupplier($id)
    {
        $this->supplier = Supplier::find($id);
        
        if ($this->supplier) {
            $this->code = $this->supplier->code;
            $this->name = $this->supplier->name;
            $this->mobile = $this->supplier->mobile;
            $this->address = $this->supplier->address;
            $this->showModal = true;
            $this->resetValidation();
        }
    }

    public function update()
    {
        $this->validate([
            'code' => ['required', Rule::unique('suppliers', 'code')->ignore($this->supplier->id)],
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        try {
            $this->supplier->update([
                'code' => $this->code,
                'name' => $this->name,
                'mobile' => $this->mobile,
                'address' => $this->address,
                'updated_by' => auth()->id(),
            ]);

            $this->showModal = false;
            $this->dispatch('supplier-updated'); // Tell parent to refresh
            $this->dispatch('show-success', message: 'Supplier updated successfully details');

        } catch (\Exception $e) {
            $this->dispatch('show-error', message: 'Failed to update supplier: ' . $e->getMessage());
        }
    }
}; ?>

<div x-data="{ show: @entangle('showModal') }">
    <div x-show="show" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="show = false"></div>

        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">Edit Supplier</h3>
                            <div class="mt-4 space-y-4">
                                <flux:input wire:model="code" label="Code" placeholder="Enter Supplier Code" />
                                <flux:input wire:model="name" label="Name" placeholder="Enter Supplier Name" />
                                <flux:input wire:model="mobile" label="Mobile" placeholder="Enter Supplier Mobile" />
                                <flux:input wire:model="address" label="Address" placeholder="Enter Supplier Address" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <flux:button wire:click="update" variant="primary" class="w-full sm:w-auto">Update</flux:button>
                    <flux:button @click="show = false" variant="ghost" class="w-full sm:w-auto mt-3 sm:mt-0">Cancel</flux:button>
                </div>
            </div>
        </div>
    </div>
</div>

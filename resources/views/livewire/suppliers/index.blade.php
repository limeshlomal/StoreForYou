<?php

use Livewire\Volt\Component;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

new class extends Component {
    public $supplier_code;
    public $supplier_name;
    public $supplier_mobile;
    public $supplier_address;
    public $search = '';

    use WithPagination;
    public $perPage = 5;

    public function with()
    {
        return [
            'suppliers' => Supplier::query()
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('code', 'like', "%{$this->search}%")
                ->latest()
                ->paginate($this->perPage)
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

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
                    <flux:input wire:model.live="search" placeholder="Search suppliers..." class="w-64"/>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200">
                        <thead class="bg-gray-100 text-gray-700 text-sm uppercase">
                            <tr>
                                 <th class="px-6 py-3 text-left font-semibold">#</th>
                                 <th class="px-6 py-3 text-left font-semibold">Code</th>
                                 <th class="px-6 py-3 text-left font-semibold">Name</th>
                                 <th class="px-6 py-3 text-left font-semibold">Mobile</th>
                                 <th class="px-6 py-3 text-left font-semibold">Address</th>
                                 <th class="px-6 py-3 text-left font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                            @forelse ($suppliers as $supplier)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 font-medium">{{ $supplier->code }}</td>
                                <td class="px-6 py-4">{{ $supplier->name }}</td>
                                <td class="px-6 py-4">{{ $supplier->mobile }}</td>
                                <td class="px-6 py-4">{{ $supplier->address }}</td>
                                <td class="px-6 py-4 flex space-x-2">
                                    <flux:button type="button" size="sm" variant="danger" wire:click="edit({{ $supplier->id }})">
                                        Edit
                                    </flux:button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No suppliers found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>     
                {{-- Pagination --}}
                @if ($suppliers->hasPages())
                    <div class="mt-4">
                        {{ $suppliers->links() }}
                    </div>
                @endif 
            </div>
        </div>


    </div>
</div>

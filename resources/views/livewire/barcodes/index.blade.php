<?php

use Livewire\Volt\Component;
use App\Models\Product;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $selectedProducts = [];

    // Reset page on search change
    public function updatingSearch()
    {
        $this->resetPage();
    }

    //render/load products into page
    public function render():mixed
    {
        $products = Product::where('name', 'like', "%{$this->search}%")
                            ->paginate($this->perPage);

        return view('livewire.barcodes.index', [
            'products' => $products
        ]);

    }

    //generate barcode
    public function generate()
    {
        if(empty($this->selectedProducts))
        {
            $this->dispatch('alert', type: 'error', message: 'Please select at least one product');
            return;
        }

        return redirect()->route('barcodes.print', ['ids'=>implode(',', $this->selectedProducts)]);
       
    }


}; ?>

<div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-8 px-4">
    <div class="max-w-7xl mx-auto">
        {{-- Page Header --}}
        <div class="mb-8">
            <flux:heading size="xl">Barcode/Labels Management</flux:heading>
            <p class="text-gray-600 mt-1">Create and manage your Barcode/Labels</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
               <flux:heading size="lg" class="mb-4">Add Barcode/Label</flux:heading>

               <form wire:submit.prevent="generate">
                <div class="grid grid-cols-3 gap-2 mb-4">
                    @foreach ($products as $product)
                        <label class="flex items-center space-x-2 border p-2 rounded">
                            <input type="checkbox" wire:model="selectedProducts" value="{{ $product->id }}">
                            <span>{{ $product->name }}/ {{$product->code}}</span>
                        </label>
                    @endforeach
                </div>

                {{ $products->links() }}
                
                <flux:button type="submit" variant="primary" class="mt-4 float-right">
                    Generate Barcodes
                </flux:button>
               </form>

        </div>
    </div>

</div>
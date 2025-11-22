<?php

use Livewire\Volt\Component;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;
    
public function with()
{
    return [
       'categories' => Category::where('is_active', true)->orderBy('name')->get()
    ];
}

public $barcode;
public $name;
public $category_id;
public $alert_quantity;
public $price;
public $product_image;


public function save()
{
    $this->validate([
        'barcode' => 'required',
        'name' => 'required|string|max:255',
        'category_id' => 'required',
        'alert_quantity' => 'required|integer|min:0',
        'price' => 'required',
        'product_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    // Show confirmation modal
    $this->dispatch('show-confirmation');
}

public function confirmSave()
{
    try {
        if(Product::where('barcode', $this->barcode)->exists())
        {
            $this->dispatch('show-error', message:'Product code already exists. Please use different code!');
            return;
        }

        Product::create([
            'barcode' => $this->barcode,
            'name' => $this->name,
            'category_id' => $this->category_id,
            'alert_quantity' => $this->alert_quantity,
            'retail_price' => $this->price,
            'product_image' => $this->product_image->store('products', 'public'),
            'is_active' => true,
            'created_by' => Auth::id()
        ]);

        $this->reset();
        $this->dispatch('show-success', message: 'Product created successfully!');

    } catch (\Throwable $th) {
        $this->dispatch('show-error', message: 'An error occured while creating the product. Please try again!');
    }
}

    public function cancel()
    {
        $this->reset();
    }

    
}; 

?>
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
        <div class="space-y-6">

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
                    @forelse ($categories as $category)
                        <option value="{{$category->id}}">{{ $category->code }}-{{ $category->name}}</option>
                    @empty
                        <option disabled>Not Available</option>
                    @endforelse
                    
                </flux:select>             


                {{-- Alert Quantity --}}
                <flux:input wire:model="alert_quantity" type="number" min="0" label="Alert Quantity" placeholder="0"
                    required />

                {{-- Price --}}
                <flux:input wire:model="price" type="number" step="0.01" min="0" label="Price"
                    placeholder="0.00" required />
                
                {{-- Image --}}
                <label class="font-medium mb-1 block">Product Image</label>
                <input type="file" wire:model="product_image" class="border p-2 rounded w-full" accept="image/*">
                @error('product_image') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>


            {{-- Action Buttons --}}
            <div class="flex gap-3 justify-end">
                <flux:button type="button" variant="ghost" wire:click="cancel">
                    Cancel
                </flux:button>

                <flux:button type="button" variant="primary" wire:click="save">
                    Save Product
                </flux:button>
            </div>
        </div>
    </div>
    
    <!-- Confirmation Modal -->
    <div x-data="{ showConfirm: false }" x-show="showConfirm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold mb-4">Confirm Product Creation</h3>
        <div class="space-y-2 mb-6">
            <p><strong>Barcode:</strong> {{ $barcode }}</p>
            <p><strong>Name:</strong> {{ $name }}</p>
            <p><strong>Category:</strong> {{ $categories->where('id', $category_id)->first()->name ?? 'N/A' }}</p>
            <p><strong>Alert Quantity:</strong> {{ $alert_quantity }}</p>
            <p><strong>Price:</strong> ${{ number_format($price, 2) }}</p>
        </div>
        <div class="flex gap-3 justify-end">
            <button @click="showConfirm = false" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-50">Cancel</button>
            <button wire:click="confirmSave" @click="showConfirm = false" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Confirm & Create</button>
        </div>
    </div>
</div>
</div>
<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('show-confirmation', () => {
        // Find the modal and show it
        const modal = document.querySelector('[x-data*="showConfirm"]');
        if (modal) {
            modal._x_dataStack[0].showConfirm = true;
        }
    });
});
</script>

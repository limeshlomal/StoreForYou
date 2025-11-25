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
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        {{-- Page Header --}}
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <flux:heading size="xl" class="text-slate-900 dark:text-white font-bold">Add New Product</flux:heading>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Create a new product for your inventory</p>
            </div>
            <div class="flex gap-3">
                <flux:button variant="ghost" wire:click="cancel" class="text-slate-600">Cancel</flux:button>
                <flux:button variant="primary" wire:click="save" class="shadow-lg shadow-primary-500/20">Save Product</flux:button>
            </div>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column: Basic Info --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <flux:heading size="lg" class="mb-6 border-b border-slate-100 dark:border-slate-700 pb-4">Basic Information</flux:heading>
                    
                    <div class="space-y-6">
                        <flux:input wire:model="name" label="Product Name" placeholder="e.g. Cotton T-Shirt" required />
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <flux:input wire:model="barcode" label="Barcode" placeholder="Scan or enter code" icon="qr-code" required />
                            
                            <flux:select wire:model="category_id" label="Category" placeholder="Select Category">
                                <option value="">Select Category</option>
                                @forelse ($categories as $category)
                                    <option value="{{$category->id}}">{{ $category->code }}-{{ $category->name}}</option>
                                @empty
                                    <option disabled>Not Available</option>
                                @endforelse
                            </flux:select>
                        </div>

                        <flux:textarea label="Description" placeholder="Product description..." rows="4" class="resize-none" />
                    </div>
                </div>
            </div>

            {{-- Right Column: Pricing & Media --}}
            <div class="space-y-6">
                {{-- Pricing & Stock --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <flux:heading size="lg" class="mb-6 border-b border-slate-100 dark:border-slate-700 pb-4">Pricing & Stock</flux:heading>
                    
                    <div class="space-y-6">
                        <flux:input wire:model="price" type="number" step="0.01" min="0" label="Retail Price" placeholder="0.00" icon="currency-dollar" required />
                        
                        <flux:input wire:model="alert_quantity" type="number" min="0" label="Low Stock Alert" placeholder="10" description="Notify when stock reaches this level" required />
                    </div>
                </div>

                {{-- Image Upload --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <flux:heading size="lg" class="mb-6 border-b border-slate-100 dark:border-slate-700 pb-4">Product Image</flux:heading>
                    
                    <div class="mt-2 flex justify-center rounded-xl border-2 border-dashed border-slate-300 dark:border-slate-600 px-6 py-10 hover:border-primary-500 transition-colors bg-slate-50 dark:bg-slate-900/50">
                        <div class="text-center">
                            @if ($product_image)
                                <img src="{{ $product_image->temporaryUrl() }}" class="mx-auto h-32 w-32 object-cover rounded-lg mb-4" />
                            @else
                                <svg class="mx-auto h-12 w-12 text-slate-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                                </svg>
                            @endif
                            <div class="mt-4 flex text-sm leading-6 text-slate-600 dark:text-slate-400 justify-center">
                                <label for="file-upload" class="relative cursor-pointer rounded-md bg-white dark:bg-slate-800 font-semibold text-primary-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-primary-600 focus-within:ring-offset-2 hover:text-primary-500">
                                    <span>Upload a file</span>
                                    <input id="file-upload" wire:model="product_image" type="file" class="sr-only" accept="image/*">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs leading-5 text-slate-500">PNG, JPG, GIF up to 2MB</p>
                        </div>
                    </div>
                    @error('product_image') <span class="text-sm text-red-500 mt-2 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
    </div>
    
    <!-- Confirmation Modal -->
    <div x-data="{ showConfirm: false }" x-show="showConfirm" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center z-50 transition-opacity" style="display: none;">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl transform transition-all">
            <div class="text-center mb-6">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-primary-100 dark:bg-primary-900/30 mb-4">
                    <svg class="h-6 w-6 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Confirm Product Creation</h3>
                <p class="text-sm text-slate-500 mt-2">Please verify the product details before creating.</p>
            </div>
            
            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-xl p-4 space-y-3 mb-6 text-sm">
                <div class="flex justify-between"><span class="text-slate-500">Barcode:</span> <span class="font-medium text-slate-900 dark:text-white">{{ $barcode }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Name:</span> <span class="font-medium text-slate-900 dark:text-white">{{ $name }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Category:</span> <span class="font-medium text-slate-900 dark:text-white">{{ $categories->where('id', $category_id)->first()->name ?? 'N/A' }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Price:</span> <span class="font-medium text-slate-900 dark:text-white">Rs. {{ number_format((float)$price, 2) }}</span></div>
            </div>

            <div class="flex gap-3 justify-end">
                <flux:button variant="ghost" @click="showConfirm = false" class="w-full justify-center">Cancel</flux:button>
                <flux:button variant="primary" wire:click="confirmSave" @click="showConfirm = false" class="w-full justify-center">Confirm & Create</flux:button>
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

<?php
use Livewire\Volt\Component;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

new class extends Component {

    public $category_code = '';
    public $category_name = '';
    public $editing_id = null;
    public $edit_category_code = '';
    public $edit_category_name = '';
    use WithPagination;

    public $search = '';
    public $perPage = 5;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        try {
            Category::findOrFail($id)->delete();
            $this->dispatch('show-success', message: 'Category deleted successfully!');
        } catch (\Exception $e) {
            $this->dispatch('show-error', message: 'An error occurred while deleting the category.');
        }
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->editing_id = $id;
        $this->edit_category_code = $category->code;
        $this->edit_category_name = $category->name;
    }

    public function update()
    {
        $this->validate([
            'edit_category_code' => 'required|string|max:255',
            'edit_category_name' => 'required|string|min:3|max:255',
        ]);

        try {
            $category = Category::findOrFail($this->editing_id);
            
            // Check for duplicate category code (excluding current category)
            if (Category::where('code', $this->edit_category_code)->where('id', '!=', $this->editing_id)->exists()) {
                $this->dispatch('show-error', message: 'Category code already exists. Please use a different code.');
                return;
            }

            // Check for duplicate category name (excluding current category)
            if (Category::where('name', $this->edit_category_name)->where('id', '!=', $this->editing_id)->exists()) {
                $this->dispatch('show-error', message: 'Category name already exists. Please use a different name.');
                return;
            }

            $category->update([
                'code' => $this->edit_category_code,
                'name' => $this->edit_category_name,
            ]);

            $this->cancelEdit();
            $this->dispatch('show-success', message: 'Category updated successfully!');

        } catch (\Exception $e) {
            $this->dispatch('show-error', message: 'An error occurred while updating the category.');
        }
    }

    public function cancelEdit()
    {
        $this->editing_id = null;
        $this->edit_category_code = '';
        $this->edit_category_name = '';
    }

    public function with()
    {
        return [
            'categories' => Category::query()
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('code', 'like', "%{$this->search}%")
                ->latest()
                ->paginate($this->perPage)
        ];
    }

    

    public function save()
    {        
        $this->validate([
            'category_code' => 'required|string|max:255',
            'category_name' => 'required|string|min:3|max:255',
        ]);

        try {
            // Check for duplicate category code
            if (Category::where('code', $this->category_code)->exists()) {
                $this->dispatch('show-error', message: 'Category code already exists. Please use a different code.');
                return;
            }

            // Check for duplicate category name
            if (Category::where('name', $this->category_name)->exists()) {
                $this->dispatch('show-error', message: 'Category name already exists. Please use a different name.');
                return;
            }

            Category::create([
                'code' => $this->category_code,
                'name' => $this->category_name,
                'is_active' => true,
                'created_by' => Auth::id(),
            ]);

            $this->reset();
            $this->dispatch('show-success', message: 'Category created successfully!');

        } catch (\Exception $e) {
            $this->dispatch('show-error', message: 'An error occurred while creating the category. Please try again.');
        }
    }

    public function cancel()
    {
        $this->reset();
    }

    
}; ?>

<div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-8 px-4">
    <div class="max-w-7xl mx-auto">
        {{-- Page Header --}}
        <div class="mb-8">
            <flux:heading size="xl">Category Management</flux:heading>
            <p class="text-gray-600 mt-1">Create and manage your product categories</p>
        </div>

        {{-- Main Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            {{-- Left Side - Create Category --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-6">
                <flux:heading size="lg" class="mb-4">Add New Category</flux:heading>

                <form wire:submit="save" class="space-y-6">

                    {{-- Category Code --}}
                    <flux:input 
                        wire:model="category_code" 
                        label="Category Code" 
                        placeholder="Enter Category Code" 
                        description="Category Name/Code - must be unique" 
                        required 
                    />

                    {{-- Category Name --}}
                    <flux:input 
                        wire:model="category_name" 
                        label="Name" 
                        placeholder="Enter Category Name" 
                        required 
                    />

                    {{-- Action Buttons --}}
                    <div class="flex gap-3 justify-end">
                        <flux:button type="button" variant="ghost" wire:click="cancel">
                            Cancel
                        </flux:button>

                        <flux:button type="submit" variant="primary">
                            Add
                        </flux:button>
                    </div>
                </form>
            </div>

            {{-- Right Side - Category List --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-4">
                <div class="flex justify-between items-center mb-4">
                    <flux:input 
                        wire:model.live="search" 
                        placeholder="Search categories..." 
                        class="w-64"
                    />
                </div>
                
                {{-- Data Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="border-b text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 font-medium">Code</th>
                                <th class="px-4 py-3 font-medium">Name</th>
                                <th class="px-4 py-3 font-medium">Status</th>
                                <th class="px-4 py-3 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                            @forelse ($categories as $category)                               
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                @if($editing_id === $category->id)
                                    {{-- Edit Mode --}}
                                    <td class="px-4 py-3">
                                        <flux:input 
                                            wire:model="edit_category_code" 
                                            size="sm"
                                            class="w-full"
                                        />
                                    </td>
                                    <td class="px-4 py-3">
                                        <flux:input 
                                            wire:model="edit_category_name" 
                                            size="sm"
                                            class="w-full"
                                        />
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right space-x-2">
                                        <flux:button size="sm" variant="primary" wire:click="update">
                                            Save
                                        </flux:button>
                                        <flux:button size="sm" variant="ghost" wire:click="cancelEdit">
                                            Cancel
                                        </flux:button>
                                    </td>
                                @else
                                    {{-- View Mode --}}
                                    <td class="px-4 py-3 font-medium">{{$category->code}}</td>
                                    <td class="px-4 py-3">{{$category->name}}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right space-x-2">
                                        <flux:button size="sm" variant="primary" wire:click="edit({{ $category->id }})">
                                            Edit
                                        </flux:button>
                                        <flux:button 
                                            size="sm" 
                                            variant="danger" 
                                            wire:click="delete({{ $category->id }})"
                                            wire:confirm="Are you sure you want to delete this category?"
                                        >
                                            Delete
                                        </flux:button>
                                    </td>
                                @endif
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            No categories found
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($categories->hasPages())
                    <div class="mt-4">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>


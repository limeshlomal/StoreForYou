<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $file;

}; ?>

<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        
        {{-- Success/Error Messages --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg flex items-center gap-3 text-green-700 dark:text-green-400">
                <flux:icon name="check-circle" class="w-6 h-6" />
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg flex items-center gap-3 text-red-700 dark:text-red-400">
                <flux:icon name="x-circle" class="w-6 h-6" />
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Page Header --}}
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <flux:heading size="xl" class="text-slate-900 dark:text-white font-bold">Bulk Product Upload</flux:heading>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Efficiently add multiple products using an Excel template</p>
            </div>
            
            <div class="flex gap-3">
                <flux:button variant="ghost" href="{{ route('products.index') }}" wire:navigate class="text-slate-600">Back to List</flux:button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column: Actions (Download & Upload) --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Step 1: Upload File --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="p-3 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl">
                            <flux:icon name="cloud-arrow-up" class="w-6 h-6 text-indigo-600 dark:text-indigo-400" />
                        </div>
                        <div>
                            <flux:heading size="lg" class="mb-1">1. Upload Filled Template</flux:heading>
                            <p class="text-slate-500 dark:text-slate-400 text-sm">
                                Drag and drop your filled Excel file here, or click to browse.
                            </p>
                        </div>
                    </div>

                    <div 
                        class="mt-4 flex justify-center rounded-xl border-2 border-dashed border-slate-300 dark:border-slate-600 px-6 py-10 hover:border-primary-500 transition-colors bg-slate-50 dark:bg-slate-900/50"
                        x-data="{ dragging: false }"
                        :class="{ 'border-primary-500 bg-primary-50 dark:bg-primary-900/20': dragging }"
                        @dragover.prevent="dragging = true"
                        @dragleave.prevent="dragging = false"
                        @drop.prevent="dragging = false"
                    >
                        <div class="text-center w-full">
                            <div class="mx-auto h-12 w-12 text-slate-300 mb-4">
                                <flux:icon name="document-plus" variant="outline" class="w-12 h-12" />
                            </div>
                            
                            <div class="mt-4 flex text-sm leading-6 text-slate-600 dark:text-slate-400 justify-center">
                                <label for="file-upload" class="relative cursor-pointer rounded-md font-semibold text-primary-600 focus-within:outline-none hover:text-primary-500">
                                    <span>Upload a file</span>
                                    <input id="file-upload" wire:model="file" type="file" class="sr-only" accept=".xlsx,.xls,.csv">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs leading-5 text-slate-500 mt-2">Excel (XLSX, XLS) or CSV up to 10MB</p>

                            @if ($file)
                                <div class="mt-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg inline-flex items-center gap-2 text-green-700 dark:text-green-400 animate-in fade-in slide-in-from-bottom-2">
                                    <flux:icon name="check-circle" class="w-5 h-5" />
                                    <span class="text-sm font-medium">{{ $file->getClientOriginalName() }}</span>
                                    <button wire:click="$set('file', null)" class="text-green-600 hover:text-green-800 ml-2">
                                        <flux:icon name="x-mark" class="w-4 h-4" />
                                    </button>
                                </div>
                            @endif

                            @error('file') 
                                <span class="text-sm text-red-500 mt-2 block font-medium">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <flux:button 
                            variant="primary" 
                            wire:click="importProducts" 
                            wire:loading.attr="disabled"
                            :disabled="!$file"
                            class="w-full sm:w-auto"
                        >
                            <span wire:loading.remove wire:target="importProducts">
                                <flux:icon name="arrow-up-tray" class="w-4 h-4 mr-2" />
                                Upload Products
                            </span>
                            <span wire:loading wire:target="importProducts" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </flux:button>
                    </div>
                </div>
            </div>

            {{-- Right Column: Instructions --}}
            <div class="space-y-6">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <flux:heading size="lg" class="mb-4 border-b border-slate-100 dark:border-slate-700 pb-3">Important Guidelines</flux:heading>
                    
                    <div class="space-y-4">
                        <div class="flex gap-3 text-sm text-slate-600 dark:text-slate-300">
                            <div class="shrink-0 mt-0.5 w-5 h-5 rounded-full bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-xs">!</div>
                            <p>Do not rearrange or rename the columns in the template file.</p>
                        </div>
                        
                        <div class="flex gap-3 text-sm text-slate-600 dark:text-slate-300">
                            <div class="shrink-0 mt-0.5 w-5 h-5 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 flex items-center justify-center font-bold text-xs">1</div>
                            <p><span class="font-medium text-slate-900 dark:text-white">Barcode:</span> Must be unique for each product. Leave empty to auto-generate.</p>
                        </div>

                        <div class="flex gap-3 text-sm text-slate-600 dark:text-slate-300">
                            <div class="shrink-0 mt-0.5 w-5 h-5 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 flex items-center justify-center font-bold text-xs">2</div>
                            <p><span class="font-medium text-slate-900 dark:text-white">Category:</span> Use the precise Category Name or ID. If not found, product may be skipped.</p>
                        </div>

                        <div class="flex gap-3 text-sm text-slate-600 dark:text-slate-300">
                            <div class="shrink-0 mt-0.5 w-5 h-5 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 flex items-center justify-center font-bold text-xs">3</div>
                            <p><span class="font-medium text-slate-900 dark:text-white">Price & Quantity:</span> Must be numeric values. Do not include currency symbols.</p>
                        </div>

                        <div class="flex gap-3 text-sm text-slate-600 dark:text-slate-300">
                            <div class="shrink-0 mt-0.5 w-5 h-5 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 flex items-center justify-center font-bold text-xs">4</div>
                            <p><span class="font-medium text-slate-900 dark:text-white">Images:</span> Image URLs can be included, or upload manually later.</p>
                        </div>
                    </div>                 
                </div>
            </div>
        </div>
    </div>
</div>
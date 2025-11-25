<?php

use Livewire\Volt\Component;
use App\Models\Product;
use Picqer\Barcode\BarcodeGeneratorSVG;

new class extends Component {
    
    public $products;

    public function mount()
    {
       $ids = request()->query('ids');
       $idsArray = array_filter(explode(',', $ids)); // remove empty strings
       $this->products = Product::whereIn('id', $idsArray)->get();
       
    }

    public function render():mixed
    {
        $this->generator = new BarcodeGeneratorSVG();

        return view('livewire.barcodes.print', [
            'products' => $this->products,
            'generator' => $this->generator
        ]);
    }

}; ?>

<div class="min-h-screen bg-slate-50 dark:bg-slate-900 p-8 print:p-0 print:bg-white">
    
    <!-- No-Print Header with Controls -->
    <div class="max-w-5xl mx-auto mb-8 print:hidden flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <flux:heading size="xl" class="text-slate-900 dark:text-white font-bold">Print Barcodes</flux:heading>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Preview and print product labels.</p>
        </div>
        <div class="flex gap-3">
            <flux:button variant="ghost" onclick="window.history.back()" class="text-slate-600 dark:text-slate-300">
                Back
            </flux:button>
            <flux:button variant="primary" onclick="window.print()" class="shadow-lg shadow-primary-500/20 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                </svg>
                Print Labels
            </flux:button>
        </div>
    </div>

    <!-- Printable Area -->
    <div class="max-w-5xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-slate-200 print:shadow-none print:border-0 print:p-0 print:max-w-none">
        <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 print:grid-cols-5 print:gap-2">
            @foreach($products as $product)
                <div class="flex flex-col items-center justify-center p-4 border border-slate-100 rounded-lg print:border-0 print:p-2 break-inside-avoid">
                    <!-- Barcode SVG -->
                    <div class="mb-2">
                        {!! $generator->getBarcode($product->barcode, $generator::TYPE_CODE_128, 1.5, 40) !!}
                    </div>

                    <!-- Product Image -->
                    @if(!empty($product->product_image))
                        <img src="{{ asset($product->product_image) }}" class="h-12 w-12 object-cover rounded mb-1 print:grayscale">
                    @endif

                    <!-- Product Name & Code -->
                    <div class="text-center">
                        <div class="text-xs font-bold text-slate-900">{{ Str::limit($product->name, 20) }}</div>
                        <div class="text-[10px] text-slate-500 font-mono">{{ $product->barcode }}</div>
                        <div class="text-xs font-bold text-slate-900 mt-1">Rs. {{ number_format($product->retail_price, 2) }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    @media print {
        @page {
            margin: 0.5cm;
        }
        body {
            background: white !important;
            -webkit-print-color-adjust: exact;
        }
    }
</style>


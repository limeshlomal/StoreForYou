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

<table class="barcode-table">
@foreach($products->chunk(5) as $row)
<tr>
    @foreach($row as $product)
    <td style="text-align:center; padding:10px;">
        <!-- Generate SVG barcode -->
        {!! $generator->getBarcode($product->barcode, $generator::TYPE_CODE_128) !!}

        <!-- Product image if exists -->
        @if(!empty($product->product_image))
            <br>
            <img src="{{ asset($product->product_image) }}" width="60">
        @endif

        <!-- Product name -->
        <div>{{ $product->barcode }}</div>
    </td>
    @endforeach
</tr>
@endforeach
</table>


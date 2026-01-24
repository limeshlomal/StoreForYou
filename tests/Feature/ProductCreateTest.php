<?php

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;

use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(Tests\TestCase::class, DatabaseTransactions::class);

test('can create product without image', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $category = Category::create(['name' => 'Test Cat', 'slug' => 'test-cat', 'is_active' => true]); 

    Volt::test('products.create')
        ->set('barcode', 'NOIMG123')
        ->set('name', 'No Image Product')
        ->set('category_id', $category->id)
        ->set('price', 100)
        ->set('alert_quantity', 5)
        ->call('save') // Validates first
        ->assertHasNoErrors()
        ->call('confirmSave'); // Actually saves

    $this->assertDatabaseHas('products', [
        'barcode' => 'NOIMG123',
        'name' => 'No Image Product',
        'product_image' => null,
    ]);
});

test('can create product with image', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    Storage::fake('public');

    $category = Category::create(['name' => 'Img Cat', 'slug' => 'img-cat', 'is_active' => true]); 
    $file = UploadedFile::fake()->image('product.jpg');

    Volt::test('products.create')
        ->set('barcode', 'IMG123')
        ->set('name', 'Image Product')
        ->set('category_id', $category->id)
        ->set('price', 200)
        ->set('alert_quantity', 10)
        ->set('product_image', $file)
        ->call('save')
        ->assertHasNoErrors()
        ->call('confirmSave');

    $this->assertDatabaseHas('products', [
        'barcode' => 'IMG123',
        'name' => 'Image Product',
    ]);

    $product = Product::where('barcode', 'IMG123')->first();
    $this->assertNotNull($product->product_image);
    Storage::disk('public')->assertExists($product->product_image);
});

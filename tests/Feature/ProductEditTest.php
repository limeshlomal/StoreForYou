<?php

use App\Models\Product;
use App\Models\Inventory;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('can view product edit page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $category = Category::create(['name' => 'Test Cat', 'slug' => 'test-cat', 'is_active' => true]); 
    $product = Product::create([
        'barcode' => 'CODE123',
        'name' => 'Edit Me',
        'category_id' => $category->id,
        'retail_price' => 100,
        'alert_quantity' => 10,
        'created_by' => $user->id,
    ]);

    $response = $this->get(route('products.edit', $product));
    $response->assertStatus(200);
    $response->assertSee('Edit Product');
    $response->assertSee('Edit Me');
});

test('can adjust stock via edit page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $category = Category::create(['name' => 'Stock Cat', 'slug' => 'stock-cat', 'is_active' => true]); 
    $product = Product::create([
        'barcode' => 'STOCK123',
        'name' => 'Stock Item',
        'category_id' => $category->id,
        'retail_price' => 50,
        'alert_quantity' => 5,
        'created_by' => $user->id,
    ]);

    // Initial stock 0
    expect($product->inventories()->sum('quantity'))->toBe(0);

    // Add stock
    Volt::test('products.edit', ['product' => $product])
        ->set('adjust_type', 'add')
        ->set('adjust_quantity', 10)
        ->call('adjustStock')
        ->assertHasNoErrors();

    expect($product->inventories()->sum('quantity'))->toBe(10);

    // Remove stock
    Volt::test('products.edit', ['product' => $product])
        ->set('adjust_type', 'remove')
        ->set('adjust_quantity', 3)
        ->call('adjustStock')
        ->assertHasNoErrors();

    expect($product->inventories()->sum('quantity'))->toBe(7);
});

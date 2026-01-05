<?php

use App\Models\Product;
use App\Models\Inventory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('product can return total stock', function () {
    Schema::disableForeignKeyConstraints();

    $user = User::factory()->create();
    
    // Create product
    $product = Product::forceCreate([
       'barcode' => '123456',
       'name' => 'Test Product',
       'category_id' => 1, // Dummy
       'alert_quantity' => 5,
       'retail_price' => 100,
       'is_active' => true,
       'created_by' => $user->id,
       'updated_by' => $user->id,
    ]);

    // Create inventory
    Inventory::forceCreate([
        'product_id' => $product->id,
        'quantity' => 10,
        'cost_price' => 50,
        'created_by' => $user->id,
        'updated_by' => $user->id,
        'purchasing_id' => 1, // Dummy
    ]);

    Inventory::forceCreate([
        'product_id' => $product->id,
        'quantity' => 5,
        'cost_price' => 50,
        'created_by' => $user->id,
        'updated_by' => $user->id,
        'purchasing_id' => 2, // Dummy
    ]);

    
    $stock = $product->get_total_stock();

    expect($stock)->toBe(15);
    // Cast to int if needed, but toBe handles equality.
    // However, sum() might return string or float. 'toBe' is strict? No 'toBe' is ===/== depending, 'toEqual' is ==.
    // Inventory::sum returns user numeric value, probably string/int/float.
    // Let's use toBe(15) or assertEquals(15, $stock).
    
    Schema::enableForeignKeyConstraints();
});

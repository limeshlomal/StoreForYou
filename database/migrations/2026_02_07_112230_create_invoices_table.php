<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('invoices', function (Blueprint $table) {
        $table->id();
        $table->string('invoice_number')->unique();
        $table->decimal('subtotal', 10, 2)->default(0);
        $table->decimal('discount', 10, 2)->default(0);
        $table->decimal('total', 10, 2)->default(0);
        $table->decimal('payment_amount', 10, 2)->default(0);
        $table->decimal('change_amount', 10, 2)->default(0);

        $table->enum('status', [
            'pending', 'completed', 'cancelled',
            'returned', 'partial', 'partial_returned', 'hold'
        ])->default('pending');

        $table->foreignId('user_id')->nullable()
              ->constrained()->nullOnDelete();

        $table->timestamps();
        $table->softDeletes();
    });

    Schema::create('invoice_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
        $table->foreignId('product_id')->constrained()->cascadeOnDelete();
        $table->unsignedInteger('quantity');
        $table->decimal('price', 10, 2);
        $table->decimal('discount', 10, 2)->default(0);
        $table->decimal('total', 10, 2);
        $table->timestamps();
    });

    Schema::create('invoice_payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
        $table->decimal('amount', 10, 2);
        $table->enum('payment_method', ['cash', 'card', 'cheque']);
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('invoice_payments');
    Schema::dropIfExists('invoice_items');
    Schema::dropIfExists('invoices');
}



};

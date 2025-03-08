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
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_color_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('product_size_id')->nullable()->constrained()->onDelete('cascade');
            
            // Stok miktarı
            $table->integer('quantity')->default(0);
            
            // Ürün kodu (SKU) - Renk ve boyuta göre özel
            $table->string('sku')->nullable();
            
            // Fiyat (renk ve boyuta özel fiyat olabilir)
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('discount_price', 10, 2)->nullable();
            
            // Stok durumu
            $table->boolean('status')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stocks');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_color_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('product_size_id')->nullable()->constrained()->onDelete('cascade');
            
           
            $table->integer('quantity')->default(0);
            
          
            $table->string('sku')->nullable();
            
            
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('discount_price', 10, 2)->nullable();
            
         
            $table->boolean('status')->default(true);
            
            $table->timestamps();
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('product_stocks');
    }
};

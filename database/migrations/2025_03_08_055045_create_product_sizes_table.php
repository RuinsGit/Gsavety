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
        Schema::create('product_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Boyut adı (çok dilli)
            $table->string('size_name_az')->nullable();
            $table->string('size_name_en')->nullable();
            $table->string('size_name_ru')->nullable();
            
            // Boyut değeri (Medium, Large, vb.)
            $table->string('size_value')->nullable();
            
            // Boyut durumu
            $table->boolean('status')->default(true);
            
            // Sıralama
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_sizes');
    }
};

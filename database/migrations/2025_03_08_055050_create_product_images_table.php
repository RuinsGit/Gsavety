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
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_color_id')->nullable()->constrained()->onDelete('set null');
            
            // Resim yolu
            $table->string('image_path');
            
            // Resim alt metni (çok dilli)
            $table->string('alt_text_az')->nullable();
            $table->string('alt_text_en')->nullable();
            $table->string('alt_text_ru')->nullable();
            
            // Ana resim mi?
            $table->boolean('is_main')->default(false);
            
            // Resim durumu
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
        Schema::dropIfExists('product_images');
    }
};

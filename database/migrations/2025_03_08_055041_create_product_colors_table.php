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
        Schema::create('product_colors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Renk adı (çok dilli)
            $table->string('color_name_az')->nullable();
            $table->string('color_name_en')->nullable();
            $table->string('color_name_ru')->nullable();
            
            // Renk kodu (HEX)
            $table->string('color_code')->nullable();
            
            // Renk görseli
            $table->string('color_image')->nullable();
            
            // Renk durumu
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
        Schema::dropIfExists('product_colors');
    }
};

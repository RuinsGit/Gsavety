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
        Schema::create('product_banners', function (Blueprint $table) {
            $table->id();
            
            // Başlıklar (çoklu dil)
            $table->string('title_az')->nullable();
            $table->string('title_en')->nullable();
            $table->string('title_ru')->nullable();
            
            // Resim
            $table->string('image')->nullable();
            
            // Durum
            $table->boolean('status')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_banners');
    }
};

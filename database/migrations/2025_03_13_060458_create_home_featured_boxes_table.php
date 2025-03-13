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
        Schema::create('home_featured_boxes', function (Blueprint $table) {
            $table->id();
            
            // Çokdilli başlık alanları
            $table->string('title_az')->nullable();
            $table->string('title_en')->nullable();
            $table->string('title_ru')->nullable();
            
            // Resim alanı
            $table->string('image')->nullable();
            
            // Durum ve sıralama
            $table->boolean('status')->default(true);
            $table->integer('order')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_featured_boxes');
    }
};

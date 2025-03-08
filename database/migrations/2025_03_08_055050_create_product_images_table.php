<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_color_id')->nullable()->constrained()->onDelete('set null');
            
            
            $table->string('image_path');
            
           
            $table->string('alt_text_az')->nullable();
            $table->string('alt_text_en')->nullable();
            $table->string('alt_text_ru')->nullable();
            
          
            $table->boolean('is_main')->default(false);
            
      
            $table->boolean('status')->default(true);
            
  
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

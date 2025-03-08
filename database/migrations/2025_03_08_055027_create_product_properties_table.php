<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('product_properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            
            $table->string('property_name_az')->nullable();
            $table->string('property_name_en')->nullable();
            $table->string('property_name_ru')->nullable();
            
           
            $table->text('property_value_az')->nullable();
            $table->text('property_value_en')->nullable();
            $table->text('property_value_ru')->nullable();
            
          
            $table->string('property_type')->nullable();
            
            
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('product_properties');
    }
};

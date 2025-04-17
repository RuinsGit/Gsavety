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
        Schema::create('about_text_sections', function (Blueprint $table) {
            $table->id();
            $table->string('title1_az')->nullable();
            $table->string('title1_en')->nullable();
            $table->string('title1_ru')->nullable();
            $table->text('description1_az')->nullable();
            $table->text('description1_en')->nullable();
            $table->text('description1_ru')->nullable();
            
            $table->string('title2_az')->nullable();
            $table->string('title2_en')->nullable();
            $table->string('title2_ru')->nullable();
            $table->text('description2_az')->nullable();
            $table->text('description2_en')->nullable();
            $table->text('description2_ru')->nullable();
            
            $table->string('title3_az')->nullable();
            $table->string('title3_en')->nullable();
            $table->string('title3_ru')->nullable();
            $table->text('description3_az')->nullable();
            $table->text('description3_en')->nullable();
            $table->text('description3_ru')->nullable();
            
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_text_sections');
    }
}; 
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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            
            // Üst kategori ilişkisi (self-referencing)
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            
            // Kategori durumu
            $table->boolean('status')->default(true);
            $table->integer('order')->default(0);
            
            // Çok dilli kategori adları
            $table->string('name_az');
            $table->string('name_en')->nullable();
            $table->string('name_ru')->nullable();
            
            // Çok dilli açıklamalar
            $table->text('description_az')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_ru')->nullable();
            
            // SEO alanları
            $table->string('slug_az')->unique();
            $table->string('slug_en')->nullable()->unique();
            $table->string('slug_ru')->nullable()->unique();
            
            // Kategori görseli
            $table->string('image')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};

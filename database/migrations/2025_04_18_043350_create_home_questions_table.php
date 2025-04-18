<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('home_questions', function (Blueprint $table) {
            $table->id();
            
            $table->string('title_az');
            $table->string('title_en');
            $table->string('title_ru');
            
            $table->text('description_az');
            $table->text('description_en');
            $table->text('description_ru');
            
            $table->boolean('status')->default(true);
            $table->integer('order')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('home_questions');
    }
}; 
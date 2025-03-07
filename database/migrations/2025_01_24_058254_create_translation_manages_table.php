<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('translation_manages', function (Blueprint $table) {
        $table->id();
        $table->string('key')->unique(); 
        $table->text('value_az');
        $table->text('value_en')->nullable();
        $table->text('value_ru')->nullable();
        $table->string('group')->default('general');
        $table->boolean('status')->default(1);
        $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('translation_manages');
    }
};
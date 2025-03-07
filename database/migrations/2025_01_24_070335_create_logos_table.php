<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('logos', function (Blueprint $table) {
            $table->id();
            $table->string('logo_1_image'); 
            $table->string('logo_2_image'); 
            $table->text('logo_alt1_az'); 
            $table->text('logo_alt1_en'); 
            $table->text('logo_alt1_ru'); 
            $table->text('logo_alt2_az'); 
            $table->text('logo_alt2_en'); 
            $table->text('logo_alt2_ru'); 
            $table->string('logo_title1_az'); 
            $table->string('logo_title1_en');
            $table->string('logo_title1_ru');
            $table->string('logo_title2_az');
            $table->string('logo_title2_en');
            $table->string('logo_title2_ru');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('logos');
    }
};
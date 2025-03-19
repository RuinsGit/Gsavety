<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();

            $table->string('number'); 
            $table->string('number_image')->nullable(); 
            $table->string('mail'); 
            $table->string('mail_image')->nullable(); 
            $table->string('address_az'); 
            $table->string('address_en'); 
            $table->string('address_ru'); 
            $table->string('address_image')->nullable(); 
            $table->text('filial_description')->nullable(); 

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}

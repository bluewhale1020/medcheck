<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceptionListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reception_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',45);
            $table->dateTime('import_date');
            $table->dateTime('expiration_date')->nullable();
            $table->string('main_course',45)->nullable();
            $table->boolean('main_kenpo')->nullable();
            $table->integer('first_serial_number')->nullable();
            $table->integer('last_serial_number')->nullable();
            $table->integer('max_serial_number')->nullable();            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reception_lists');
    }
}

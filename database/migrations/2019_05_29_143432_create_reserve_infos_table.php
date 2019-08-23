<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReserveInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reserve_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('reception_list_id');
            $table->integer('checkup_info_id')->nullable();
            $table->integer('account_id');
            $table->integer('serial_number')->nullable();
            $table->dateTime('schedule_date');
            $table->dateTime('checkup_date')->nullable();
            $table->string('course',25)->nullable();
            $table->boolean('kenpo')->default(false);
            $table->string('notes')->nullable();
            $table->boolean('check_in')->default(false);
            $table->boolean('complete')->default(false);           
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
        Schema::dropIfExists('reserve_infos');
    }
}

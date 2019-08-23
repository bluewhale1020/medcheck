<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSelectItemInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('select_item_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',45);
            $table->string('name_jp',45);
            $table->string('options',45)->nullable();
            $table->boolean('check_only')->default(0);
            $table->string('grouping',25)->nullable();            
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
        Schema::dropIfExists('select_item_infos');
    }
}

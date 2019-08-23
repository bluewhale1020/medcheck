<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResultInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',45);
            $table->string('name_jp',45);
            $table->string('select_item_category',45);
            $table->integer('select_item_info_id');
            $table->string('unit',25)->nullable();
            $table->smallInteger('num_decimal_places')->nullable();
            $table->string('options',45)->nullable();
            $table->smallInteger('min_val')->nullable();
            $table->smallInteger('max_val')->nullable();
            $table->string('m_lower_limit',10)->nullable();
            $table->string('m_upper_limit',10)->nullable();
            $table->string('fm_lower_limit',10)->nullable();
            $table->string('fm_upper_limit',10)->nullable();            
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
        Schema::dropIfExists('result_infos');
    }
}

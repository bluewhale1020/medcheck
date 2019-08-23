<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSelectItemInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('select_item_infos', function (Blueprint $table) {
            $table->smallInteger('select_item_order')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('select_item_infos', function (Blueprint $table) {
            $table->dropColumn('select_item_order');  //カラムの削除
        });
    }
}

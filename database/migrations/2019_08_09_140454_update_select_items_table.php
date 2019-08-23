<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSelectItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('select_items', function (Blueprint $table) {
            $table->smallInteger('delta-aminolevulinic_acid')->default(0);
        });        

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('select_items', function (Blueprint $table) {
            $table->dropColumn('delta-aminolevulinic_acid');  //カラムの削除
        });        

    }
}

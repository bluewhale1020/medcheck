<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSelectItemInfosTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('select_item_infos', function (Blueprint $table) {
            $table->boolean('use_both')->default(0);            
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
            $table->dropColumn('use_both');  //カラムの削除
        });
    }
}

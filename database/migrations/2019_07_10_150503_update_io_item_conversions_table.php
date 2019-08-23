<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateIoItemConversionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('io_item_conversions', function (Blueprint $table) {
            $table->smallInteger('list_order')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('io_item_conversions', function (Blueprint $table) {
            $table->dropColumn('list_order');  //カラムの削除
        });
    }
}

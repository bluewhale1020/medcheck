<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSelectItemsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('select_items', function (Blueprint $table) {
            $table->string('urinary_test_type',40)->nullable()->change();
            $table->string('blood_test_type',40)->nullable()->change();
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
            $table->smallInteger('urinary_test_type')->default(0)->change();
            $table->smallInteger('blood_test_type')->default(0)->change();
        });
    }
}

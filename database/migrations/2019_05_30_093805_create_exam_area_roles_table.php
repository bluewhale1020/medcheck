<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamAreaRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_area_role', function (Blueprint $table) {
            $table->unsignedInteger('exam_area_id')->index();
            $table->unsignedInteger('role_id')->index();

            //外部キー制約
            $table->foreign('exam_area_id')
                ->references('id')
                ->on('exam_areas')
                ->onDelete('cascade');
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

        });

         
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exam_area_role');
    }
}

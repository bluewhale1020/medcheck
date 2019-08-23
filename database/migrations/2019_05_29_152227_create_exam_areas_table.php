<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_areas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',25);
            $table->integer('exam_category_id');
            $table->boolean('height')->nullable()->default(false);
            $table->boolean('weight')->nullable()->default(false);
            $table->boolean('bodyfat_ratio')->nullable()->default(false);
            $table->boolean('abdominal_circumference')->nullable()->default(false);
            $table->boolean('vision_test')->nullable()->default(false);
            $table->boolean('hearing_test')->nullable()->default(false);
            $table->boolean('hearing_test_conv')->nullable()->default(false);
            $table->boolean('physical_examination')->nullable()->default(false);
            $table->boolean('blood_pressure')->nullable()->default(false);
            $table->boolean('urinary_test')->nullable()->default(false);
            $table->boolean('urinary_sediment')->nullable()->default(false);
            $table->boolean('blood_test')->nullable()->default(false);
            $table->boolean('fecaloccult_blood')->nullable()->default(false);
            $table->boolean('electrogram_test')->nullable()->default(false);
            $table->boolean('chest_xray')->nullable()->default(false);
            $table->boolean('stomach_xray')->nullable()->default(false);
            $table->boolean('eye_pressure')->nullable()->default(false);
            $table->boolean('eyeground')->nullable()->default(false);
            $table->boolean('grip_strength')->nullable()->default(false);
            $table->boolean('lung_capacities')->nullable()->default(false);
            $table->boolean('urinary_metabolites')->nullable()->default(false);            
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
        Schema::dropIfExists('exam_areas');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('reserve_info_id');
            $table->string('findings_chestabdomen')->nullable();
            $table->float('height')->nullable();
            $table->float('weight')->nullable();
            $table->float('bodyfat_ratio')->nullable();
            $table->float('abdominal_circumference')->nullable();
            $table->float('r_eyesight', 3, 1)->nullable();
            $table->float('l_eyesight', 3, 1)->nullable();
            $table->float('corrected_r_eyesight', 3, 1)->nullable();
            $table->float('corrected_l_eyesight', 3, 1)->nullable();
            $table->string('r_hearing_1000hz',15)->nullable();
            $table->string('l_hearing_1000hz',15)->nullable();
            $table->string('r_hearing_4000hz',15)->nullable();
            $table->string('l_hearing_4000hz',15)->nullable();
            $table->string('hearing_on_conv',15)->nullable();
            $table->smallInteger('h_blood_pressure')->nullable();
            $table->smallInteger('l_blood_pressure')->nullable();
            $table->string('urinary_protein',5)->nullable();
            $table->string('urinary_sugar',5)->nullable();
            $table->string('urinary_urobilinogen',5)->nullable();
            $table->float('urinary_ph')->nullable();
            $table->string('urinary_blood',5)->nullable();
            $table->float('eye_pressure_r')->nullable();
            $table->float('eye_pressure_l')->nullable();
            $table->float('lung_capacity')->nullable();
            $table->float('lung_fev1_sec')->nullable();
            $table->float('lung_fev1_per')->nullable();
            $table->float('grip_strength_r')->nullable();
            $table->float('grip_strength_l')->nullable();
            $table->boolean('is_hungry')->nullable();
            $table->float('hours_after_meals')->nullable();
            $table->mediumInteger('chest_xray_no')->nullable();
            $table->mediumInteger('stomach_xray_no')->nullable();
            $table->mediumInteger('electro_no')->nullable();
            $table->mediumInteger('eyeground_no')->nullable();            
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
        Schema::dropIfExists('exam_results');
    }
}

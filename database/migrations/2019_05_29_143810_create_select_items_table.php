<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSelectItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('select_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('reserve_info_id');
            $table->smallInteger('height')->default(0);
            $table->smallInteger('weight')->default(0);
            $table->smallInteger('bodyfat_ratio')->default(0);
            $table->smallInteger('abdominal_circumference')->default(0);
            $table->smallInteger('vision_test')->default(0);
            $table->smallInteger('hearing_test')->default(0);
            $table->smallInteger('hearing_test_conv')->default(0);
            $table->smallInteger('physical_examination')->default(0);
            $table->smallInteger('blood_pressure')->default(0);
            $table->smallInteger('urinary_test')->default(0);
            $table->smallInteger('urinary_test_type')->default(0);
            $table->smallInteger('urinary_sediment')->default(0);
            $table->smallInteger('blood_test')->default(0);
            $table->smallInteger('blood_test_type')->default(0);
            $table->smallInteger('fecaloccult_blood')->default(0);
            $table->smallInteger('electrogram_test')->default(0);
            $table->smallInteger('chest_xray')->default(0);
            $table->smallInteger('stomach_xray')->default(0);
            $table->smallInteger('eye_pressure')->default(0);
            $table->smallInteger('eyeground')->default(0);
            $table->smallInteger('grip_strength')->default(0);
            $table->smallInteger('lung_capacities')->default(0);
            $table->smallInteger('urinary_metabolites')->default(0);
            $table->smallInteger('methyl_hippuric_acid')->default(0);
            $table->smallInteger('n-formylmethylamine')->default(0);
            $table->smallInteger('mandelic_acid')->default(0);
            $table->smallInteger('trichloroacetic_acid')->default(0);
            $table->smallInteger('hippuric_acid')->default(0);
            $table->smallInteger('2,5-hexanedione')->default(0);
            $table->smallInteger('formaldehyde')->default(0);
            $table->smallInteger('dust')->default(0);
            $table->smallInteger('lead')->default(0);
            $table->smallInteger('ionizing_radiation')->default(0);
            $table->smallInteger('Indium')->default(0);            
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
        Schema::dropIfExists('select_items');
    }
}

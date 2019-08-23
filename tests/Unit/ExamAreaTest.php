<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\ExamArea;
use DB;

class ExamAreaTest extends TestCase
{
    use DatabaseTransactions;

    public function setup(): void
    {
        parent::setUp();

        DB::table('exam_areas')->insert([
            [
                "name" => "握力",
                "exam_category_id" => 1,
                "height" => 0,
                "weight" => 0,
                "bodyfat_ratio" => 0,
                "abdominal_circumference" => 0,
                "vision_test" => 0,
                "hearing_test" => 0,
                "hearing_test_conv" => 0,
                "physical_examination" => 0,
                "blood_pressure" => 0,
                "urinary_test" => 0,
                "urinary_sediment" => 0,
                "blood_test" => 0,
                "fecaloccult_blood" => 0,
                "electrogram_test" => 0,
                "chest_xray" => 0,
                "stomach_xray" => 0,
                "eye_pressure" => 0,
                "eyeground" => 0,
                "grip_strength" => 1,
                "lung_capacities" => 0,
                "urinary_metabolites" => 0,
              ],
              [
                "name" => "肺機能",
                "exam_category_id" => 1,
                "height" => 0,
                "weight" => 0,
                "bodyfat_ratio" => 0,
                "abdominal_circumference" => 0,
                "vision_test" => 0,
                "hearing_test" => 0,
                "hearing_test_conv" => 0,
                "physical_examination" => 0,
                "blood_pressure" => 0,
                "urinary_test" => 0,
                "urinary_sediment" => 0,
                "blood_test" => 0,
                "fecaloccult_blood" => 0,
                "electrogram_test" => 0,
                "chest_xray" => 1,
                "stomach_xray" => 0,
                "eye_pressure" => 0,
                "eyeground" => 0,
                "grip_strength" => 0,
                "lung_capacities" => 1,
                "urinary_metabolites" => 0,
              ],         
        ]);

    }


    /**
     * testGetAllAreaItems unit test
     *
     * @return void
     */
    public function testGetAllAreaItems(){
        // print_r(ExamArea::all()->toArray());
        $result =  ExamArea::getAllAreaItems();

        $expected = [
        '握力'=>[ 'grip_strength' ],
        '肺機能'=>['chest_xray','lung_capacities' ],
    ];
        // print_r($result);
        $this->assertEquals($expected['握力'],$result['握力']); 
        $this->assertEquals($expected['肺機能'],$result['肺機能']); 

    }


    /**
     * testGetTargetAreaItems unit test
     *
     * @return void
     */
    public function testGetTargetAreaItems()
    {
        $exam_area_id = 4;
        $result =  ExamArea::getTargetAreaItems($exam_area_id);

        $expected = ['hearing_test','hearing_test_conv'];
        print_r($result);
        $this->assertEquals($expected,$result); 

        $exam_area_id = 5;
        $result =  ExamArea::getTargetAreaItems($exam_area_id);

        $expected = ['blood_pressure'];
        print_r($result);
        $this->assertEquals($expected,$result); 

        $exam_area_id = null;
        // print_r($selectItem);
        $result =  ExamArea::getTargetAreaItems($exam_area_id);

        print_r($result);
        $this->assertFalse($result);        

    }


    public function tearDown(): void
    {
        parent::tearDown();
    }    
}

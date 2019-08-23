<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\SelectItem;
use App\ReserveInfo;
use Illuminate\Foundation\Testing\WithFaker;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use DB;

class SelectItemTest extends TestCase
{
    use DatabaseTransactions;
    public $item;
    protected $reception_list_id;    
    protected $select_id;    
 
    public function setup(): void
    {
        parent::setUp();

        $this->reception_list_id = 8;
            
    }

    public function testUpdateSpecialItems(){
        $data = ["physical_examination" => 2,"lung_capacities" => 2,'dust'=>1,'urinary_metabolites'=>2,
        'methyl_hippuric_acid'=>1,'mandelic_acid'=>1,'hippuric_acid'=>0];

        factory(SelectItem::class,1)
        ->create($data)
        ->each(function(SelectItem $select) {
            $this->select_id = $select->id;
        });

        $select_item = SelectItem::find($this->select_id);

        $select_item =  SelectItem::updateSpecialItems($select_item);

        $data['dust'] = 2;
        $data['methyl_hippuric_acid'] = 2;
        $data['mandelic_acid'] = 2;

        $this->assertInstanceOf(SelectItem::class, $select_item);
        
        $this->assertDatabaseHas('select_items',$data);   
        
        $select_item->physical_examination = 1;
        $select_item->urinary_metabolites = 1;
        $select_item =  SelectItem::updateSpecialItems($select_item);
        print_r($select_item->toArray());
        $data['physical_examination'] = 1;
        $data['dust'] = 1;
        $data['urinary_metabolites'] = 1;
        $data['methyl_hippuric_acid'] = 1;
        $data['mandelic_acid'] = 1;

        $this->assertInstanceOf(SelectItem::class, $select_item);
        
        $this->assertDatabaseHas('select_items',$data); 

    }

    public function testCheckItemsComplete(){

        factory(SelectItem::class,1)
        ->create(["physical_examination" => 2,"lung_capacities" => 1,'dust'=>1])
        ->each(function(SelectItem $select) {
            $this->select_id = $select->id;
        });

        $select_item = SelectItem::find($this->select_id);
        $related_items = ["physical_examination","lung_capacities"];

        $result =  SelectItem::checkItemsComplete($select_item,$related_items);

        $this->assertFalse($result);  


        $select_item->lung_capacities = 2;
        $select_item->save();

        $result =  SelectItem::checkItemsComplete($select_item,$related_items);

        $this->assertTrue($result);  
        
        
        $select_item->physical_examination = 0;
        $select_item->save();

        $result =  SelectItem::checkItemsComplete($select_item,$related_items);

        $this->assertTrue($result);           
    }    


    private function insertDataForGetTestTargetCounts(){
        //データの定義とインサート
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


        factory(ReserveInfo::class,3)->create(['check_in'=>true,'complete'=>false,
        'reception_list_id'=>$this->reception_list_id])
        ->each(function(ReserveInfo $reserve) {
            $reserve->select_item()->save(factory(SelectItem::class)
            ->make(["grip_strength" => 1,"lung_capacities" => 0,'chest_xray'=>0,'urinary_metabolites'=>0]));
            });    
        factory(ReserveInfo::class,5)->create(['check_in'=>true,'complete'=>false,
        'reception_list_id'=>$this->reception_list_id])
        ->each(function(ReserveInfo $reserve) {
            $reserve->select_item()->save(factory(SelectItem::class)
            ->make(["grip_strength" => 2,"lung_capacities" => 0,'chest_xray'=>0,'urinary_metabolites'=>0]));
            });    

        factory(ReserveInfo::class,1)->create(['check_in'=>true,'complete'=>false,
        'reception_list_id'=>$this->reception_list_id])
        ->each(function(ReserveInfo $reserve) {
            $reserve->select_item()->save(factory(SelectItem::class)
            ->make(["grip_strength" => 0,"lung_capacities" => 1,'chest_xray'=>2,'urinary_metabolites'=>0]));
            });
        factory(ReserveInfo::class,1)->create(['check_in'=>true,'complete'=>false,
        'reception_list_id'=>$this->reception_list_id])
        ->each(function(ReserveInfo $reserve) {
            $reserve->select_item()->save(factory(SelectItem::class)
            ->make(["grip_strength" => 0,"lung_capacities" => 2,'chest_xray'=>2,'urinary_metabolites'=>0]));
            });
        factory(ReserveInfo::class,1)->create(['check_in'=>true,'complete'=>false,
        'reception_list_id'=>$this->reception_list_id])
        ->each(function(ReserveInfo $reserve) {
            $reserve->select_item()->save(factory(SelectItem::class)
            ->make(["grip_strength" => 0,"lung_capacities" => 1,'chest_xray'=>0,'urinary_metabolites'=>0]));
            });
    }
 

    public function testGetTestTargetCounts(){

        $this->insertDataForGetTestTargetCounts();

        $area_items = ['grip_strength'];
        $area_name = '握力';
        $result =  SelectItem::getTestTargetCounts($area_name, $area_items, $this->reception_list_id);

        $expected = [$area_name.'完了数'=>5, $area_name.'対象者数'=>8];
        // print_r($result);
        $this->assertEquals($expected,$result);  
        
        
        $area_items = ['lung_capacities','chest_xray'];
        $area_name = '肺機能';
        $result =  SelectItem::getTestTargetCounts($area_name, $area_items, $this->reception_list_id);

        $expected = [$area_name.'完了数'=>1, $area_name.'対象者数'=>3];
        // print_r($result);
        $this->assertEquals($expected,$result);         

        $area_items = ['urinary_metabolites'];
        $area_name = '有機';
        $result =  SelectItem::getTestTargetCounts($area_name, $area_items, $this->reception_list_id);

        $expected = [$area_name.'完了数'=>0, $area_name.'対象者数'=>0];
        // print_r($result);
        $this->assertEquals($expected,$result);         

    }

    /**
     * A testScopeCalcProgress test
     *
     * @return void
     */
    public function testcalcProgress()
    {
        $this->item = new SelectItem();
        $selectItem = [
            'vision_test'=>1,
            'urinary_test'=>2,            
            'blood_test'=>2,            
            'weight'=>1,
        ];
        // print_r($selectItem);
        $progress =  $this->item->calcProgress($selectItem);

        $expected = 50;
        // print($progress);
        $this->assertEquals($expected,$progress);

        $selectItem = [
            'vision_test'=>1,
            'urinary_test'=>2,            
            'blood_test'=>1,            
            'weight'=>1,
        ];
        // print_r($selectItem);
        $progress =  $this->item->calcProgress($selectItem);

        $expected = 25;
        // print($progress);
        $this->assertEquals($expected,$progress);

    }    


    /**
     * testGetTestTargetIds unit test
     *
     * @return void
     */
    public function testGetTestTargetIds()
    {
        $this->markTestSkipped(
            'このテストは、テスト済み。'
          );


        $area_items = ['height'];
        $result =  SelectItem::getTestTargetIds($area_items);

        $expected = [1,4,6,7,9,10];
        // print_r($result);
        $this->assertEquals($expected,$result); 

        $area_items = ['fecaloccult_blood'];
        $result =  SelectItem::getTestTargetIds($area_items);

        $expected = [1,2,3,5,6,7,8,9,10,39,42,43,47,49,56,57,60,66];
        // print_r($result);
        $this->assertEquals($expected,$result); 


        $area_items = ['fecaloccult_blood','abdominal_circumference'];
        $result =  SelectItem::getTestTargetIds($area_items);

        $expected = [1,2,3,4,5,6,7,8,9,10,39,41,42,43,47,49,56,57,58,60,61,62,63,64,65,66];
        // print_r($result);
        $this->assertEquals($expected,$result); 


        $area_items = ['hearing_test','hearing_test_conv'];
        $result =  SelectItem::getTestTargetIds($area_items);

        $expected = [1,2,3,4,5,7,8,9,10,39,40,42,43,47,49,50,51,52,53,54,55,56,57,
        58,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91];
    
        print_r($result);
        $this->assertEquals($expected,$result); 



        $area_items = null;
        // print_r($selectItem);
        $result =  SelectItem::getTestTargetIds($area_items);

        print_r($result);
        $this->assertFalse($result);        

    }

 
    public function tearDown(): void
    {
        parent::tearDown();
    }

    
}

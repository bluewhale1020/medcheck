<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\ImportService;

use Illuminate\Http\Request;
use App\Services\ManageReserve;

use App\Account;
use App\ReserveInfo;
use App\SelectItem;
use App\Configuration;

use Carbon\Carbon;

class ManageReserveTest extends TestCase
{
    use DatabaseTransactions;

    protected $manager;
    protected $createRequest;
    protected $updateRequest;
    protected $emptyRequest;
    protected $createSortedRequest;
    protected $updateSortedRequest;
    protected $emptySortedRequest;

    protected $test_reserve_id;
    protected $account_id;
    protected $select_list;

    public function setup(): void
    {
        parent::setUp();

        $this->reception_list_id = 4;
        $config = Configuration::where('name','reception_list_id')->first();
        $config->value = $this->reception_list_id;
        $config->save();


        $this->select_list = [
            "height",
            "weight",
            "bodyfat_ratio",
            "abdominal_circumference",
            "vision_test",
            "hearing_test",
            "hearing_test_conv",
            "physical_examination",
            "blood_pressure",
            "urinary_sediment",
            "fecaloccult_blood",
            "electrogram_test",
            "chest_xray",
            "stomach_xray",
            "eye_pressure",
            "eyeground",
            "grip_strength",
            "lung_capacities",
            "dust",
            "lead",
            "ionizing_radiation",
            "Indium",
            "urinary_test",
            "urinary_test_type",
            "blood_test",
            "blood_test_type",
            "urinary_metabolites",
            "methyl_hippuric_acid",
            "n-formylmethylamine",
            "mandelic_acid",
            "trichloroacetic_acid",
            "hippuric_acid",
            "2,5-hexanedione",
            "formaldehyde",
        ];

        $this->manager = new ManageReserve();

        factory(ReserveInfo::class,1)->create()
        ->each(function(ReserveInfo $reserve) {
        $reserve->select_item()->save(factory(SelectItem::class)->make());
        $this->test_reserve_id = $reserve->id;
        $this->account_id = $reserve->account_id;
        });


        $rawData = [
            "2,5-hexanedione"=> 2,
            "abdominal_circumference"=> "0",
            "account_id"=> $this->account_id,
            "birthdate"=> "1982-01-01",
            "blood_pressure"=> "0",
            "blood_test"=> 2,
            "blood_test_type"=> "0",
            "bodyfat_ratio"=> 0,
            "chest_xray"=> 2,
            "course"=> "aoba健診",
            "dust"=> 0,
            "electrogram_test"=> 1,
            "eye_pressure"=> 2,
            "eyeground"=> 1,
            "fecaloccult_blood"=> 2,
            "formaldehyde"=> 2,
            "grip_strength"=> 0,
            "hearing_test"=> 2,
            "hearing_test_conv"=> 0,
            "height"=> 0,
            "hippuric_acid"=> "1",
            "id"=> $this->test_reserve_id,
            "id_number"=> "ID0123",
            "ionizing_radiation"=> 0,
            "kana"=> "あおば",
            "kenpo"=> 1,
            "lead"=> 2,
            "lung_capacities"=> 1,
            "mandelic_acid"=> 2,
            "methyl_hippuric_acid"=> "0",
            "n-formylmethylamine"=> 0,
            "name"=> "青葉",
            "notes"=> "wefaweffae",
            "physical_examination"=> 2,
            "reserve_info_id"=> $this->test_reserve_id,
            "reception_list_id"=> $this->reception_list_id,        
            "select_list"=>$this->select_list,        
            "serial_number"=> 161,
            "sex"=> "男",
            "stomach_xray"=> 2,
            "trichloroacetic_acid"=> "1",
            "urinary_metabolites"=> "1",
            "urinary_sediment"=> 2,
            "urinary_test"=> 1,
            "urinary_test_type"=> "ウロビリ",
            "vision_test"=> "0",
            "weight"=> "0",
        ];
        $this->updateRequest = new Request($rawData);

        $rawData = [
            "abdominal_circumference"=> "1",
            "account_id"=> null,
            "birthdate"=> "1989-02-07",
            "blood_pressure"=> "1",
            "blood_test"=> "1",
            "blood_test_type"=> "F",
            "course"=> "定期健診",
            "electrogram_test"=> "1",
            "eyeground"=> "1",
            "hearing_test_conv"=> "1",
            "height"=> "1",
            "id"=> null,
            "id_number"=> "1111",
            "kana"=> "test",
            "kenpo"=> "0",
            "name"=> "青葉レントゲン",
            "notes"=> "fawefwaf",
            "physical_examination"=> "1",
            "reserve_info_id"=> null,
            "reception_list_id"=> null,
            "select_list"=>$this->select_list,
            "serial_number"=> 184,
            "sex"=> "男",
            "urinary_test"=> "1",
            "urinary_test_type"=> "糖",
            "vision_test"=> "1",
            "weight"=> "1",     
        ];
        $this->createRequest = new Request($rawData);


        $rawData = [
        "select_list"=>$this->select_list,
        ];
        $this->emptyRequest = new Request($rawData);



        #create
        $accountData = [
            "kana"=> "test",   
            "name"=> "青葉レントゲン",     
            "id_number"=> "1111",
            "birthdate"=> "1989-02-07",     
            "sex" =>'男'
        ];

        $now = Carbon::now();

        $reserveData =[
            "account_id"=>null,
            "serial_number"=> 184,
            "reception_list_id"=> $this->reception_list_id,        
            "checkup_date"=>$now->format('Y-m-d H:i:s'),
            "schedule_date"=>$now->format('Y-m-d H:i:s'),
            "course"=> "定期健診",
            "kenpo"=> "0",
            "notes"=> "fawefwaf",      
        ];
        $selectData=[
            "abdominal_circumference"=> "1",
            "blood_pressure"=> "1",
            "blood_test"=> "1",
            "blood_test_type"=> "F",
            "electrogram_test"=> "1",
            "eyeground"=> "1",
            "hearing_test_conv"=> "1",
            "height"=> "1",
            "physical_examination"=> "1",
            "reserve_info_id"=> '',
            "urinary_test"=> "1",
            "urinary_test_type"=> "糖",
            "vision_test"=> "1",
            "weight"=> "1", 
        ];

        $expected1 =$this->createRequest($accountData);
        $expected2 =$this->createRequest($reserveData);
        $expected3 =$this->createRequest($selectData);
        $this->createSortedRequest = [$expected1,$expected2,$expected3];


        #update
        $accountData = [
            "kana"=> "あおば",
            "name"=> "青葉",     
            "id_number"=> "ID0123",
            "birthdate"=> "1982-01-01",
            "sex"=> "男",
        ];

        $now = Carbon::now();

        $reserveData =[
            "account_id"=>$this->account_id,
            "serial_number"=> 161,
            "reception_list_id"=> $this->reception_list_id,        
            "checkup_date"=>$now->format('Y-m-d H:i:s'),
            "schedule_date"=>$now->format('Y-m-d H:i:s'),
            "course"=> "aoba健診",
            "kenpo"=> 1,
            "notes"=> "wefaweffae",     
        ];
        $selectData=[
            "2,5-hexanedione"=> 2,
            "abdominal_circumference"=> "0",
            "blood_pressure"=> "0",
            "blood_test"=> 2,
            "blood_test_type"=> "0",
            "bodyfat_ratio"=> 0,
            "chest_xray"=> 2,
            "dust"=> 0,
            "electrogram_test"=> 1,
            "eye_pressure"=> 2,
            "eyeground"=> 1,
            "fecaloccult_blood"=> 2,
            "formaldehyde"=> 2,
            "grip_strength"=> 0,
            "hearing_test"=> 2,
            "hearing_test_conv"=> 0,
            "height"=> 0,
            "hippuric_acid"=> "1",
            "ionizing_radiation"=> 0,
            "lead"=> 2,
            "lung_capacities"=> 1,
            "mandelic_acid"=> 2,
            "methyl_hippuric_acid"=> "0",
            "n-formylmethylamine"=> 0,
            "physical_examination"=> 2,
            "reserve_info_id"=> $this->test_reserve_id,
            "stomach_xray"=> 2,
            "trichloroacetic_acid"=> "1",
            "urinary_metabolites"=> "1",
            "urinary_sediment"=> 2,
            "urinary_test"=> 1,
            "urinary_test_type"=> "ウロビリ",
            "vision_test"=> "0",
            "weight"=> "0", 
        ];

        $expected1 =$this->createRequest($accountData);
        $expected2 =$this->createRequest($reserveData);
        $expected3 =$this->createRequest($selectData);
        $this->updateSortedRequest = [$expected1,$expected2,$expected3];


        #empty
        $accountData = [
            "kana"=> "",   
            "name"=> "",     
            "id_number"=> "",
            "birthdate"=> "",     
            "sex" =>''
        ];

        $now = Carbon::now();        

        $reserveData =[
            "account_id"=>'',
            "serial_number"=> '',
            "reception_list_id"=> $this->reception_list_id,            
            "checkup_date"=>$now->format('Y-m-d H:i:s'),
            "schedule_date"=>$now->format('Y-m-d H:i:s'),
            "course"=> "",
            "kenpo"=> "",
            "notes"=> "",      
        ];

        $selectData = [
            'reserve_info_id'=>''
        ];

        $expected1 =$this->createRequest($accountData);
        $expected2 =$this->createRequest($reserveData);
        $expected3 =$this->createRequest($selectData);
        $this->emptySortedRequest = [$expected1,$expected2,$expected3]; 




    } 

    public function createRequest($rawData,$is_select_item = false){

        // if($is_select_item){

            // $rawData['reserve_info_id']=$this->test_reserve_id;        
        // }
        return new Request($rawData);
    }


    /**
     * A unit test testSortRequestData
     *
     * @return void
     */
    public function testSortRequestData()
    {

       #create
       $accountData = [
        "kana"=> "test",   
        "name"=> "青葉レントゲン",     
        "id_number"=> "1111",
        "birthdate"=> "1989-02-07",     
        "sex" =>'男'
       ];

       $now = Carbon::now();

       $reserveData =[
        "account_id"=>null,
        "serial_number"=> 184,
        "reception_list_id"=> $this->reception_list_id,       
        "checkup_date"=>$now->format('Y-m-d H:i:s'),
        "schedule_date"=>$now->format('Y-m-d H:i:s'),
        "course"=> "定期健診",
        "kenpo"=> "0",
        "notes"=> "fawefwaf",      
       ];
       $selectData=[
        "abdominal_circumference"=> "1",
        "blood_pressure"=> "1",
        "blood_test"=> "1",
        "blood_test_type"=> "F",
        "electrogram_test"=> "1",
        "eyeground"=> "1",
        "hearing_test_conv"=> "1",
        "height"=> "1",
        "physical_examination"=> "1",
        "reserve_info_id"=> '',
        "urinary_test"=> "1",
        "urinary_test_type"=> "糖",
        "vision_test"=> "1",
        "weight"=> "1", 
       ];



       list($accountRequest,$reserveRequest,$selectRequest) =  $this->manager->sortRequestData($this->createRequest);
       print_r($reserveRequest->all());
        $expected1 =$this->createRequest($accountData);
        $expected2 =$this->createRequest($reserveData);
        $expected3 =$this->createRequest($selectData);


        // print_r($result);
        $this->assertEquals($accountData,$accountRequest->all());
        $this->assertEquals($reserveData,$reserveRequest->all());
        $this->assertEquals($selectData,$selectRequest->all());
       
       #update
       $accountData = [
        "kana"=> "あおば",
        "name"=> "青葉",     
        "id_number"=> "ID0123",
        "birthdate"=> "1982-01-01",
        "sex"=> "男",
       ];

       $now = Carbon::now();

       $reserveData =[
        "account_id"=>$this->account_id,
        "serial_number"=> 161,
        "reception_list_id"=> $this->reception_list_id,         
        "checkup_date"=>$now->format('Y-m-d H:i:s'),
        "schedule_date"=>$now->format('Y-m-d H:i:s'),
        "course"=> "aoba健診",
        "kenpo"=> 1,
        "notes"=> "wefaweffae",     
       ];
       $selectData=[
        "2,5-hexanedione"=> 2,
        "abdominal_circumference"=> "0",
        "blood_pressure"=> "0",
        "blood_test"=> 2,
        "blood_test_type"=> "0",
        "bodyfat_ratio"=> 0,
        "chest_xray"=> 2,
        "dust"=> 0,
        "electrogram_test"=> 1,
        "eye_pressure"=> 2,
        "eyeground"=> 1,
        "fecaloccult_blood"=> 2,
        "formaldehyde"=> 2,
        "grip_strength"=> 0,
        "hearing_test"=> 2,
        "hearing_test_conv"=> 0,
        "height"=> 0,
        "hippuric_acid"=> "1",
        "ionizing_radiation"=> 0,
        "lead"=> 2,
        "lung_capacities"=> 1,
        "mandelic_acid"=> 2,
        "methyl_hippuric_acid"=> "0",
        "n-formylmethylamine"=> 0,
        "physical_examination"=> 2,
        "reserve_info_id"=> $this->test_reserve_id,
        "stomach_xray"=> 2,
        "trichloroacetic_acid"=> "1",
        "urinary_metabolites"=> "1",
        "urinary_sediment"=> 2,
        "urinary_test"=> 1,
        "urinary_test_type"=> "ウロビリ",
        "vision_test"=> "0",
        "weight"=> "0", 
       ];

       list($accountRequest,$reserveRequest,$selectRequest) =  $this->manager->sortRequestData($this->updateRequest);

       $expected1 =$this->createRequest($accountData);
       $expected2 =$this->createRequest($reserveData);
       $expected3 =$this->createRequest($selectData);


       $this->assertEquals($accountData,$accountRequest->all());
       $this->assertEquals($reserveData,$reserveRequest->all());
       $this->assertEquals($selectData,$selectRequest->all());
     
       
        #empty
        $accountData = [
            "kana"=> "",   
            "name"=> "",     
            "id_number"=> "",
            "birthdate"=> "",     
            "sex" =>''
        ];

        $now = Carbon::now();        

        $reserveData =[
            "account_id"=>'',
            "serial_number"=> '',
            "reception_list_id"=> $this->reception_list_id,             
            "checkup_date"=>$now->format('Y-m-d H:i:s'),
            "schedule_date"=>$now->format('Y-m-d H:i:s'),
            "course"=> "",
            "kenpo"=> "",
            "notes"=> "",      
        ];

        $selectData = [
            'reserve_info_id'=>''
        ];

        list($accountRequest,$reserveRequest,$selectRequest) =  $this->manager->sortRequestData($this->emptyRequest);

        $expected1 =$this->createRequest($accountData);
        $expected2 =$this->createRequest($reserveData);
        $expected3 =$this->createRequest($selectData);

        // print_r($result);
        $this->assertEquals($expected1,$accountRequest);
        $this->assertEquals($expected2,$reserveRequest);
        $this->assertEquals($expected3,$selectRequest);

    }

    public function testSetCheckupdate(){
        // Stop here and mark this test as incomplete.
        // $this->markTestIncomplete(
        //     'This test has not been implemented yet.'
        // );
        $now = Carbon::now();

        $data = [
            'checkup_date'=>'2019-07-05 10:00:00',
            'schedule_date'=>'2019-07-05 10:00:00',
        ];
        $result =  $this->manager->setCheckupdate($data);

        $expected = [
            'checkup_date'=>'2019-07-05 10:00:00',
            'schedule_date'=>'2019-07-05 10:00:00',            
        ];
        $this->assertEquals($expected,$result);   
        
        $data = [
            'checkup_date'=>'',
            'schedule_date'=>'2019-07-05 10:00:00',
        ];
        $result =  $this->manager->setCheckupdate($data);

        $expected = [
            'checkup_date'=>$now->format('Y-m-d H:i:s'),
            'schedule_date'=>'2019-07-05 10:00:00',            
        ];
        $this->assertEquals($expected,$result);   
        
        $data = [
            'checkup_date'=>'',
            'schedule_date'=>'',
        ];
        $result =  $this->manager->setCheckupdate($data);

        $expected = [
            'checkup_date'=>$now->format('Y-m-d H:i:s'),
            'schedule_date'=>$now->format('Y-m-d H:i:s'),            
        ];
        $this->assertEquals($expected,$result);   
        
        

    } 

    public function testStoreData(){
        // Stop here and mark this test as incomplete.
        // $this->markTestIncomplete(
        //     'This test has not been implemented yet.'
        // );        
        #create
        list($accountRequest,$reserveRequest,$selectRequest) = $this->createSortedRequest;
        $result =  $this->manager->storeData($accountRequest,$reserveRequest,$selectRequest);

        // print_r($result);

        $this->assertInstanceOf(Account::class, $result['account']);
        $this->assertInstanceOf(ReserveInfo::class, $result['reserve']);
        $this->assertInstanceOf(SelectItem::class, $result['select_item']);

        $reserve_infos = $reserveRequest->all();
        $reserve_infos['account_id'] = $result['account']->id;
        $select_items = $selectRequest->all();
        $select_items['reserve_info_id'] = $result['reserve']->id;        

        $this->assertDatabaseHas('accounts',$accountRequest->all()); 
        $this->assertDatabaseHas('reserve_infos',$reserve_infos); 
        $this->assertDatabaseHas('select_items',$select_items); 

        #empty
        list($accountRequest,$reserveRequest,$selectRequest) = $this->emptySortedRequest;
        $result =  $this->manager->storeData($accountRequest,$reserveRequest,$selectRequest);

        $this->assertFalse($result['account']);
        $this->assertFalse($result['reserve']);
        $this->assertFalse($result['select_item']);
         
    }  

    public function testUpdateData(){
        // Stop here and mark this test as incomplete.
        // $this->markTestIncomplete(
        //     'This test has not been implemented yet.'
        // );
        #update
        list($accountRequest,$reserveRequest,$selectRequest) = $this->updateSortedRequest;

        // print_r($reserveRequest->all());

        $result =  $this->manager->updateData($accountRequest,$reserveRequest,$selectRequest,$this->test_reserve_id);

        $this->assertInstanceOf(Account::class, $result['account']);
        $this->assertInstanceOf(ReserveInfo::class, $result['reserve']);
        $this->assertInstanceOf(SelectItem::class, $result['select_item']);
     
        $reserve_infos = $reserveRequest->all();
        $reserve_infos['account_id'] = $result['account']->id;
        $select_items = $selectRequest->all();
        $select_items['reserve_info_id'] = $result['reserve']->id;        

        $this->assertDatabaseHas('accounts',$accountRequest->all()); 
        $this->assertDatabaseHas('reserve_infos',$reserve_infos); 
        $this->assertDatabaseHas('select_items',$select_items); 

        #empty
        list($accountRequest,$reserveRequest,$selectRequest) = $this->emptySortedRequest;
        $result =  $this->manager->updateData($accountRequest,$reserveRequest,$selectRequest,$this->test_reserve_id);

        // print_r($result);
        $this->assertFalse($result['account']);
        $this->assertFalse($result['reserve']);
        $this->assertFalse($result['select_item']);        
    }  

    public function testCreateReserve(){
        // Stop here and mark this test as incomplete.
        // $this->markTestIncomplete(
        //     'This test has not been implemented yet.'
        // );
        $result =  $this->manager->createReserve($this->createRequest);

        $this->assertInstanceOf(Account::class, $result['account']);
        $this->assertInstanceOf(ReserveInfo::class, $result['reserve']);
        $this->assertInstanceOf(SelectItem::class, $result['select_item']);
        
        list($accountRequest,$reserveRequest,$selectRequest) = $this->createSortedRequest; 
        

        $reserve_infos = $reserveRequest->all();
        $reserve_infos['account_id'] = $result['account']->id;
        $select_items = $selectRequest->all();
        $select_items['reserve_info_id'] = $result['reserve']->id;        

        $this->assertDatabaseHas('accounts',$accountRequest->all()); 
        $this->assertDatabaseHas('reserve_infos',$reserve_infos); 
        $this->assertDatabaseHas('select_items',$select_items);         
         
    }  

    public function testUpdateReserve(){
        // Stop here and mark this test as incomplete.
        // $this->markTestIncomplete(
        //     'This test has not been implemented yet.'
        // );
        $result =  $this->manager->UpdateReserve($this->updateRequest,$this->test_reserve_id);

        $this->assertInstanceOf(Account::class, $result['account']);
        $this->assertInstanceOf(ReserveInfo::class, $result['reserve']);
        $this->assertInstanceOf(SelectItem::class, $result['select_item']);
        
        list($accountRequest,$reserveRequest,$selectRequest) = $this->updateSortedRequest; 

        $reserve_infos = $reserveRequest->all();
        $reserve_infos['account_id'] = $result['account']->id;
        $select_items = $selectRequest->all();
        $select_items['reserve_info_id'] = $result['reserve']->id;        

        $this->assertDatabaseHas('accounts',$accountRequest->all()); 
        $this->assertDatabaseHas('reserve_infos',$reserve_infos); 
        $this->assertDatabaseHas('select_items',$select_items);          
       
    }  

    public function tearDown(): void
    {
        parent::tearDown();
    }     

}

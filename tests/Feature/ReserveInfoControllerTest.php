<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Http\Request;
use Mockery;

use App\ReserveInfo;
use App\SelectItem;
use App\SelectItemInfo;
use App\ReceptionList;
use App\Configuration;

use App\Services\ManageReserve;

class ReserveInfoControllerTest extends TestCase
{

    protected $reception_list_id;
    protected $account_id;
    protected $test_reserve_id;
    protected $serial_number;
    protected $createRequest;
    protected $updateRequest;
  

    public function setup(): void
    {
        parent::setUp();
        //   //データの定義とインサート
        $reception_list = ReceptionList::create([
            'name'=>'test',                     
            'import_date'=>'2019-07-05',                     
            'expiration_date'=>'2019-08-05',                     
            'first_serial_number'=>28,
            'last_serial_number'=>41,
            'max_serial_number'=>55,
        ]);
        $this->reception_list_id = $reception_list->id;

        $config = Configuration::where('name','reception_list_id')->first();
        $config->value = $this->reception_list_id;
        $config->save();

        
        factory(ReserveInfo::class,1)->create()
        ->each(function(ReserveInfo $reserve) {
        $reserve->select_item()->save(factory(SelectItem::class)->make());
        $this->test_reserve_id = $reserve->id;
        $this->account_id = $reserve->account_id;
        $this->serial_number = $reserve->serial_number;
        });

        $rawData = [
            "account_id"=> $this->account_id,
            "birthdate"=> "1982-01-01",
            "course"=> "aoba健診",
            "id"=> $this->test_reserve_id,
            "id_number"=> "ID0123",
            "kana"=> "あおば",
            "kenpo"=> 1,
            "reserve_info_id"=> $this->test_reserve_id,
            "reception_list_id"=> $this->reception_list_id,        
           "serial_number"=> $this->serial_number,
            "sex"=> "男",
            ];
            $this->updateRequest = new Request($rawData);
    
            $rawData = [
            "account_id"=> null,
            "birthdate"=> "1989-02-07",
            "course"=> "定期健診",
            "id"=> null,
            "id_number"=> "1111",
            "kana"=> "test",
            "kenpo"=> "0",
            "reserve_info_id"=> null,
            "reception_list_id"=> null,
            "serial_number"=> 184,
            "sex"=> "男",
            ];
            $this->createRequest = new Request($rawData);


    } 


    /**
     * testStore test 
     * @param ManageReserve $reserveService,ManageSerialNumber $snManager,Request $request
     * @return ['account'=>$account,'reserve'=>$reserve,'select_item'=>$selectItem,'next_number'=>$number ,'errors'=>$this->getErrorInfo(),'message'=>$this->getUpdateMessage()]
     */
    public function testStore()
    {


        $retData = ['account'=>[],'reserve'=>[],'select_item'=>[]];
        
        $mock = Mockery::mock(ManageReserve::class);

        $mock->shouldReceive('createReserve')
                ->once()
                ->andReturn($retData);

        $this->instance(ManageReserve::class, $mock);


        $response = $this->post("api/reserve",$this->createRequest->all());            
        // $response = $this->call('PUT', "api/exam_area/".$this->test_reserve_id,$rawData);
        // dd($response->getContent());
        $responseData = $response->getContent();

        // print_r($responseData);
        $response->assertStatus(200);   

        $next_number = ['next_number'=>185];
        foreach ($next_number as $key => $value) {
            # code...
            $response->assertSee('"'.$key.'":'.$value);             
        }
        // $response->assertSee($next_number);             
    } 
    

    /**
     * testStore test 
     * @param ManageReserve $reserveService,ManageSerialNumber $snManager,Request $request,$id
     * @return ['account'=>$account,'reserve'=>$reserve,'select_item'=>$selectItem,'next_number'=>$number ,'errors'=>$this->getErrorInfo(),'message'=>$this->getUpdateMessage()]
     */
    public function testUpdate()
    {


        $retData = ['account'=>[],'reserve'=>[],'select_item'=>[]];
        
        $mock = Mockery::mock(ManageReserve::class);

        $mock->shouldReceive('updateReserve')
                ->once()
                ->andReturn($retData);

        $this->instance(ManageReserve::class, $mock);


        $response = $this->put("api/reserve/".$this->test_reserve_id,$this->updateRequest->all());            
        // $response = $this->call('PUT', "api/exam_area/".$this->test_reserve_id,$rawData);
        // dd($response->getContent());
        $responseData = $response->getContent();

        // print_r($responseData);
        $response->assertStatus(200);   

        $next_number = ['next_number'=>$this->serial_number+1];
        foreach ($next_number as $key => $value) {
            # code...
            $response->assertSee('"'.$key.'":'.$value);             
        }
        // $response->assertSee($next_number);             
    } 

    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }      
}

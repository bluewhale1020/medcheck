<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
// use Illuminate\Foundation\Testing\WithFaker;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\ImportService;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use \Illuminate\Validation\ValidationException as ValidationException;
use App\Account;
use App\ReserveInfo;
use App\SelectItem;

class ImportServiceTest extends TestCase
{

    use DatabaseTransactions;

    protected $import;

    public function setup(): void
    {
      parent::setUp();

      $this->import = new ImportService();
    }    


    /**
     * A unit test testGet_datetime
     *
     * @return void
     */
    public function testGet_datetime()
    {
        // 入力データ			            期待結果	

        $columnName = '受診予定日';
        $itemValue = '2019-07-10';

        $result =  $this->import->get_datetime($columnName,$itemValue);

        $expected = false;
        // print_r($result);
        $this->assertEquals($expected,$result);         

        // itemValue:会話法 			[hearing_test_conv:1]
        $columnName = '予約時間';
        $itemValue = '12:30:00';

        $result =  $this->import->get_datetime($columnName,$itemValue);

        $expected = ['schedule_date'=>'2019-07-10 12:30:00'];
        // print_r($result);
        $this->assertEquals($expected,$result);         
        
        $columnName = '受診予定日';
        $itemValue = '';

        $result =  $this->import->get_datetime($columnName,$itemValue);

        $expected = false;
        // print_r($result);
        $this->assertEquals($expected,$result);         

        // itemValue:会話法 			[hearing_test_conv:1]
        $columnName = '予約時間';
        $itemValue = '12:30:00';

        $result =  $this->import->get_datetime($columnName,$itemValue);

        $expected = false;
        // print_r($result);
        $this->assertEquals($expected,$result);        


        $columnName = '受診予定日';
        $itemValue = '2019-07-10';

        $result =  $this->import->get_datetime($columnName,$itemValue);

        $expected = false;
        // print_r($result);
        $this->assertEquals($expected,$result);         

        // itemValue:会話法 			[hearing_test_conv:1]
        $columnName = '予約時間';
        $itemValue = '';

        $result =  $this->import->get_datetime($columnName,$itemValue);

        $expected = ['schedule_date'=>'2019-07-10 00:00:00'];
        // print_r($result);
        $this->assertEquals($expected,$result);          

    }


    /**
     * A unit test testFunc_select_hearing_type
     *
     * @return void
     */
    public function testFunc_select_hearing_type()
    {
        // 入力データ			            期待結果	
        // itemValue:オージオ			[hearing_test:1]
        $columnName = '聴力';
        $itemValue = 'オージオ';

        $result =  $this->import->func_select_hearing_type($columnName,$itemValue);

        $expected = ['hearing_test'=>1];
        // print_r($result);
        $this->assertEquals($expected,$result);         

        // itemValue:会話法 			[hearing_test_conv:1]
        $columnName = '聴力';
        $itemValue = '会話法';

        $result =  $this->import->func_select_hearing_type($columnName,$itemValue);

        $expected = ['hearing_test_conv'=>1];
        // print_r($result);
        $this->assertEquals($expected,$result);         
        
        // null			            [hearing_test:1]	
        $columnName = null;
        $itemValue = null;

        $result =  $this->import->func_select_hearing_type($columnName,$itemValue);        

        $expected = ['hearing_test'=>1];
        // print_r($result);
        $this->assertEquals($expected,$result);
    }

    /**
     * A unit test testFunc_check_test_type
     *
     * @return void
     */
    public function testFunc_check_test_type()
    {
        // 入力データ			                            期待結果	
        // columnName:血液検査,itemValue:A+E			[blood_test:1,blood_test_type:A+E]	

        $columnName = '血液検査';
        $itemValue = 'A+E';

        $result =  $this->import->func_check_test_type($columnName,$itemValue);

        $expected = ['blood_test'=>1,'blood_test_type'=>'A+E'];
        // print_r($result);
        $this->assertEquals($expected,$result);

        // columnName:尿検査2,itemValue:蛋白・糖			[urinary_test:1,urinary_test_type:蛋白・糖]	
        $columnName = '尿検査2';
        $itemValue = '蛋白・糖';

        $result =  $this->import->func_check_test_type($columnName,$itemValue);

        $expected = ['urinary_test_type'=>'蛋白+糖'];
        // print_r($result);
        $this->assertEquals($expected,$result);


        // null			                                FALSE	
        $columnName = null;
        $itemValue = null;

        $result =  $this->import->func_check_test_type($columnName,$itemValue);

        $expected = false;
        // print_r($result);
        $this->assertEquals($expected,$result);        

        // $this->assertTrue(true);
    }

    /**
     * A unit test testConv_selectitemdata_into_num
     *
     * @return void
     */
    public function testConv_selectitemdata_into_num()
    {
        $select_items = [
            'item1'=>'234',
            'item2'=>'test',
            'item3'=>'●',
            'item4'=>'',
            'item5'=>'●',
            'item6'=>'●',
        ];

        $result =  $this->import->conv_selectitemdata_into_num($select_items);

        $expected = [
            'item1'=>'234',
            'item2'=>'test',
            'item3'=>'1',
            'item4'=>'',
            'item5'=>'1',
            'item6'=>'1',
        ];
        // print_r($result);
        $this->assertEquals($expected,$result); 

    }

    /**
     * A unit test testSortData
     *
     * @return void
     */
    public function testSortData()
    {
        // 入力データ			                                        期待結果	
        // headers[フリガナ、整理番号、身長、聴力、尿検査],               accounts[kana:テスト太郎],
        // records[テスト太郎、1234,161,オージオ,糖・潜血]			      reserve_infos[serial_number:1234],
        //                                                             select_items[height :161,hearing_test:1,urinary_test:1,urinary_test_type:糖・潜血]
        
        $rawData = [
            'フリガナ'=>'テスト太郎',
            '整理番号'=>'1234',
            '身長'=>'●',
            '聴力'=>'オージオ',
            '尿検査2'=>'糖・潜血',
            '尿検査'=>'●',
            'reception_list_id'=>55
        ];
        $accounts = ['kana'=>'テスト太郎'];
        $reserve_infos = ['reception_list_id'=>55,'serial_number'=>1234];
        $select_items = ['height' =>1,'hearing_test'=>1,'urinary_test'=>1,'urinary_test_type'=>'糖+潜血'];        
        $expected = ['accounts'=>$accounts,'reserve_infos'=>$reserve_infos,'select_items'=>$select_items];
        
        $result =  $this->import->sortData($rawData);
        
        // print_r($result);
        $this->assertEquals($expected,$result);
        
        // []	            	                                        ['accounts'=>[],'reserve_infos'=>[],'select_items'=>[]];	
        $rawData = [];
        $expected = ['accounts'=>[],'reserve_infos'=>[],'select_items'=>[]];
        
        $result =  $this->import->sortData($rawData);
        
        // print_r($result);
        $this->assertEquals($expected,$result);


    }

    public function testSaveAccount(){
        $accountsData = ['kana'=>'testaccount','name'=>'testaccount','sex'=>'男','birthdate'=>'2000-01-01'];
        $accounts = new Request($accountsData);
        $result =  $this->import->saveAccount($accounts);
        
        // print_r($result);
        $this->assertInstanceOf(Account::class, $result);
        $this->assertDatabaseHas('accounts',$accountsData);        

        
        
        $accountsData = ['kana'=>'testaccount','name'=>'updateaccount','sex'=>'男','birthdate'=>'2000-01-01'];
        $accounts = new Request($accountsData);        
        $result =  $this->import->saveAccount($accounts);
        
        // print_r($result);
        $this->assertInstanceOf(Account::class, $result);
        $this->assertDatabaseHas('accounts',$accountsData);   
        
        
        
        $accountsData = ['name'=>'updateaccount','sex'=>'男','birthdate'=>'2000-01-01'];
        $accounts = new Request($accountsData);        
        $result =  $this->import->saveAccount($accounts);
        
        // print_r($result);
        $this->assertFalse($result);
       

    }

    public function testSaveReserveInfo(){
        $account = Account::where('id','>',0)->first();
        $reserve_infosData = ['reception_list_id'=>2, 'serial_number'=>1234,'checkup_info_id'=>111,'schedule_date'=>'2019-06-20'];
        $reserve_infos = new Request($reserve_infosData);
        $result =  $this->import->saveReserveInfo($reserve_infos,$account);
        
        // print_r($result);
        $this->assertInstanceOf(ReserveInfo::class, $result);
        $this->assertDatabaseHas('reserve_infos',$reserve_infosData);
        
        $reserve_infosData = ['reception_list_id'=>3, 'serial_number'=>4444,'checkup_info_id'=>111,'schedule_date'=>'2019-06-20'];
        $reserve_infos = new Request($reserve_infosData);
        $result =  $this->import->saveReserveInfo($reserve_infos,$account);
        
        // print_r($result);
        $this->assertInstanceOf(ReserveInfo::class, $result);
        $this->assertDatabaseHas('reserve_infos',$reserve_infosData);  
        
        
        $reserve_infosData = ['reception_list_id'=>3, 'serial_number'=>4444,'checkup_info_id'=>111];
        $reserve_infos = new Request($reserve_infosData);
        $result =  $this->import->saveReserveInfo($reserve_infos,$account);
        
        // print_r($result);
        $this->assertFalse($result);      
    }

    public function testSaveSelectItem(){
        $reserve = ReserveInfo::where('id','>',0)->first();
        $select_itemsData = ['height' =>1,'hearing_test'=>1,'urinary_test'=>1,'urinary_test_type'=>"test1"];
        $select_items = new Request($select_itemsData);
        $result =  $this->import->saveSelectItem($select_items,$reserve);

        // print_r($result);
        $this->assertInstanceOf(SelectItem::class, $result);
        $this->assertDatabaseHas('select_items',$select_itemsData);
        
        $select_itemsData = ['height' =>2,'hearing_test'=>2,'urinary_test'=>2,'urinary_test_type'=>"test2"];
        $select_items = new Request($select_itemsData);
        $result =  $this->import->saveSelectItem($select_items,$reserve);
        
        // print_r($result);
        $this->assertInstanceOf(SelectItem::class, $result);
        $this->assertDatabaseHas('select_items',$select_itemsData);  
        
        
        $select_itemsData = ['height' =>1,'hearing_test'=>1,'urinary_test'=>1,'urinary_test_type'=>5];
        $select_items = new Request($select_itemsData);
        $result =  $this->import->saveSelectItem($select_items,$reserve);
        
        // print_r($result);
        $this->assertFalse($result); 


    }


    public function testSaveData(){

        $accounts = ['kana'=>'テストタロウ','name'=>'テスト太郎','sex'=>'男','birthdate'=>'1999-12-31'];
        $reserve_infos = ['reception_list_id'=>2, 'serial_number'=>1234,'checkup_info_id'=>111,'schedule_date'=>'2019-06-20'];
        $select_items = ['height' =>1,'hearing_test'=>1,'urinary_test'=>1,'urinary_test_type'=>'糖,潜血'];
        $sortedData = ['accounts'=>$accounts,'reserve_infos'=>$reserve_infos,'select_items'=>$select_items];

        // $expected = ['accounts'=>$accounts,'reserve_infos'=>$reserve_infos,'select_items'=>$select_items];
        
        $result =  $this->import->saveData($sortedData);
        
        // print_r($result);
        $this->assertInstanceOf(Account::class, $result['account']);
        $this->assertInstanceOf(ReserveInfo::class, $result['reserve']);
        $this->assertInstanceOf(SelectItem::class, $result['select_item']);

        $reserve_infos['account_id'] = $result['account']->id;
        $select_items['reserve_info_id'] = $result['reserve']->id;

        $this->assertDatabaseHas('accounts',$accounts);         
        $this->assertDatabaseHas('reserve_infos',$reserve_infos);         
        $this->assertDatabaseHas('select_items',$select_items);         

        // []	            	                                     	
        // $sortedData = ['accounts'=>[],'reserve_infos'=>[],'select_items'=>[]];
        // $expected = ['account'=>false,'reserve'=>false,'select_item'=>false];
        
        // $result =  $this->import->saveData($rawData);
        
        // print_r($result);
        // $this->assertEquals($expected,$result);

    }

    public function testImportReceptionList(){
        $rawData = [
            'フリガナ'=>'テストタロウ','氏名'=>'テスト太郎','性別'=>'男','生年月日'=>'1999-12-31' ,           
            'reception_list_id'=>2, '整理番号'=>1234,'No'=>111,'受診予定日'=>'2019-06-20','予約時間'=>'',
            '身長'=>'●',
            '聴力'=>'オージオ',
            '尿検査2'=>'糖・潜血',
            '尿検査'=>'●',
        ];

        $accounts = ['kana'=>'テストタロウ','name'=>'テスト太郎','sex'=>'男','birthdate'=>'1999-12-31'];
        $reserve_infos = ['reception_list_id'=>2, 'serial_number'=>1234,'checkup_info_id'=>111,'schedule_date'=>'2019-06-20'];
        $select_items = ['height' =>1,'hearing_test'=>1,'urinary_test'=>1,'urinary_test_type'=>'糖+潜血'];
        

        $result =  $this->import->importReceptionList($rawData);
        
        print_r($result);
        $this->assertInstanceOf(Account::class, $result['account']);
        $this->assertInstanceOf(ReserveInfo::class, $result['reserve']);
        $this->assertInstanceOf(SelectItem::class, $result['select_item']);

        $reserve_infos['account_id'] = $result['account']->id;
        $select_items['reserve_info_id'] = $result['reserve']->id;

        $this->assertDatabaseHas('accounts',$accounts);         
        $this->assertDatabaseHas('reserve_infos',$reserve_infos);         
        $this->assertDatabaseHas('select_items',$select_items);         

        // []	            	                                     	
        $rawData = [];
        
        
        // $expected = ['account'=>false,'reserve'=>false,'select_item'=>false];
        
        // $result =  $this->import->importReceptionList($rawData);
        
        // print_r($result);
        // $this->assertEquals($expected,$result);

    }

    public function tearDown(): void
    {
        parent::tearDown();
    }  
}

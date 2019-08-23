<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\ReceptionList;
use App\IoItemConversion;
use App\Account;
use App\ReserveInfo;
use App\SelectItem;
use App\ReserveList;

class ReceptionListTest extends TestCase
{
    protected $tb_account;

    public function setup(): void
    {
      parent::setUp();
      //データの定義とインサート
    //   factory(Item::class)->create([
    //     'title' => 'rookeis',
    //     'url' => 'http://www.yahoo.co.jp'
    //   ]);
    //   factory(Item::class,10)->create();
      $this->tb_account = new Account();
    } 

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSaveAccount()
    {
        $data = [
            'kana'=>'テスト太郎',
            'birthdate'=>'テスト太郎',
            'sex'=>'男',
        ];
        $accounts = Request($data);

        $response = $this->get('/');

        $response->assertStatus(200);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }






    public function tearDown(): void
    {
        $this->list->truncate();
        parent::tearDown();
    }      
}

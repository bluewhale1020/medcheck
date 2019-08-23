<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\EventList;

class EventListTest extends TestCase
{

    // use DatabaseTransactions;

    // protected $import;

    public function setup(): void
    {
      parent::setUp();

    //   $this->import = new EventList();
    }  

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        // データ挿入
        $data = [
            'name'=>'testname',
            'type'=>'testtype',
            'level'=>3,
            'notes'=>'testnotes',
        ];
        EventList::create($data);
        $this->assertDatabaseHas('event_lists',$data);         

        // $this->assertTrue(true);
    }



    public function tearDown(): void
    {
        parent::tearDown();
    }      
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Http\Request;
use Mockery;

class ExampleTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);


        $mock = Mockery::mock(UpdateResult::class);

        $mock->shouldReceive('saveResultForm')
                ->once()
                ->with(Mockery::any(),$this->test_reserve_id)
                ->andReturn($retData);

        $this->instance(UpdateResult::class, $mock);


        $response = $this->put("api/exam_area/".$this->test_reserve_id,$rawData);            
        // $response = $this->call('PUT', "api/exam_area/".$this->test_reserve_id,$rawData);
        // dd($response->getContent());
        $response->assertStatus(200); 

    }
}

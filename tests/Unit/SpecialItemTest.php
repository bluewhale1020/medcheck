<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\SpecialItem;

class SpecialItemTest extends TestCase
{
    public function testGetRelatedItems(){
        $name = 'dust';
        $result =  SpecialItem::getRelatedItems($name);

        $expected = ['physical_examination','lung_capacities'];
        $this->assertEquals($expected,$result);         
    } 
}

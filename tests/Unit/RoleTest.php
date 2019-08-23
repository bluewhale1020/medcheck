<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Role;

class RoleTest extends TestCase
{
    public function testGetImagePath(){
        $data = 5;
        $result =  Role::getImagePath($data);
        $image_name = 'man';
        $expected = 'img/roles/' . $image_name . '.png';
        $this->assertEquals($expected,$result);         
    } 
}

<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Configuration;

class ConfigurationTest extends TestCase
{

    /**
     * testGetBarcodeInfo unit test
     *
     * @return void
     */
    public function testGetBarcodeInfo()
    {

        $result =  Configuration::getBarcodeInfo();

        $expected = [
            'columns'=>['id_number','checkup_info_id'],
            'default_no'=>'XXXXX'
        ];
        print_r($result);
        $this->assertEquals($expected,$result); 
    }
}

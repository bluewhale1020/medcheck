<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\ReceptionList;

class ReceptionListTest extends TestCase
{
  
    public function testCalcExpirationDate(){


        $import_date = '1999-10-10 00:00:00';
        // print_r($selectItem);
        $result =  ReceptionList::calcExpirationDate($import_date);

        $expected = '1999-11-10 00:00:00';
        print($result);
        $this->assertEquals($expected,$result);        


    }


}

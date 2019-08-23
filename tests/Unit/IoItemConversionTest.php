<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\IoItemConversion;

class IoItemConversionTest extends TestCase
{
    /**
     * testGetTableColumns unit test
     *
     * @return void
     */
    public function testGetTableColumns()
    {

        $result =  IoItemConversion::getTableColumns();

        $expected = [
            "No",
            "性別",
            "校舎",
            "所属",
            "在籍番号",
            "通称名",
            "フリガナ",
            "生年月日",
            "年齢",
            "整理番号",
            "受診予定日",
            "予約時間",
            "コース",
            "協会けんぽ",
            "備考",
            "身長",
            "体重",
            "体脂肪率",
            "腹囲",
            "視力",
            "聴力",
            "血圧",
            "胸部X線",
            "心電図",
            "血液検査",
            "尿検査",
            "尿検査2",
            "尿沈渣",
            "便",
            "胃部",
            "眼底",
            "代",
            "ﾒﾁﾙ馬尿酸",
            "Nメチルホルムアミド",
            "ﾏﾝﾃﾞﾙ酸",
            "ﾄﾘｸﾛﾙ酢酸",
            "馬尿酸",
            "2．5ﾍｷｻﾝｼﾞｵﾝ",
            "肺活量",
            "握力",
            "ホルムアルデヒド",
            "じん肺",
            "鉛",
            "電離",
            "インジウム",          
        ];
        $this->assertEquals($expected,$result->toArray()); 
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ExamArea;
use App\SpecialItem;

class SelectItem extends Model
{


    protected $fillable = [
        'reserve_info_id',       
        'height',                
        'weight',                
        'bodyfat_ratio',         
        'abdominal_circumference',
        'vision_test',           
        'hearing_test',          
        'hearing_test_conv',     
        'physical_examination',  
        'blood_pressure',        
        'urinary_test',          
        'urinary_test_type',     
        'urinary_sediment',      
        'blood_test',            
        'blood_test_type',       
        'fecaloccult_blood',     
        'electrogram_test',      
        'chest_xray',            
        'stomach_xray',          
        'eye_pressure',          
        'eyeground',             
        'grip_strength',         
        'lung_capacities',       
        'urinary_metabolites',   
        'methyl_hippuric_acid',  
        'n-formylmethylamine',
        'mandelic_acid',      
        'trichloroacetic_acid',
        'hippuric_acid',
        '2,5-hexanedione',
        'delta-aminolevulinic_acid',
        'formaldehyde',
        'dust',
        'lead',
        'ionizing_radiation',
        'Indium',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function reserve_info()
    {
        return $this->belongsTo('App\ReserveInfo');
    }

    //代謝物関連項目、電離放射線、鉛、粉じん、血清インジウム、ホルムアルデヒドについて、検査対象ならば検査済みかチェック
    public static function updateSpecialItems($select_item){

        // 代謝物が検査済みなら関連項目全てにチェック
        if($select_item->urinary_metabolites > 0){
            $metabolite_items = SelectItemInfo::getMetabolitesGroup();

            $metabo_value = $select_item->urinary_metabolites;
            
            foreach ($metabolite_items as $name => $name_jp) {
                if($select_item[$name] > 0){
                    $select_item[$name] = $metabo_value;
                }
            }
        }

        //特殊項目
        $special_items = SpecialItem::getNames();
        
        foreach ($special_items as $key => $special_item) {

            if($select_item[$special_item] > 0){
                $related_items = SpecialItem::getRelatedItems($special_item);
                $result = self::checkItemsComplete($select_item,$related_items);
                if($result){
                    $select_item[$special_item] = 2;
                }else{
                    $select_item[$special_item] = 1;
                    
                }
            }
        }
        // dd($select_item->toArray());

        $select_item->save();

        return $select_item;
    }

    // 検査項目の中の関連項目がすべて受診済かチェックする
    public static function checkItemsComplete($select_item,$related_items){
        $complete = true;
        foreach ($related_items as $key => $related_item) {
            if($select_item[$related_item] == 1){
                $complete = false;
                break;
            }
        }

        return $complete;
    }

    // 進捗度の計算
    public function calcProgress($selectItem){
        $total = 0;
        $complete = 0;
        if(!is_array($selectItem)){
            $selectItem = $selectItem->toArray();
        }

        $targetItems = ExamArea::getColumnNames(); // ['id','reserve_info_id','created_at','updated_at'];

        foreach ($selectItem as $itemName => $itemData) {
            // 対象検査=1,実施済み=2の項目数を計算
            if(!in_array($itemName,$targetItems)){
                continue;
            }
            //  print_r($itemName);
            if($itemData == 2){
                $total += 1;
                $complete += 1;
            }elseif ($itemData == 1) {
                $total += 1;
            }
        }
        if($total == 0){
            return false;
        }
        
        // 実施済み数/検査数を％で返す
        return intval(($complete/$total)*100);
    }

    // 対象項目受診予定の健診IDリストを返す
    public static function getTestTargetIds($area_items){
        if(empty($area_items)){
            return false;
        }

        $query = self::query();

        foreach ($area_items as $key => $itemName) {
            $query->orWhere($itemName,'>',0);
            // $query->where($itemName,'>',0);
        }

        return $query->get()->pluck('reserve_info_id')->toArray();

    }


    // 対象項目受診予定の検査対象者と完了数を返す
    public static function getTestTargetCounts($area_name, $area_items, $reception_id){
        if(empty($area_items)){
            return false;
        }

        //受付済みの対象者総数
        $query = self::query();
        $query->whereHas('reserve_info', function($subquery) use ($reception_id) {
            $subquery->where('check_in',true)->where('reception_list_id', $reception_id);
        });        

        $query->where(function($subquery) use ($area_items){
            foreach ($area_items as $key => $itemName) {
                $subquery->orWhere($itemName,'>',0);
            }
        });


        $total = $query->count();

        if($total == 0){
            return [$area_name.'完了数'=>0, $area_name.'対象者数'=>0];
        }

        //対象者のうち検査完了数
        $query->where(function($subquery) use ($area_items){
            foreach ($area_items as $key => $itemName) {
                $subquery->where($itemName,'!=',1);
            }
        });

        $complete = $query->count();

        return [$area_name.'完了数'=>$complete, $area_name.'対象者数'=>$total];

    }
}

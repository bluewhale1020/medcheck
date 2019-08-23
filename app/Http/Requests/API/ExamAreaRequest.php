<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class ExamAreaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required|string|max:25',
            'exam_category_id'=>'required|integer',
            'height'=>'in:0,1',
            'weight'=>'in:0,1',
            'bodyfat_ratio'=>'in:0,1',
            'abdominal_circumference'=>'in:0,1',
            'vision_test'=>'in:0,1',
            'hearing_test'=>'in:0,1',
            'hearing_test_conv'=>'in:0,1',
            'physical_examination'=>'in:0,1',
            'blood_pressure'=>'in:0,1',
            'urinary_test'=>'in:0,1',
            'urinary_sediment'=>'in:0,1',
            'blood_test'=>'in:0,1',
            'fecaloccult_blood'=>'in:0,1',
            'electrogram_test'=>'in:0,1',
            'chest_xray'=>'in:0,1',
            'stomach_xray'=>'in:0,1',
            'eye_pressure'=>'in:0,1',
            'eyeground'=>'in:0,1',
            'grip_strength'=>'in:0,1',
            'lung_capacities'=>'in:0,1',
            'urinary_metabolites'=>'in:0,1',
            'file'=>'sometimes|nullable|mimes:jpg,jpeg,png,bmp',
        ];
    }

    public function messages()
    {
    return [
        "required"=>"必須項目です。",
        "string"=>"文字列を入力してください。",    
        "max"=>"25文字以内で入力してください。",    
        "mimes" => "指定されたファイルが特定の画像タイプ(jpg,jpeg,png,bmp)ではありません。",

    ];
    }

}
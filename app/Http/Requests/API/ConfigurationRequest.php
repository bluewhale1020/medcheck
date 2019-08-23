<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class ConfigurationRequest extends FormRequest
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
            "reception_list_id"=> "required|integer",
            "first_serial_number"=> "required|integer",            
            "barcode_protocol"=> "required|string",
            "barcode_column_name"=> "required|string|max:45",
            "barcode_column_name2"=> "sometimes|string|max:45",
            "default_barcode_no"=> "required|string|max:45",
            "reception_list_filename"=> "required|string|max:45",
        ];
    }
}

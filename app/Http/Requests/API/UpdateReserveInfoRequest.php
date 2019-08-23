<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReserveInfoRequest extends FormRequest
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
        // reception_list_id | int(11)             | NO   |     | NULL    |                |
        // | checkup_info_id   | int(11)             | YES  |     | NULL    |                |
        // | account_id        | int(11)             | NO   |     | NULL    |                |
        // | serial_number     | int(11)             | YES  |     | NULL    |                |
        // | schedule_date     | datetime            | NO   |     | NULL    |                |
        // | checkup_date      | datetime            | YES  |     | NULL    |                |
        // | course            | varchar(25)         | YES  |     | NULL    |                |
        // | kenpo             | tinyint(1)          | NO   |     | 0       |                | 

        return [
            // 'reception_list_id' => 'required|integer',
            // 'checkup_info_id' => 'required|integer',
            'account_id' => 'required|integer',
            'schedule_date' => 'required|date',
        ];
    }
}

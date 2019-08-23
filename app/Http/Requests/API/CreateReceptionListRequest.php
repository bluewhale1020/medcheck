<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class CreateReceptionListRequest extends FormRequest
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
        // | name                | varchar(45)      | NO   |     | NULL    |                |
        // | import_date         | datetime         | NO   |     | NULL    |                |
        // | expiration_date     | datetime         | YES  |     | NULL    |                |
        // | main_course         | varchar(45)      | YES  |     | NULL    |                |
        // | main_kenpo          | tinyint(1)       | YES  |     | NULL    |                |
        // | first_serial_number | int(11)          | YES  |     | NULL    |                |
        // | last_serial_number  | int(11)          | YES  |     | NULL    |                |
        // | max_serial_number   | int(11)          | YES  |     | NULL          
        return [
            'name' => 'required|string|max:45',
            'import_date' => 'required|date',
        ];
    }
}

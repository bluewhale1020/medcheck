<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountRequest extends FormRequest
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
        // | kana            | varchar(60)         | NO   |     | NULL    |                |
        // | name            | varchar(60)         | NO   |     | NULL    |                |
        // | id_number       | varchar(25)         | YES  |     | NULL    |                |
        // | birthdate       | date                | NO   |     | NULL    |                |
        // | age             | int(11)             | YES  |     | NULL    |                |
        // | sex         
        return [
            'kana' => 'required|string|max:60',
            'name' => 'sometimes|string|max:60',
            'id_number' => 'sometimes|string|max:25',
            'birthdate' => 'required|date',
            'sex' => 'sometimes|in:男,女'
          ];
    }
}

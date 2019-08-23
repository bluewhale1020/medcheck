<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        //'name', 'email', 'password','role_id','online'
        return [
            'name' => 'required|string|max:191',
            'email' => [
              'required', 'max:191','email',
              Rule::unique('users')->ignore($this->user),
          ],
            'password' => 'sometimes|min:4',
            'role_id' => 'required|integer'
          ];
    }

    /**
     * エラーメッセージのカスタマイズ
     * @return array
     */
    public function messages()
    {
      //'name', 'email', 'password','role_id','online'
      return [
        'required' => '必須事項です',
        'string' => '文字列で入力してください',
        'email.unique' => '既にそのメールアドレスは登録されています。',
        'password.min' => '4文字以上で入力してください',
        'email'  => 'emailの書式で入力してください',
        'max'  => '191文字以内で入力してください',
        'role_id.integer'  => '役柄はID番号で選択',
      ];
    }
}

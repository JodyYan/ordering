<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Bossupdate extends FormRequest
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
            'account'=>['between:1,20', 'string', 'unique:bosses,account'],
            'name'=>['between:1,20', 'string', 'unique:bosses,name'],
            'password'=>['between:1,15', 'string']
        ];
    }
}

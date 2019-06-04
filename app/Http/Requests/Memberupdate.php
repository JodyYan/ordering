<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Memberupdate extends FormRequest
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
            'name'=>['between:1,20', 'string'],
            'account'=>['between:1,15', 'string', 'unique:members,account'],
            'password'=>['between:1,15', 'string'],
            'rice'=>['integer', 'between:1,6'],
            'vegetable'=>['integer', 'between:1,4'],
            'group_id'=>['exists:groups,id'],
            'note'=>['max:30']
        ];
    }
}

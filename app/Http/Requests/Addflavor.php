<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Addflavor extends FormRequest
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
            'flavorArray' => ['required', 'array'],
            'flavorArray.*' => ['array'],
            'flavorArray.*.menu_id' => ['required', 'exists:menus,id'],
            'flavorArray.*.choice' => ['required', 'string']
        ];
    }
}

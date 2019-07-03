<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Orderdata extends FormRequest
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
            'menuArray' => ['array'],
            'menuArray.*' => ['array'],
            'menuArray.*.menu_id' => ['required', 'exists:menus,id'],
            'menuArray.*.quantity' => ['required', 'integer'],
            'menuArray.*.flavor_id' => ['exists:flavors,id'],
            'menuArray.*.note' => ['string', 'max:30'],
            'menuArray.*.user_rice' => ['integer', 'between:1,7'],
            'menuArray.*.user_vegetable' => ['integer', 'between:1,4']
        ];
    }
}

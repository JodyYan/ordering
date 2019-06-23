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
            'menu_id' => ['required', 'exists:menus,id'],
            'quantity' => ['required', 'integer'],
            'flavor_id' => ['exists:flavors,id'],
            'note' => ['string', 'max:30'],
            'user_rice' => ['integer', 'between:1,7'],
            'user_vegetable' => ['integer', 'between:1,4']
        ];
    }
}

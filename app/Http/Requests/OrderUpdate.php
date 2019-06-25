<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderUpdate extends FormRequest
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
            'quantity' => ['integer', 'min:1'],
            'flavor_id' => ['exists:flavors,id'],
            'note' => ['string', 'max:30'],
            'user_rice' => ['integer', 'between:1,7'],
            'user_vegetable' => ['integer', 'between:1,4']
        ];
    }
}

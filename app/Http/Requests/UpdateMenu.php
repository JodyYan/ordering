<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenu extends FormRequest
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
            'name' => ['string'],
            'price' => ['integer', 'min:0'],
            'group_id' => ['exists:groups,id'],
            'quantity_limit' => ['integer', 'min:0'],
            'note' => ['string', 'max:30'],
            'menu_date' => ['date'],
            'type' => ['boolean']
        ];
    }
}

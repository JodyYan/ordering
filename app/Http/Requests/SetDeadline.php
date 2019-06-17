<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetDeadline extends FormRequest
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
            'which_date' => ['required', 'date'],
            'deadline_time' => ['required', 'date_format:H:i'],
            'group_id' => ['required', 'exists:groups,id']
        ];
    }
}

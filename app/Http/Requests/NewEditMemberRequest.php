<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewEditMemberRequest extends FormRequest
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
            'name' => 'required|max:50',
            'address' => 'max:250',
            'phone' => 'max:50',
            'sex' => 'required|in:M,F',
            'joined_at' => 'required|date',
            'birthday' => 'nullable|date',
            'oib' => 'max:50',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganizerRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|unique:organizers|max:14',
            'password' => 'required|string|max:255',
            'address' => 'required|string|max:255'
        ];
    }
    public function messages()
        {
            return [
                'required' => ':attribute  is required',
                'unique' => ':attribute is already used',
            ];
        }
}

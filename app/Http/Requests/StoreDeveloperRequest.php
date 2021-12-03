<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeveloperRequest extends FormRequest
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
            'first_name' => ['required','min:2','max:255'],
            'last_name' => ['required','min:2','max:255'],
            'email' => ['required','email','unique:developers,email'],
            'phone_number' => ['required','numeric','digits_between:10,15','unique:developers,phone_number'],
            'address' => ['required','min:5','max:255'],
            'avatar' => ['required','image','max:1024']
        ];
    }
}

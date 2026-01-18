<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubjetcRequest extends FormRequest
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
            'Name_en' => 'required',
            'Name_ar' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'Name_en.required' => 'اكتب الاسم بالانجليزي',
            'Name_ar.required' => 'اكتب الاسم بالعربي',
        ];
    }
}

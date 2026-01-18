<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExammRequest extends FormRequest
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
            'Name_Exam_Ar' => ['required'],
            'Name_Exam_En' => ['required'],
            'full_mark' => ['required'],
            'subject_id' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'Name.required' => trans('validation.required'),
            'Name_Exam_En.required' => trans('validation.required'),
            'full_mark.required' => trans('validation.required'),
            'subject_id.required' => trans('validation.required'),
        ];
    }
}

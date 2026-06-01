<?php

namespace App\Http\Requests\Applicant\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateApplicantProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            /*
            | Personal Information
            */

            'birth_place' => [
                'required',
                'string',
                'max:255',
            ],

            'birth_date' => [
                'required',
                'date',
            ],

            'gender' => [
                'required',
                Rule::in([
                    'male',
                    'female',
                ]),
            ],

            'marital_status' => [
                'required',
                Rule::in([
                    'single',
                    'married',
                    'divorced',
                ]),
            ],

            'nationality' => [
                'required',
                'string',
                'max:100',
            ],

            'postal_code' => [
                'nullable',
                'string',
                'max:20',
            ],

            /*
            | Education Information
            */

            'last_education' => [
                'required',
                'string',
                'max:255',
            ],

            'institution_name' => [
                'required',
                'string',
                'max:255',
            ],

            'study_program' => [
                'nullable',
                'string',
                'max:255',
            ],

            'graduation_year' => [
                'required',
                'integer',
                'min:1950',
                'max:' . date('Y'),
            ],
        ];
    }
}
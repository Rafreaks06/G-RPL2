<?php

namespace App\Http\Requests\Applicant\Application;

use App\Enums\RplType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [

            'study_program_id' => [
                'required',
                'exists:study_programs,id',
            ],

            'rpl_type' => [
                'required',
                Rule::in(
                    RplType::ALL
                ),
            ],
        ];
    }
}
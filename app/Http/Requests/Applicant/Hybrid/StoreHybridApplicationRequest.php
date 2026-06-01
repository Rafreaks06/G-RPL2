<?php

namespace App\Http\Requests\Applicant\Hybrid;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Enums\RplType;

class StoreHybridApplicationRequest extends FormRequest
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

            /*
            | Application Header
            */

            'study_program_id' => [
                'required',
                'exists:study_programs,id',
            ],

            'rpl_type' => [
                'required',
                Rule::in([
                    RplType::HYBRID,
                ]),
            ],
        ];
    }
}
<?php

namespace App\Http\Requests\Staff\Submission;

use Illuminate\Foundation\Http\FormRequest;

class ReturnSubmissionRequest extends FormRequest
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

            'review_notes' => [

                'required',

                'string',

                'max:1000',
            ],
        ];
    }
}
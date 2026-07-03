<?php

namespace App\Http\Requests\Assessor\CourseMapping;

use App\Enums\CourseGrade;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateAssessmentCourseMappingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'course_id' => [

                'nullable',
                'integer',
                'exists:courses,id',
                'required_if:is_recognized,true',
            ],

            'is_recognized' => [

                'required',
                'boolean',
            ],

            'grade' => [

                'nullable',
                new Enum(CourseGrade::class),
                'required_if:is_recognized,true',
                'prohibited_if:is_recognized,false',
            ],

            'notes' => [

                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [

            'course_id.required_if'
                => 'Target course is required when mapping is recognized.',

            'grade.required_if'
                => 'Grade is required when mapping is recognized.',

            'grade.prohibited_if'
                => 'Grade must not be filled when mapping is not recognized.',
        ];
    }
}
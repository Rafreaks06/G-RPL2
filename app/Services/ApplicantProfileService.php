<?php

namespace App\Services;

use App\Models\Applicant;

class ApplicantProfileService
{
    /*
    | Get Applicant Profile
    */

    public function show(
        Applicant $applicant
    ): Applicant {

        return $applicant
            ->load(
                'user'
            );
    }

    /*
    | Update Applicant Profile
    */

    public function update(
        Applicant $applicant,
        array $data
    ): Applicant {

        $applicant->update([

            /*
            | Personal Information
            */

            'birth_place'
                => $data['birth_place'],

            'birth_date'
                => $data['birth_date'],

            'gender'
                => $data['gender'],

            'marital_status'
                => $data['marital_status'],

            'nationality'
                => $data['nationality'],

            'postal_code'
                => $data['postal_code']
                    ?? null,

            /*
            | Education Information
            */

            'last_education'
                => $data['last_education'],

            'institution_name'
                => $data['institution_name'],

            'study_program'
                => $data['study_program']
                    ?? null,

            'graduation_year'
                => $data['graduation_year'],
        ]);

        return $applicant
            ->fresh()
            ->load(
                'user'
            );
    }
}
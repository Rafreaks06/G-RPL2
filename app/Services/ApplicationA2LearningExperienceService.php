<?php

namespace App\Services;

use App\Enums\ApplicationStatus;

use App\Models\Application;
use App\Models\Applicant;
use App\Models\ApplicationA2LearningExperience;

use Illuminate\Database\Eloquent\Collection;

class ApplicationA2LearningExperienceService
{
    /**
     * Get all learning experiences.
     */
    public function list(
        int $applicationId,
        Applicant $applicant
    ): Collection {

        $application = $this->getApplication(
            $applicationId,
            $applicant
        );

        return $application
            ->a2LearningExperiences()
            ->latest()
            ->get();
    }

    /**
     * Get learning experience detail.
     */
    public function getById(
        int $applicationId,
        int $experienceId,
        Applicant $applicant
    ): ApplicationA2LearningExperience {

        $this->getApplication(
            $applicationId,
            $applicant
        );

        return ApplicationA2LearningExperience::query()

            ->where(
                'application_id',
                $applicationId
            )

            ->findOrFail(
                $experienceId
            );
    }

    /**
     * Create learning experience.
     */
    public function create(
        int $applicationId,
        Applicant $applicant,
        array $data
    ): ApplicationA2LearningExperience {

        $application = $this->validateEditableApplication(
            $applicationId,
            $applicant
        );

        return ApplicationA2LearningExperience::create([

            'application_id'
                => $application->id,

            'title'
                => $data['title'],

            'experience_type'
                => $data['experience_type'],

            'organization_name'
                => $data['organization_name'],

            'start_date'
                => $data['start_date']
                    ?? null,

            'end_date'
                => $data['end_date']
                    ?? null,

            'is_ongoing'
                => $data['is_ongoing'],

            'description'
                => $data['description'],
        ]);
    }

    /**
     * Update learning experience.
     */
    public function update(
        int $applicationId,
        int $experienceId,
        Applicant $applicant,
        array $data
    ): ApplicationA2LearningExperience {

        $this->validateEditableApplication(
            $applicationId,
            $applicant
        );

        $experience = $this->getById(
            $applicationId,
            $experienceId,
            $applicant
        );

        $experience->update([

            'title'
                => $data['title'],

            'experience_type'
                => $data['experience_type'],

            'organization_name'
                => $data['organization_name'],

            'start_date'
                => $data['start_date']
                    ?? null,

            'end_date'
                => $data['end_date']
                    ?? null,

            'is_ongoing'
                => $data['is_ongoing'],

            'description'
                => $data['description'],
        ]);

        return $experience->fresh();
    }

    /**
     * Validate editable application.
     */
    private function validateEditableApplication(
        int $applicationId,
        Applicant $applicant
    ): Application {

        $application = $this->getApplication(
            $applicationId,
            $applicant
        );

        if (
            !in_array(
                $application->status,
                [
                    ApplicationStatus::DRAFT,
                    ApplicationStatus::RETURNED,
                ]
            )
        ) {

            abort(
                422,
                'Application cannot be modified.'
            );
        }

        return $application;
    }

    /**
     * Get applicant application.
     */
    private function getApplication(
        int $applicationId,
        Applicant $applicant
    ): Application {

        return Application::query()

            ->where(
                'applicant_id',
                $applicant->id
            )

            ->findOrFail(
                $applicationId
            );
    }
}
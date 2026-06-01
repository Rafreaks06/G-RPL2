<?php

namespace App\Services;

use App\Enums\ApplicationStatus;
use App\Enums\RplType;

use App\Helpers\Applicant\ApplicationSubmissionHelper;

use App\Models\Application;
use App\Models\Applicant;
use App\Models\StudyProgram;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class ApplicationService
{
    public function __construct(
        protected ApplicationSubmissionHelper $submissionHelper
    ) {}

    /**
     * Get all applications.
     */
    public function list(
        Applicant $applicant,
        array $filters = []
    ): LengthAwarePaginator {

        return Application::query()

            ->with([
                'studyProgram',
            ])

            ->where(
                'applicant_id',
                $applicant->id
            )

            ->latest()

            ->paginate(
                $filters['per_page']
                    ?? 10
            );
    }

    /**
     * Get application detail.
     */
    public function getById(
        int $id,
        Applicant $applicant
    ): Application {

        return Application::with([
            'studyProgram',
        ])
            ->where(
                'applicant_id',
                $applicant->id
            )
            ->findOrFail($id);
    }

    /**
     * Create application.
     */
    public function create(
        Applicant $applicant,
        array $data
    ): Application {

        /*
        | Validate Applicant Profile
        */

        $this->validateApplicantProfile(
            $applicant
        );

        /*
        | Only One Active Application
        */

        $existingApplication = Application::query()

            ->where(
                'applicant_id',
                $applicant->id
            )

            ->whereIn(
                'status',
                [
                    ApplicationStatus::DRAFT,
                    ApplicationStatus::SUBMITTED,
                    ApplicationStatus::UNDER_REVIEW,
                    ApplicationStatus::RETURNED,
                    ApplicationStatus::UNDER_ASSESSMENT,
                    ApplicationStatus::ASSESSED,
                ]
            )

            ->exists();

        if ($existingApplication) {

            abort(
                422,
                'You already have an active application.'
            );
        }

        /*
        | Validate Study Program Support
        */

        $this->validateRplTypeSupport(
            $data['study_program_id'],
            $data['rpl_type']
        );

        $application = Application::create([

            'application_number'
                => $this->generateApplicationNumber(),

            'applicant_id'
                => $applicant->id,

            'study_program_id'
                => $data['study_program_id'],

            'rpl_type'
                => $data['rpl_type'],

            'status'
                => ApplicationStatus::DRAFT,

            'revision_count'
                => 0,
        ]);

        return $this->getById(
            $application->id,
            $applicant
        );
    }

    /**
     * Update application.
     */
    public function update(
        int $id,
        Applicant $applicant,
        array $data
    ): Application {

        $application = $this->getById(
            $id,
            $applicant
        );

        /*
        | Only Draft Or Returned
        */

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
                'Application cannot be updated.'
            );
        }

        /*
        | Validate Study Program Support
        */

        $this->validateRplTypeSupport(
            $data['study_program_id'],
            $data['rpl_type']
        );

        $application->update([

            'study_program_id'
                => $data['study_program_id'],

            'rpl_type'
                => $data['rpl_type'],
        ]);

        return $this->getById(
            $application->id,
            $applicant
        );
    }

    /**
     * Submit application.
     */
    public function submit(
        int $id,
        Applicant $applicant
    ): Application {

        $application = $this->getById(
            $id,
            $applicant
        );

        /*
        | Only Draft Or Returned
        */

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
                'Application cannot be submitted.'
            );
        }

        /*
        | Validate Submission Requirements
        */

        $this->submissionHelper
            ->validate(
                $application
            );

        $revisionCount =
            $application->revision_count;

        if (
            $application->status ===
            ApplicationStatus::RETURNED
        ) {

            $revisionCount++;
        }

        $application->update([

            'status'
                => ApplicationStatus::SUBMITTED,

            'review_notes'
                => null,

            'revision_count'
                => $revisionCount,

            'submitted_at'
                => now(),
        ]);

        return $this->getById(
            $application->id,
            $applicant
        );
    }

    /**
     * Validate applicant profile completeness.
     */
    private function validateApplicantProfile(
        Applicant $applicant
    ): void {

        $requiredFields = [

            'birth_date',
            'last_education',
            'graduation_year',
        ];

        foreach (
            $requiredFields as $field
        ) {

            if (
                empty(
                    $applicant->{$field}
                )
            ) {

                abort(
                    422,
                    'Please complete your profile before creating an application.'
                );
            }
        }
    }

    /**
     * Validate study program support.
     */
    private function validateRplTypeSupport(
        int $studyProgramId,
        string $rplType
    ): void {

        $studyProgram = StudyProgram::findOrFail(
            $studyProgramId
        );

        switch ($rplType) {

            case RplType::A1:

                if (
                    !$studyProgram->supports_a1
                ) {

                    abort(
                        422,
                        'Selected study program does not support RPL A1.'
                    );
                }

                break;

            case RplType::A2:

                if (
                    !$studyProgram->supports_a2
                ) {

                    abort(
                        422,
                        'Selected study program does not support RPL A2.'
                    );
                }

                break;

            case RplType::HYBRID:

                if (
                    !$studyProgram->is_hybrid_allowed
                ) {

                    abort(
                        422,
                        'Selected study program does not support Hybrid RPL.'
                    );
                }

                break;
        }
    }

    /**
     * Generate application number.
     */
    private function generateApplicationNumber(): string
    {
        return sprintf(
            'RPL-%s-%s',
            now()->format('Y'),
            strtoupper(
                Str::random(6)
            )
        );
    }
}
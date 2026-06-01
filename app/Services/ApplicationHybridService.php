<?php

namespace App\Services;

use App\Enums\ApplicationStatus;
use App\Enums\RplType;

use App\Models\Application;
use App\Models\Applicant;
use App\Models\StudyProgram;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApplicationHybridService
{
    /**
     * Get all hybrid applications.
     */
    public function index(
        Applicant $applicant
    ) {
        return Application::query()

            ->with([
                'studyProgram',
            ])

            ->where(
                'applicant_id',
                $applicant->id
            )

            ->where(
                'rpl_type',
                RplType::HYBRID
            )

            ->latest()

            ->get();
    }

    /**
     * Get hybrid detail.
     */
    public function show(
        int $applicationId,
        Applicant $applicant
    ): Application {

        return Application::query()

            ->with([
                'studyProgram',
                'a1Courses',
                'a2LearningExperiences',
                'documents',
            ])

            ->where(
                'applicant_id',
                $applicant->id
            )

            ->where(
                'rpl_type',
                RplType::HYBRID
            )

            ->findOrFail(
                $applicationId
            );
    }

    /**
     * Create hybrid application.
     */
    public function store(
        Applicant $applicant,
        array $data
    ): Application {

        /*
        | Validate Applicant Profile
        */

        $this->validateApplicantProfile(
            $applicant
        );

        $this->validateStudyProgramSupport(
            $data['study_program_id']
        );

        return DB::transaction(
            function () use (
                $applicant,
                $data
            ) {

                $application =
                    Application::create([

                        'application_number'
                            => $this->generateApplicationNumber(),

                        'applicant_id'
                            => $applicant->id,

                        'study_program_id'
                            => $data['study_program_id'],

                        'rpl_type'
                            => RplType::HYBRID,

                        'status'
                            => ApplicationStatus::DRAFT,

                        'revision_count'
                            => 0,
                    ]);

                return $application->load([
                    'studyProgram',
                ]);
            }
        );
    }

    /**
     * Update hybrid application.
     */
    public function update(
        int $applicationId,
        Applicant $applicant,
        array $data
    ): Application {

        $application =
            $this->validateEditableApplication(
                $applicationId,
                $applicant
            );

        $this->validateStudyProgramSupport(
            $data['study_program_id']
        );

        $application->update([

            'study_program_id'
                => $data['study_program_id'],
        ]);

        return $application->load([
            'studyProgram',
            'a1Courses',
            'a2LearningExperiences',
            'documents',
        ]);
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
     * Validate editable application.
     */
    private function validateEditableApplication(
        int $applicationId,
        Applicant $applicant
    ): Application {

        $application =
            Application::query()

                ->where(
                    'applicant_id',
                    $applicant->id
                )

                ->where(
                    'rpl_type',
                    RplType::HYBRID
                )

                ->findOrFail(
                    $applicationId
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
     * Validate hybrid support.
     */
    private function validateStudyProgramSupport(
        int $studyProgramId
    ): void {

        $studyProgram =
            StudyProgram::findOrFail(
                $studyProgramId
            );

        if (
            !$studyProgram->is_hybrid_allowed
        ) {

            abort(
                422,
                'Selected study program does not support Hybrid RPL.'
            );
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
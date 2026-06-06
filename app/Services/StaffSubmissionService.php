<?php

namespace App\Services;
use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class StaffSubmissionService
{
    /*
    | Get All Submissions
    */

    public function list(): Collection
    {
        return Application::query()

            ->whereIn(
                'status',
                [
                    ApplicationStatus::SUBMITTED,
                    ApplicationStatus::UNDER_REVIEW,
                    ApplicationStatus::RETURNED,
                ]
            )

            ->with([
                'applicant.user',
                'studyProgram',
            ])

            ->latest()

            ->get();
    }

    /*
    | Get Submission Detail
    */

    public function getById(
        int $applicationId
    ): Application {

        return Application::query()

            ->with([
                'applicant.user',
                'studyProgram',
                'a1Courses',
                'a2LearningExperiences',
                'documents',
            ])

            ->findOrFail(
                $applicationId
            );
    }

    /*
    | Start Review
    */

    public function review(
        int $applicationId
    ): Application {

        $application = Application::findOrFail(
            $applicationId
        );

        if (
            $application->status !==
            ApplicationStatus::SUBMITTED
        ) {

            abort(
                422,
                'Only submitted applications can be reviewed.'
            );
        }

        $application->update([

            'status'
                => ApplicationStatus::UNDER_REVIEW,
        ]);

        return $application->fresh();
    }

    /*
    | Return Application
    */

    public function return(
        int $applicationId,
        array $data
    ): Application {

        $application = Application::findOrFail(
            $applicationId
        );

        if (
            $application->status !==
            ApplicationStatus::UNDER_REVIEW
        ) {

            abort(
                422,
                'Only applications under review can be returned.'
            );
        }

        $application->update([

            'status'
                => ApplicationStatus::RETURNED,

            'review_notes'
                => $data['review_notes'],

            'revision_count'
                => $application->revision_count + 1,
        ]);

        return $application->fresh();
    }

    /*
    | Assign Assessor
    */

    public function assignAssessor(
        int $applicationId,
        array $data
    ): Application {

        $application = Application::findOrFail(
            $applicationId
        );

        if (
            $application->status !==
            ApplicationStatus::UNDER_REVIEW
        ) {

            abort(
                422,
                'Only applications under review can be assigned to an assessor.'
            );
        }

        $assessor = User::findOrFail(
            $data['assessor_id']
        );

        if (
            ! $assessor->hasRole(
                'assessor'
            )
        ) {

            abort(
                422,
                'Selected user is not an assessor.'
            );
        }

        $application->update([

            'assigned_assessor_id'
                => $assessor->id,

            'status'
                => ApplicationStatus::UNDER_ASSESSMENT,
        ]);

        return $application
            ->fresh()
            ->load(
                'assignedAssessor'
            );
    }

    /*
    | Get All Assessors
    */
    public function getAssessors(): Collection
    {
        return User::query()
            ->whereHas('roles',fn ($query) => $query->where('name','assessor'))
            ->with('assessor')
            ->get();
    }

    /*
    | Download Document
    */

    public function downloadDocument(
        int $applicationId,
        int $documentId
    )
    {
        $application = Application::query()

            ->with('documents')

            ->findOrFail(
                $applicationId
            );

        $document = $application
            ->documents()
            ->findOrFail(
                $documentId
            );

        if (
            ! Storage::disk('public')
                ->exists(
                    $document->file_path
                )
        ) {

            abort(
                404,
                'Document file not found.'
            );
        }

        return Storage::disk('public')
            ->download(
                $document->file_path,
                $document->file_name
            );
    }
}
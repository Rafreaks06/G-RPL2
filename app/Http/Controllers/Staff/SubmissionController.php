<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;

use App\Http\Requests\Staff\Submission\ReturnSubmissionRequest;
use App\Http\Requests\Staff\Submission\AssignAssessorRequest;

use App\Services\StaffSubmissionService;

class SubmissionController extends Controller
{
    public function __construct(
        protected StaffSubmissionService $staffSubmissionService
    ) {}

    /*
    | Get All Assessors
    */

    public function assessors()
    {
        return response()->json([
            'success' => true,
            'data'=> $this->staffSubmissionService
                  ->getAssessors(),
        ]);
    }

    /*
    | Get All Submissions
    */

    public function index()
    {
        return response()->json([
            'success' => true,

            'data' => $this->staffSubmissionService
                ->list(),
        ]);
    }

    /*
    | Get Submission Detail
    */

    public function show(
        int $application
    )
    {
        return response()->json([
            'success' => true,

            'data' => $this->staffSubmissionService
                ->getById(
                    $application
                ),
        ]);
    }

    /*
    | Download Document
    */

    public function downloadDocument(
        int $application,
        int $document
    )
    {
        return $this->staffSubmissionService
            ->downloadDocument(
                $application,
                $document
            );
    }

    /*
    | Start Review
    */

    public function review(
        int $application
    )
    {
        return response()->json([

            'success' => true,

            'message'
                => 'Application review started.',

            'data'
                => $this->staffSubmissionService
                    ->review(
                        $application
                    ),
        ]);
    }

    /*
    | Return Application
    */

    public function return(
        ReturnSubmissionRequest $request,
        int $application
    )
    {
        return response()->json([

            'success' => true,

            'message'
                => 'Application returned successfully.',

            'data'
                => $this->staffSubmissionService
                    ->return(
                        $application,
                        $request->validated()
                    ),
        ]);
    }

    /*
    | Assign Assessor
    */

    public function assignAssessor(
        AssignAssessorRequest $request,
        int $application
    )
    {
        return response()->json([

            'success' => true,

            'message'
                => 'Assessor assigned successfully.',

            'data'
                => $this->staffSubmissionService
                    ->assignAssessor(
                        $application,
                        $request->validated()
                    ),
        ]);
    }
}
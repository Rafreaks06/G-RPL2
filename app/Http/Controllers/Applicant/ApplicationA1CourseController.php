<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;

use App\Http\Requests\Applicant\A1\StoreApplicationA1CourseRequest;
use App\Http\Requests\Applicant\A1\UpdateApplicationA1CourseRequest;

use App\Services\ApplicationA1CourseService;

use Illuminate\Http\Request;

class ApplicationA1CourseController extends Controller
{
    public function __construct(
        protected ApplicationA1CourseService $applicationA1CourseService
    ) {}

    /**
     * Get all A1 courses.
     */
    public function index(
        Request $request,
        int $application
    )
    {
        return response()->json([
            'success' => true,

            'data' => $this->applicationA1CourseService->list(
                $application,
                $request->user()->applicant
            ),
        ]);
    }

    /**
     * Get A1 course detail.
     */
    public function show(
        Request $request,
        int $application,
        int $course
    )
    {
        return response()->json([
            'success' => true,

            'data' => $this->applicationA1CourseService->getById(
                $application,
                $course,
                $request->user()->applicant
            ),
        ]);
    }

    /**
     * Create A1 course.
     */
    public function store(
        StoreApplicationA1CourseRequest $request,
        int $application
    )
    {
        return response()->json([
            'success' => true,

            'message'
                => 'A1 course created successfully.',

            'data'
                => $this->applicationA1CourseService->create(
                    $application,
                    $request->user()->applicant,
                    $request->validated()
                ),
        ], 201);
    }

    /**
     * Update A1 course.
     */
    public function update(
        UpdateApplicationA1CourseRequest $request,
        int $application,
        int $course
    )
    {
        return response()->json([
            'success' => true,

            'message'
                => 'A1 course updated successfully.',

            'data'
                => $this->applicationA1CourseService->update(
                    $application,
                    $course,
                    $request->user()->applicant,
                    $request->validated()
                ),
        ]);
    }
}
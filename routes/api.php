<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\StudyProgramController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\CourseManagementController;
use App\Http\Controllers\Applicant\ApplicationController;
use App\Http\Controllers\Applicant\ApplicationA1CourseController;
use App\Http\Controllers\Applicant\ApplicationA2LearningExperienceController;
use App\Http\Controllers\Applicant\ApplicationHybridController;
use App\Http\Controllers\Applicant\ApplicationDocumentController;
use App\Http\Controllers\Applicant\ApplicantProfileController;
use App\Http\Controllers\Staff\SubmissionController;

// -------------------------
// Universal Route for all role
// -------------------------

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/study-programs', [StudyProgramController::class, 'publicIndex']);
});

// -------------------------
// Auth Routes
// -------------------------

Route::prefix('auth')->group(function () {

    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:register');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');

    Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
        $user = User::findOrFail($id);

        if (! hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            return response()->json(['success' => false, 'message' => 'Invalid verification link'], 403);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['success' => true, 'message' => 'Email already verified']);
        }

        $user->markEmailAsVerified();

        return response()->json(['success' => true, 'message' => 'Email verified successfully']);
    })->middleware('signed')->name('verification.verify');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

// -------------------------
// Admin Routes (role: system_admin)
// -------------------------

Route::middleware(['auth:sanctum', 'role:system_admin'])->prefix('admin')->group(function () {

    Route::prefix('study-programs')->group(function () {
        Route::get('/', [StudyProgramController::class, 'index']);
        Route::get('/{studyProgram}', [StudyProgramController::class, 'show']);
        Route::post('/', [StudyProgramController::class, 'store']);
        Route::put('/{studyProgram}', [StudyProgramController::class, 'update']);
    });

    Route::middleware(['throttle:30,1'])->prefix('users')->group(function () {
        Route::get('/', [UserManagementController::class, 'index']);
        Route::get('/{user}', [UserManagementController::class, 'show']);
        Route::post('/', [UserManagementController::class, 'store']);
        Route::put('/{user}', [UserManagementController::class, 'update']);
        Route::patch('/{user}/status', [UserManagementController::class, 'toggleStatus']);
    });

    Route::middleware(['throttle:30,1'])->prefix('courses')->group(function () {
        Route::get('/', [CourseManagementController::class, 'index']);
        Route::get('/{course}', [CourseManagementController::class, 'show']);
        Route::post('/', [CourseManagementController::class, 'store']);
        Route::put('/{course}', [CourseManagementController::class, 'update']);
        Route::patch('/{course}/status', [CourseManagementController::class, 'toggleStatus']);
    });
});

// -------------------------
// Applicant Routes (role: applicant)
// -------------------------

Route::middleware(['auth:sanctum', 'role:applicant'])->prefix('applicant')->group(function () {

    Route::prefix('profile')->group(function () {

        Route::get('/', [ApplicantProfileController::class, 'show',]);
        Route::put('/', [ApplicantProfileController::class, 'update',]);
    });

    Route::middleware(['throttle:30,1'])->prefix('applications')->group(function () {

        Route::get('/', [ApplicationController::class, 'index']);
        Route::post('/', [ApplicationController::class, 'store']);

        Route::prefix('hybrid')->group(function () {
            Route::get('/', [ApplicationHybridController::class, 'index']);
            Route::post('/', [ApplicationHybridController::class, 'store']);
            Route::get('/{application}', [ApplicationHybridController::class, 'show']);
            Route::put('/{application}', [ApplicationHybridController::class, 'update']);
        });

        Route::get('/{application}', [ApplicationController::class, 'show']);
        Route::put('/{application}', [ApplicationController::class, 'update']);
        Route::post('/{application}/submit', [ApplicationController::class, 'submit']);

        Route::prefix('{application}/a1-courses')->group(function () {
            Route::get('/', [ApplicationA1CourseController::class, 'index']);
            Route::get('/{course}', [ApplicationA1CourseController::class, 'show']);
            Route::post('/', [ApplicationA1CourseController::class, 'store']);
            Route::put('/{course}', [ApplicationA1CourseController::class, 'update']);
        });

        Route::prefix('{application}/a2-learning-experiences')->group(function () {
            Route::get('/', [ApplicationA2LearningExperienceController::class, 'index']);
            Route::get('/{experience}', [ApplicationA2LearningExperienceController::class, 'show']);
            Route::post('/', [ApplicationA2LearningExperienceController::class, 'store']);
            Route::put('/{experience}', [ApplicationA2LearningExperienceController::class, 'update']);
        });

        Route::prefix('{application}/documents')->group(function () {
            Route::get('/', [ApplicationDocumentController::class, 'index']);
            Route::get('/{document}', [ApplicationDocumentController::class, 'show']);
            Route::get('/{document}/download', [ApplicationDocumentController::class, 'download']);
            Route::post('/', [ApplicationDocumentController::class, 'store']);
            Route::put('/{document}', [ApplicationDocumentController::class, 'update']);
        });
    });
});

// -------------------------
// Staff Routes (role: staff_rpl)
// -------------------------

Route::middleware([
    'auth:sanctum',
    'role:staff_rpl'
])->prefix('staff')->group(function () {

    Route::get('/assessors', [SubmissionController::class,'assessors',]);
    Route::prefix('submissions')->group(function () {

        Route::get('/', [SubmissionController::class,'index',]);
        Route::get('/{application}', [SubmissionController::class,'show',]);
        Route::patch('/{application}/review', [SubmissionController::class,'review',]);
        Route::patch('/{application}/return', [SubmissionController::class,'return',]);
        Route::patch('/{application}/assign-assessor',[SubmissionController::class,'assignAssessor',]);
        Route::get('/{application}/documents/{document}/download',[SubmissionController::class,'downloadDocument']);
    });
});

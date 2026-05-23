<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\StudyProgramController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\CourseManagementController;

/*
| Authentication Routes
*/

Route::prefix('auth')->group(function () {

    /*
    | Public Routes
    */

    Route::post('/register', [
        AuthController::class,
        'register'
    ])->middleware('throttle:register');

    Route::post('/login', [
        AuthController::class,
        'login'
    ])->middleware('throttle:login');

    /*
    | Email Verification
    */

    Route::get('/email/verify/{id}/{hash}', function (
        Request $request,
        $id,
        $hash
    ) {

        $user = User::findOrFail($id);

        /*
        | Validate Verification Hash
        */

        if (! hash_equals(
            sha1($user->getEmailForVerification()),
            $hash
        )) {

            return response()->json([
                'success' => false,
                'message' => 'Invalid verification link'
            ], 403);
        }

        /*
        | Already Verified
        */

        if ($user->hasVerifiedEmail()) {

            return response()->json([
                'success' => true,
                'message' => 'Email already verified'
            ]);
        }

        /*
        | Verify Email
        */

        $user->markEmailAsVerified();

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully'
        ]);
    })->middleware('signed')
      ->name('verification.verify');

    /*
    | Protected Routes
    */

    Route::middleware('auth:sanctum')->group(function () {

        /*
        | Current User
        */

        Route::get('/me', [
            AuthController::class,
            'me'
        ]);

        /*
        | Logout
        */

        Route::post('/logout', [
            AuthController::class,
            'logout'
        ]);
    });
});

/*
| Admin Routes
*/

Route::middleware([
    'auth:sanctum',
    'role:system_admin'
])->prefix('admin')->group(function () {

    /*
    | Study Programs
    */

    Route::prefix('study-programs')->group(function () {

        /*
        | Get All Study Programs
        */

        Route::get('/', [
            StudyProgramController::class,
            'index'
        ]);

        /*
        | Get Detail Study Program
        */

        Route::get('/{studyProgram}', [
            StudyProgramController::class,
            'show'
        ]);

        /*
        | Create Study Program
        */

        Route::post('/', [
            StudyProgramController::class,
            'store'
        ]);

        /*
        | Update Study Program
        */

        Route::put('/{studyProgram}', [
            StudyProgramController::class,
            'update'
        ]);
    });

    /*
    | User Management
    */

    Route::middleware([
        'throttle:30,1'
    ])->prefix('users')->group(function () {

        /*
        | Get All Users
        */

        Route::get('/', [
            UserManagementController::class,
            'index'
        ]);

        /*
        | Get User Detail
        */

        Route::get('/{user}', [
            UserManagementController::class,
            'show'
        ]);

        /*
        | Create User
        */

        Route::post('/', [
            UserManagementController::class,
            'store'
        ]);

        /*
        | Update User
        */

        Route::put('/{user}', [
            UserManagementController::class,
            'update'
        ]);

        /*
        | Toggle User Status
        */

        Route::patch('/{user}/status', [
            UserManagementController::class,
            'toggleStatus'
        ]);
    });

    /*
    | Course Management
    */

    Route::middleware([
        'throttle:30,1'
    ])->prefix('courses')->group(function () {

        /*
        | Get All Courses
        */

        Route::get('/', [
            CourseManagementController::class,
            'index'
        ]);

        /*
        | Get Course Detail
        */

        Route::get('/{course}', [
            CourseManagementController::class,
            'show'
        ]);

        /*
        | Create Course
        */

        Route::post('/', [
            CourseManagementController::class,
            'store'
        ]);

        /*
        | Update Course
        */

        Route::put('/{course}', [
            CourseManagementController::class,
            'update'
        ]);

        /*
        | Toggle Course Status
        */

        Route::patch('/{course}/status', [
            CourseManagementController::class,
            'toggleStatus'
        ]);
    });
});
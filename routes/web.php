<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/
Route::view('/', 'pages.home');

Route::view('/tentang-rpl', 'pages.about');
Route::view('/persyaratan', 'pages.requirements');
Route::view('/faq', 'pages.faq');
Route::view('/pengumuman', 'pages.announcements');

Route::view('/login', 'pages.auth.login')
    ->name('login');

Route::view('/register', 'pages.auth.register');

/*
|--------------------------------------------------------------------------
| Frontend Static Pages
|--------------------------------------------------------------------------
|
| These pages are static frontend shells. Authentication and role checks are
| handled by resources/js/app.js using the Sanctum token from the auth API.
| Backend API endpoints remain the source of authorization.
|
*/
Route::view('/dashboard', 'pages.dashboard')
    ->name('dashboard');

Route::view('/profile', 'pages.applicant.profile');
Route::view('/profile/edit', 'pages.applicant.profile-edit');

Route::view('/applications', 'pages.applicant.applications');
Route::view('/applications/create', 'pages.applicant.create');
Route::view('/applications/{id}', 'pages.applicant.application-detail');
Route::view('/applications/{id}/edit', 'pages.applicant.application-edit');

Route::view('/assessments', 'pages.assessor.assessments');

Route::view('/approvals', 'pages.committee.approvals');

Route::view('/submissions', 'pages.staff.submissions');
Route::view('/submissions/{id}', 'pages.staff.submission-detail');

Route::view('/admin/master-data', 'pages.admin.master-data');

Route::view('/admin/users', 'pages.admin.users');
Route::view('/admin/users/create', 'pages.admin.users-create');
Route::view('/admin/users/{id}/edit', 'pages.admin.users-edit');

Route::view('/admin/study-programs', 'pages.admin.study-programs');
Route::view('/admin/study-programs/create', 'pages.admin.study-programs-create');
Route::view('/admin/study-programs/{id}/edit', 'pages.admin.study-programs-edit');

Route::view('/admin/courses', 'pages.admin.courses');
Route::view('/admin/courses/create', 'pages.admin.courses-create');
Route::view('/admin/courses/{id}/edit', 'pages.admin.courses-edit');

Route::redirect('/master-data', '/admin/master-data');
Route::redirect('/users', '/admin/users');
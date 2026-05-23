@extends('layouts.app')

@section('title', 'Edit Course - G-RPL2')
@section('page', 'courses-edit')
@section('authRequired', 'true')
@section('roleRequired', 'system_admin')

@section('content')
    <section class="app-shell" data-protected-shell hidden>
        <aside class="sidebar">
            <p class="eyebrow">Admin</p>
            <h1>Edit Course</h1>
            <a class="button button-small button-muted" href="/admin/courses">Back</a>
        </aside>
        <div class="workspace">
            <div class="workspace-header">
                <div>
                    <p class="eyebrow">Course Management</p>
                    <h2>Ubah mata kuliah</h2>
                </div>
                <span class="connection-pill" data-api-status>Connecting</span>
            </div>

            <form class="form-grid" data-course-form="edit">
                <label>Study Programs
                    <select name="study_program_ids" data-study-program-select multiple required></select>
                </label>
                <label>Code<input type="text" name="code" required maxlength="20"></label>
                <label>Name<input type="text" name="name" required maxlength="100"></label>
                <label>Semester<input type="number" name="semester" required min="1" max="14"></label>
                <label>SKS<input type="number" name="sks" required min="1" max="4"></label>
                <label>RPL Type
                    <select name="rpl_type" required>
                        <option value="a1">A1</option>
                        <option value="a2">A2</option>
                        <option value="hybrid">Hybrid</option>
                        <option value="not_supported">Not Supported</option>
                    </select>
                </label>
                <p class="form-message form-grid-full" data-form-message aria-live="polite"></p>
                <button class="button" type="submit" data-submit-button>Update Course</button>
            </form>
        </div>
    </section>
@endsection

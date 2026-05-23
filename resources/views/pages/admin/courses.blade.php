@extends('layouts.app')

@section('title', 'Courses - G-RPL2')
@section('page', 'courses')
@section('authRequired', 'true')
@section('roleRequired', 'system_admin')

@section('content')
    <section class="app-shell" data-protected-shell hidden>
        <aside class="sidebar">
            <p class="eyebrow">Admin</p>
            <h1>Courses</h1>
            <a class="button button-small" href="/admin/courses/create">Create</a>
        </aside>
        <div class="workspace">
            <div class="workspace-header">
                <div>
                    <p class="eyebrow">Course Management</p>
                    <h2>Daftar mata kuliah</h2>
                </div>
                <span class="connection-pill" data-api-status>Connecting</span>
            </div>

            <form class="toolbar" data-admin-filter="courses">
                <input type="search" name="search" placeholder="Cari kode atau nama">
                <select name="study_program_id" data-study-program-filter>
                    <option value="">Semua program studi</option>
                </select>
                <select name="is_active">
                    <option value="">Semua status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
                <button class="button button-small" type="submit">Filter</button>
            </form>

            <p class="form-message" data-page-message aria-live="polite"></p>

            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Program</th>
                            <th>Semester</th>
                            <th>SKS</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody data-courses-body>
                        <tr>
                            <td colspan="8">Memuat mata kuliah...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

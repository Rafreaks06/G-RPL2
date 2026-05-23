@extends('layouts.app')

@section('title', 'Study Programs - G-RPL2')
@section('page', 'study-programs')
@section('authRequired', 'true')
@section('roleRequired', 'system_admin')

@section('content')
    <section class="app-shell" data-protected-shell hidden>
        <aside class="sidebar">
            <p class="eyebrow">Admin</p>
            <h1>Study Programs</h1>
            <a class="button button-small" href="/admin/study-programs/create">Create</a>
        </aside>
        <div class="workspace">
            <div class="workspace-header">
                <div>
                    <p class="eyebrow">Master Data</p>
                    <h2>Daftar program studi</h2>
                </div>
                <span class="connection-pill" data-api-status>Connecting</span>
            </div>
            <p class="form-message" data-page-message aria-live="polite"></p>

            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>SKS</th>
                            <th>RPL</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody data-study-programs-body>
                        <tr>
                            <td colspan="6">Memuat program studi...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

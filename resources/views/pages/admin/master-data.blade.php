@extends('layouts.app')

@section('title', 'Master Data - G-RPL2')
@section('page', 'master-data')
@section('authRequired', 'true')
@section('roleRequired', 'system_admin')

@section('content')
    <section class="app-shell" data-protected-shell hidden>
        <aside class="sidebar">
            <p class="eyebrow">Admin</p>
            <h1>Master Data</h1>
        </aside>
        <div class="workspace">
            <div class="workspace-header">
                <div>
                    <p class="eyebrow">Reference Data</p>
                    <h2>Program dan mata kuliah</h2>
                </div>
                <span class="connection-pill" data-api-status>Connecting</span>
            </div>
            <div class="dashboard-grid">
                <a class="module-card" href="/admin/study-programs">
                    <strong>Study Programs</strong>
                    <span>Kelola program studi, total SKS, dan skema RPL yang didukung.</span>
                </a>
                <a class="module-card" href="/admin/courses">
                    <strong>Courses</strong>
                    <span>Kelola mata kuliah, semester, SKS, dan tipe rekognisi.</span>
                </a>
            </div>
        </div>
    </section>
@endsection

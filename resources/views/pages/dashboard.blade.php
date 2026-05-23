@extends('layouts.app')

@section('title', 'Dashboard - G-RPL2')
@section('page', 'dashboard')
@section('authRequired', 'true')

@section('content')
    <section class="app-shell" data-protected-shell hidden>
        <aside class="sidebar">
            <p class="eyebrow">Signed in</p>
            <h1 data-user-name>Memuat user...</h1>
            <span class="role-badge" data-user-role>Checking</span>
        </aside>

        <div class="workspace">
            <div class="workspace-header">
                <div>
                    <p class="eyebrow">Dashboard</p>
                    <h2>Ringkasan pekerjaan</h2>
                </div>
                <span class="connection-pill" data-api-status>Connecting</span>
            </div>

            <div class="dashboard-grid">
                <a class="module-card" href="/applications" data-role-card="applicant" hidden>
                    <strong>Applications</strong>
                    <span>Daftar dan pengajuan RPL applicant.</span>
                </a>
                <a class="module-card" href="/assessments" data-role-card="assessor" hidden>
                    <strong>Assessments</strong>
                    <span>Penilaian berkas dan evidensi applicant.</span>
                </a>
                <a class="module-card" href="/approvals" data-role-card="committee" hidden>
                    <strong>Approvals</strong>
                    <span>Keputusan dan persetujuan komite.</span>
                </a>
                <a class="module-card" href="/submissions" data-role-card="staff_rpl" hidden>
                    <strong>Submissions</strong>
                    <span>Review administrasi dan kelengkapan.</span>
                </a>
                <a class="module-card" href="/admin/users" data-role-card="system_admin" hidden>
                    <strong>User Management</strong>
                    <span>Pengelolaan akun dan status user.</span>
                </a>
                <a class="module-card" href="/admin/master-data" data-role-card="system_admin" hidden>
                    <strong>Master Data</strong>
                    <span>Data referensi program dan mata kuliah.</span>
                </a>
                <a class="module-card" href="/admin/study-programs" data-role-card="system_admin" hidden>
                    <strong>Study Programs</strong>
                    <span>Pengelolaan program studi dan konfigurasi RPL.</span>
                </a>
                <a class="module-card" href="/admin/courses" data-role-card="system_admin" hidden>
                    <strong>Courses</strong>
                    <span>Pengelolaan mata kuliah dan tipe rekognisi.</span>
                </a>
            </div>
        </div>
    </section>
@endsection

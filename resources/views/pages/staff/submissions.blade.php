@extends('layouts.app')

@section('title', 'Submissions - G-RPL2')
@section('page', 'submissions')
@section('authRequired', 'true')
@section('roleRequired', 'staff_rpl')

@section('content')
    <section class="app-shell" data-protected-shell hidden>
        <aside class="sidebar">
            <p class="eyebrow">Staff</p>
            <h1>Submissions</h1>
        </aside>
        <div class="workspace">
            <div class="workspace-header">
                <div>
                    <p class="eyebrow">Submission Review</p>
                    <h2>Review administrasi</h2>
                </div>
                <span class="connection-pill" data-api-status>Connecting</span>
            </div>
            <div class="empty-state">
                <strong>Daftar submission belum tersedia.</strong>
                <span>Halaman ini sudah diproteksi role staff melalui data user dari backend.</span>
            </div>
        </div>
    </section>
@endsection

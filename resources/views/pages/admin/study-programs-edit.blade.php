@extends('layouts.app')

@section('title', 'Edit Study Program - G-RPL2')
@section('page', 'study-programs-edit')
@section('authRequired', 'true')
@section('roleRequired', 'system_admin')

@section('content')
    <section class="app-shell" data-protected-shell hidden>
        <aside class="sidebar">
            <p class="eyebrow">Admin</p>
            <h1>Edit Study Program</h1>
            <a class="button button-small button-muted" href="/admin/study-programs">Back</a>
        </aside>
        <div class="workspace">
            <div class="workspace-header">
                <div>
                    <p class="eyebrow">Master Data</p>
                    <h2>Ubah program studi</h2>
                </div>
                <span class="connection-pill" data-api-status>Connecting</span>
            </div>
            <form class="form-grid" data-study-program-form="edit">
                <label>Code<input type="text" name="code" required maxlength="20"></label>
                <label>Name<input type="text" name="name" required maxlength="255"></label>
                <label>Total SKS<input type="number" name="total_sks" required min="1"></label>
                <label>Max Convertible SKS<input type="number" name="max_convertible_sks" required min="1"></label>
                <label>Supports A1
                    <select name="supports_a1" required>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </label>
                <label>Supports A2
                    <select name="supports_a2" required>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </label>
                <label>Hybrid Allowed
                    <select name="is_hybrid_allowed" required>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </label>
                <label>Status
                    <select name="status" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </label>
                <p class="form-message form-grid-full" data-form-message aria-live="polite"></p>
                <button class="button" type="submit" data-submit-button>Update Study Program</button>
            </form>
        </div>
    </section>
@endsection

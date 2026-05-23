@extends('layouts.app')

@section('title', 'Create User - G-RPL2')
@section('page', 'users-create')
@section('authRequired', 'true')
@section('roleRequired', 'system_admin')

@section('content')
    <section class="app-shell" data-protected-shell hidden>
        <aside class="sidebar">
            <p class="eyebrow">Admin</p>
            <h1>Create User</h1>
            <a class="button button-small button-muted" href="/admin/users">Back</a>
        </aside>
        <div class="workspace">
            <div class="workspace-header">
                <div>
                    <p class="eyebrow">User Management</p>
                    <h2>Tambah user</h2>
                </div>
                <span class="connection-pill" data-api-status>Connecting</span>
            </div>
            <form class="form-grid" data-admin-user-form="create">
                <label>Name<input type="text" name="name" required maxlength="100"></label>
                <label>Email<input type="email" name="email" required maxlength="50"></label>
                <label>Password<input type="password" name="password" required minlength="8" value="123456"></label>
                <label>Confirm Password<input type="password" name="password_confirmation" required minlength="8" value="123456"></label>
                <label>NIP<input type="text" name="nip" required maxlength="30"></label>
                <label>Phone<input type="text" name="phone" maxlength="20"></label>
                <label>Role
                    <select name="role" required>
                        <option value="assessor">Assessor</option>
                        <option value="staff_rpl">Staff RPL</option>
                        <option value="committee">Committee</option>
                    </select>
                </label>
                <label class="form-grid-full">Address<textarea name="address" rows="3"></textarea></label>
                <p class="form-message form-grid-full" data-form-message aria-live="polite"></p>
                <button class="button" type="submit" data-submit-button>Create User</button>
            </form>
        </div>
    </section>
@endsection

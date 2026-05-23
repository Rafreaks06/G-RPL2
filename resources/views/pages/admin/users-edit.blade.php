@extends('layouts.app')

@section('title', 'Edit User - G-RPL2')
@section('page', 'users-edit')
@section('authRequired', 'true')
@section('roleRequired', 'system_admin')

@section('content')
    <section class="app-shell" data-protected-shell hidden>
        <aside class="sidebar">
            <p class="eyebrow">Admin</p>
            <h1>Edit User</h1>
            <a class="button button-small button-muted" href="/admin/users">Back</a>
        </aside>
        <div class="workspace">
            <div class="workspace-header">
                <div>
                    <p class="eyebrow">User Management</p>
                    <h2>Ubah data user</h2>
                </div>
                <span class="connection-pill" data-api-status>Connecting</span>
            </div>
            <form class="form-grid" data-admin-user-form="edit">
                <label>Name<input type="text" name="name" required maxlength="100"></label>
                <label>Email<input type="email" name="email" required maxlength="50"></label>
                <label>Password<input type="password" name="password" minlength="8" placeholder="Kosongkan jika tidak diubah"></label>
                <label>Confirm Password<input type="password" name="password_confirmation" minlength="8"></label>
                <label>NIP<input type="text" name="nip" required maxlength="30"></label>
                <label>Phone<input type="text" name="phone" maxlength="20"></label>
                <label class="form-grid-full">Address<textarea name="address" rows="3"></textarea></label>
                <p class="form-message form-grid-full" data-form-message aria-live="polite"></p>
                <button class="button" type="submit" data-submit-button>Update User</button>
            </form>
        </div>
    </section>
@endsection

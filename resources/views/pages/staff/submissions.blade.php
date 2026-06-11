@extends('layouts.app')

@section('title', 'Submissions - G-RPL2')
@section('page', 'submissions')
@section('authRequired', 'true')
@section('roleRequired', 'staff_rpl')

@section('content')
<style>
    :root {
        --staff-dark: #0f172a;
        --staff-dark-2: #111827;
        --staff-blue: #2563eb;
        --staff-blue-2: #1d4ed8;
        --staff-blue-soft: #dbeafe;
        --staff-gold: #f59e0b;
        --staff-green: #10b981;
        --staff-red: #ef4444;
        --staff-slate: #64748b;
        --staff-muted: #94a3b8;
        --staff-border: rgba(148, 163, 184, .28);
        --staff-card: rgba(255, 255, 255, .92);
        --staff-shadow: 0 24px 70px rgba(15, 23, 42, .1);
    }

    * {
        box-sizing: border-box;
    }

    .staff-shell {
        min-height: 100vh;
        padding: 40px 22px;
        background:
            radial-gradient(circle at top left, rgba(37, 99, 235, .12), transparent 34%),
            radial-gradient(circle at top right, rgba(245, 158, 11, .13), transparent 32%),
            linear-gradient(rgba(15, 23, 42, .035) 1px, transparent 1px),
            linear-gradient(90deg, rgba(15, 23, 42, .035) 1px, transparent 1px),
            #f6f8fc;
        background-size: auto, auto, 56px 56px, 56px 56px;
    }

    .staff-container {
        width: min(1180px, 100%);
        margin: 0 auto;
    }

    .staff-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        margin-bottom: 24px;
        padding: 18px;
        border: 1px solid var(--staff-border);
        border-radius: 28px;
        background: rgba(255, 255, 255, .84);
        backdrop-filter: blur(18px);
        box-shadow: 0 18px 50px rgba(15, 23, 42, .075);
    }

    .staff-brand {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
    }

    .staff-logo {
        width: 52px;
        height: 52px;
        flex: 0 0 52px;
        border-radius: 18px;
        display: grid;
        place-items: center;
        color: #fff;
        font-size: 14px;
        font-weight: 950;
        letter-spacing: .04em;
        background:
            linear-gradient(135deg, rgba(255,255,255,.2), transparent),
            linear-gradient(135deg, var(--staff-dark), var(--staff-blue) 58%, var(--staff-gold));
        box-shadow: 0 14px 32px rgba(37, 99, 235, .25);
    }

    .staff-brand-text {
        min-width: 0;
    }

    .staff-brand-text small {
        display: block;
        margin-bottom: 4px;
        color: var(--staff-gold);
        font-size: 12px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: .09em;
    }

    .staff-brand-text h1 {
        margin: 0;
        color: var(--staff-dark);
        font-size: 24px;
        line-height: 1.08;
        font-weight: 950;
        letter-spacing: -.045em;
    }

    .staff-brand-text p {
        margin: 6px 0 0;
        color: var(--staff-slate);
        font-size: 13px;
        line-height: 1.45;
    }

    .staff-top-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        flex-wrap: wrap;
    }

    .staff-nav-link,
    .staff-logout-btn {
        min-height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        padding: 10px 16px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 950;
        border: 1px solid rgba(148, 163, 184, .28);
        transition: .2s ease;
        white-space: nowrap;
    }

    .staff-nav-link {
        color: var(--staff-dark);
        background: #fff7ed;
        border-color: rgba(245, 158, 11, .42);
        box-shadow: 0 10px 24px rgba(245, 158, 11, .13);
    }

    .staff-nav-link:hover {
        transform: translateY(-1px);
        box-shadow: 0 14px 30px rgba(245, 158, 11, .18);
    }

    .staff-logout-btn {
        cursor: pointer;
        color: #b91c1c;
        background: #fff;
        border-color: rgba(239, 68, 68, .25);
    }

    .staff-logout-btn:hover {
        color: #fff;
        background: linear-gradient(135deg, #ef4444, #b91c1c);
        border-color: transparent;
        box-shadow: 0 14px 30px rgba(239, 68, 68, .22);
        transform: translateY(-1px);
    }

    .staff-page-card {
        border: 1px solid var(--staff-border);
        border-radius: 32px;
        background: var(--staff-card);
        box-shadow: var(--staff-shadow);
        overflow: hidden;
    }

    .staff-card-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 22px;
        padding: 28px;
        border-bottom: 1px solid rgba(148, 163, 184, .22);
        background:
            linear-gradient(135deg, rgba(255, 255, 255, .94), rgba(248, 250, 252, .76)),
            radial-gradient(circle at top right, rgba(37, 99, 235, .11), transparent 36%);
    }

    .staff-title-group {
        display: flex;
        gap: 15px;
        align-items: flex-start;
        min-width: 0;
    }

    .staff-title-line {
        width: 10px;
        height: 72px;
        flex: 0 0 10px;
        border-radius: 999px;
        background: linear-gradient(180deg, var(--staff-blue), var(--staff-gold));
        box-shadow: 0 10px 22px rgba(37, 99, 235, .18);
    }

    .eyebrow {
        margin: 0 0 7px;
        color: var(--staff-gold);
        font-size: 12px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: .09em;
    }

    .staff-card-header h2 {
        margin: 0;
        color: var(--staff-dark);
        font-size: 32px;
        line-height: 1.12;
        font-weight: 950;
        letter-spacing: -.05em;
    }

    .staff-subtitle {
        max-width: 640px;
        margin: 9px 0 0;
        color: var(--staff-slate);
        font-size: 14px;
        line-height: 1.65;
    }

    .staff-status-wrap {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex: 0 0 auto;
    }

    .connection-pill {
        min-height: 56px;
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 10px 18px 10px 15px;
        border-radius: 999px;
        color: #064e3b;
        background:
            linear-gradient(135deg, rgba(209, 250, 229, .96), rgba(236, 253, 245, .96));
        border: 1px solid rgba(16, 185, 129, .34);
        box-shadow: 0 14px 30px rgba(16, 185, 129, .16);
        white-space: nowrap;
    }

    .connection-pill::before {
        content: "";
        width: 11px;
        height: 11px;
        flex: 0 0 11px;
        border-radius: 999px;
        background: #10b981;
        box-shadow: 0 0 0 6px rgba(16, 185, 129, .17);
    }

    .connection-text {
        display: flex;
        flex-direction: column;
        gap: 3px;
        line-height: 1.1;
    }

    .connection-label {
        color: #047857;
        font-size: 10px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: .09em;
    }

    .connection-value {
        color: #064e3b;
        font-size: 14px;
        font-weight: 950;
        letter-spacing: -.01em;
    }

    .staff-content {
        padding: 26px 28px 30px;
    }

    [data-page-message] {
        margin-bottom: 16px;
    }

    .staff-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
        padding: 16px;
        border: 1px solid rgba(148, 163, 184, .24);
        border-radius: 22px;
        background:
            linear-gradient(135deg, rgba(248, 250, 252, .96), rgba(255, 255, 255, .96));
        box-shadow: 0 12px 30px rgba(15, 23, 42, .045);
    }

    .staff-search-box {
        position: relative;
        flex: 1;
        min-width: 260px;
    }

    .staff-search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 15px;
        pointer-events: none;
    }

    .staff-search-input {
        width: 100%;
        min-height: 48px;
        padding: 12px 15px 12px 43px;
        border-radius: 16px;
        border: 1px solid rgba(148, 163, 184, .32);
        outline: none;
        color: var(--staff-dark);
        background: #fff;
        font-size: 14px;
        font-weight: 750;
        transition: .2s ease;
    }

    .staff-search-input::placeholder {
        color: #94a3b8;
        font-weight: 650;
    }

    .staff-search-input:focus {
        border-color: rgba(37, 99, 235, .45);
        box-shadow: 0 0 0 5px rgba(37, 99, 235, .1);
    }

    .staff-toolbar-note {
        display: flex;
        flex-direction: column;
        gap: 3px;
        flex: 0 0 auto;
        text-align: right;
    }

    .staff-toolbar-note strong {
        color: var(--staff-dark);
        font-size: 13px;
        font-weight: 950;
    }

    .staff-toolbar-note span {
        color: var(--staff-slate);
        font-size: 12px;
        font-weight: 650;
    }

    .table-container {
        overflow: hidden;
        border-radius: 22px;
        border: 1px solid rgba(148, 163, 184, .28);
        background: #fff;
        box-shadow: 0 14px 34px rgba(15, 23, 42, .045);
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead {
        background: linear-gradient(180deg, #f8fafc, #f1f5f9);
    }

    .data-table th {
        padding: 16px;
        color: #64748b;
        font-size: 12px;
        font-weight: 950;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: .045em;
        border-bottom: 1px solid rgba(148, 163, 184, .24);
        white-space: nowrap;
    }

    .data-table td {
        padding: 16px;
        color: #1e293b;
        font-size: 14px;
        line-height: 1.5;
        border-bottom: 1px solid rgba(148, 163, 184, .15);
        vertical-align: middle;
    }

    .data-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .data-table tbody tr {
        transition: .18s ease;
    }

    .data-table tbody tr:hover {
        background: #f8fafc;
    }

    .table-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .button,
    .button-small,
    .button-muted {
        text-decoration: none;
        border: 0;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        font-weight: 900;
        transition: .2s ease;
    }

    .button-small {
        min-height: 34px;
        padding: 8px 12px;
        font-size: 12px;
    }

    .button-muted {
        color: #1d4ed8;
        background: #dbeafe;
        border: 1px solid rgba(37, 99, 235, .18);
    }

    .button-muted:hover {
        color: #fff;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        border-color: transparent;
        box-shadow: 0 12px 24px rgba(37, 99, 235, .2);
        transform: translateY(-1px);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 30px;
        padding: 6px 11px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 950;
        white-space: nowrap;
        background: #eef2ff;
        color: #3730a3;
        border: 1px solid rgba(99, 102, 241, .18);
    }

    .status-badge[data-status="submitted"] {
        color: #92400e;
        background: #fef3c7;
        border-color: rgba(245, 158, 11, .25);
    }

    .status-badge[data-status="under_review"] {
        color: #1d4ed8;
        background: #dbeafe;
        border-color: rgba(37, 99, 235, .22);
    }

    .status-badge[data-status="returned"] {
        color: #b91c1c;
        background: #fee2e2;
        border-color: rgba(239, 68, 68, .22);
    }

    .status-badge[data-status="assigned"] {
        color: #047857;
        background: #d1fae5;
        border-color: rgba(16, 185, 129, .22);
    }

    @media (max-width: 900px) {
        .staff-topbar,
        .staff-card-header,
        .staff-toolbar {
            align-items: stretch;
            flex-direction: column;
        }

        .staff-top-actions,
        .staff-status-wrap {
            justify-content: flex-start;
        }

        .staff-toolbar-note {
            text-align: left;
        }
    }

    @media (max-width: 768px) {
        .staff-shell {
            padding: 18px 14px;
        }

        .staff-topbar {
            border-radius: 22px;
            padding: 16px;
        }

        .staff-brand {
            align-items: flex-start;
        }

        .staff-logo {
            width: 46px;
            height: 46px;
            flex-basis: 46px;
            border-radius: 16px;
        }

        .staff-brand-text h1 {
            font-size: 21px;
        }

        .staff-brand-text p {
            font-size: 12px;
        }

        .staff-top-actions {
            display: grid;
            grid-template-columns: 1fr;
            width: 100%;
        }

        .staff-nav-link,
        .staff-logout-btn {
            width: 100%;
        }

        .staff-page-card {
            border-radius: 24px;
        }

        .staff-card-header {
            padding: 22px 18px;
        }

        .staff-title-line {
            height: 66px;
        }

        .staff-card-header h2 {
            font-size: 25px;
        }

        .staff-subtitle {
            font-size: 13px;
        }

        .staff-status-wrap {
            width: 100%;
        }

        .connection-pill {
            width: 100%;
            justify-content: flex-start;
        }

        .staff-content {
            padding: 18px;
        }

        .staff-toolbar {
            padding: 14px;
            border-radius: 18px;
        }

        .staff-search-box {
            min-width: 100%;
        }

        .table-container {
            overflow-x: auto;
        }

        .data-table {
            min-width: 860px;
        }
    }
</style>

<section class="staff-shell" data-protected-shell hidden>
    <div class="staff-container">

        <header class="staff-topbar">
            <div class="staff-brand">
                <div class="staff-logo">RPL</div>

                <div class="staff-brand-text">
                    <small>Staff Panel</small>
                    <h1>Submission Review</h1>
                    <p>Panel pemeriksaan administrasi pengajuan RPL.</p>
                </div>
            </div>

            <div class="staff-top-actions">
                <a href="/submissions" class="staff-nav-link active">Submissions</a>

                <button type="button" class="staff-logout-btn" data-logout>
                    Logout
                </button>
            </div>
        </header>

        <main class="staff-page-card">
            <div class="staff-card-header">
                <div class="staff-title-group">
                    <span class="staff-title-line"></span>

                    <div>
                        <p class="eyebrow">Submission Review</p>
                        <h2>Review Administrasi</h2>
                        <p class="staff-subtitle">
                            Kelola, periksa, dan tindak lanjuti submission pemohon RPL yang masuk ke sistem.
                            Gunakan pencarian untuk menemukan nomor aplikasi, nama pemohon, program studi, tipe, atau status submission.
                        </p>
                    </div>
                </div>

                <div class="staff-status-wrap">
                    <span class="connection-pill">
                        <span class="connection-text">
                            <span class="connection-value" data-api-status>Connecting</span>
                        </span>
                    </span>
                </div>
            </div>

            <div class="staff-content">
                <div data-page-message></div>

                <div class="staff-toolbar">
                    <div class="staff-search-box">
                        <span class="staff-search-icon">⌕</span>
                        <input
                            type="search"
                            class="staff-search-input"
                            placeholder="Cari submission berdasarkan nomor aplikasi, pemohon, prodi, tipe, status..."
                            autocomplete="off"
                            data-submission-search
                        >
                    </div>

                    <div class="staff-toolbar-note">
                        <strong>Daftar Submission</strong>
                        <span>Data terbaru ditampilkan otomatis dari sistem.</span>
                    </div>
                </div>

                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nomor Aplikasi</th>
                                <th>Pemohon</th>
                                <th>Program Studi</th>
                                <th>Tipe</th>
                                <th>Status</th>
                                <th>Diajukan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody data-submissions-body>
                            <tr>
                                <td colspan="7">Memuat data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

    </div>
</section>
@endsection
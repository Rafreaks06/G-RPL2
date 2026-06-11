@extends('layouts.app')

@section('title', 'Submission Detail - G-RPL2')
@section('page', 'submission-detail')
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
        word-break: break-word;
    }

    .staff-subtitle {
        max-width: 640px;
        margin: 9px 0 0;
        color: var(--staff-slate);
        font-size: 14px;
        line-height: 1.65;
    }

    .header-right {
        display: flex;
        align-items: flex-start;
        justify-content: flex-end;
        gap: 10px;
        flex-wrap: wrap;
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

    .staff-action-btn {
        min-height: 42px;
        border: 0;
        cursor: pointer;
        padding: 10px 15px;
        border-radius: 999px;
        color: #1e293b;
        background: #fff;
        border: 1px solid rgba(148, 163, 184, .3);
        font-size: 13px;
        font-weight: 950;
        transition: .2s ease;
        white-space: nowrap;
    }

    .staff-action-btn:hover {
        color: var(--staff-dark);
        background: #fff7ed;
        border-color: rgba(245, 158, 11, .4);
        box-shadow: 0 12px 26px rgba(245, 158, 11, .14);
        transform: translateY(-1px);
    }

    .staff-action-btn[data-review-application] {
        color: #1d4ed8;
        background: #dbeafe;
        border-color: rgba(37, 99, 235, .2);
    }

    .staff-action-btn[data-review-application]:hover {
        color: #fff;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        border-color: transparent;
        box-shadow: 0 14px 28px rgba(37, 99, 235, .22);
    }

    .staff-action-btn[data-return-application] {
        color: #b45309;
        background: #fef3c7;
        border-color: rgba(245, 158, 11, .24);
    }

    .staff-action-btn[data-return-application]:hover {
        color: #fff;
        background: linear-gradient(135deg, #f59e0b, #b45309);
        border-color: transparent;
        box-shadow: 0 14px 28px rgba(245, 158, 11, .23);
    }

    .staff-action-btn[data-assign-assessor] {
        color: #047857;
        background: #d1fae5;
        border-color: rgba(16, 185, 129, .24);
    }

    .staff-action-btn[data-assign-assessor]:hover {
        color: #fff;
        background: linear-gradient(135deg, #10b981, #047857);
        border-color: transparent;
        box-shadow: 0 14px 28px rgba(16, 185, 129, .23);
    }

    .staff-content {
        padding: 26px 28px 30px;
    }

    [data-page-message] {
        margin-bottom: 16px;
    }

    .detail-panel {
        margin-bottom: 22px;
        padding: 18px;
        border: 1px solid rgba(148, 163, 184, .24);
        border-radius: 24px;
        background:
            linear-gradient(135deg, rgba(248, 250, 252, .95), rgba(255, 255, 255, .95));
        box-shadow: 0 12px 30px rgba(15, 23, 42, .045);
    }

    .detail-panel-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 16px;
    }

    .detail-panel-title h3 {
        margin: 0;
        color: var(--staff-dark);
        font-size: 18px;
        font-weight: 950;
        letter-spacing: -.03em;
    }

    .detail-panel-title p {
        margin: 5px 0 0;
        color: var(--staff-slate);
        font-size: 13px;
        line-height: 1.55;
    }

    .detail-mini-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 34px;
        padding: 7px 12px;
        border-radius: 999px;
        color: #92400e;
        background: #fef3c7;
        border: 1px solid rgba(245, 158, 11, .24);
        font-size: 12px;
        font-weight: 950;
        white-space: nowrap;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .detail-item {
        position: relative;
        min-height: 96px;
        padding: 16px;
        border-radius: 20px;
        border: 1px solid rgba(148, 163, 184, .24);
        background: #fff;
        box-shadow: 0 10px 24px rgba(15, 23, 42, .035);
        overflow: hidden;
    }

    .detail-item::after {
        content: "";
        position: absolute;
        right: -18px;
        top: -18px;
        width: 58px;
        height: 58px;
        border-radius: 999px;
        background: rgba(37, 99, 235, .07);
    }

    .detail-label {
        display: block;
        position: relative;
        z-index: 1;
        color: #64748b;
        font-size: 11px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: .06em;
        margin-bottom: 8px;
    }

    .detail-value {
        display: block;
        position: relative;
        z-index: 1;
        color: #0f172a;
        font-size: 14px;
        font-weight: 850;
        line-height: 1.5;
        word-break: break-word;
    }

    .review-notes-item {
        grid-column: span 2;
    }

    .staff-tabs-panel {
        border: 1px solid rgba(148, 163, 184, .24);
        border-radius: 24px;
        background: #fff;
        box-shadow: 0 12px 30px rgba(15, 23, 42, .045);
        overflow: hidden;
    }

    .tabs {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        padding: 12px;
        border-bottom: 1px solid rgba(148, 163, 184, .18);
        background: linear-gradient(180deg, #f8fafc, #f1f5f9);
    }

    .tab-button {
        border: 0;
        cursor: pointer;
        min-height: 42px;
        padding: 10px 14px;
        border-radius: 14px;
        background: transparent;
        color: #64748b;
        font-size: 13px;
        font-weight: 950;
        transition: .2s ease;
    }

    .tab-button:hover {
        color: #0f172a;
        background: rgba(255, 255, 255, .7);
    }

    .tab-button.active {
        color: #0f172a;
        background: #fff;
        box-shadow: 0 10px 22px rgba(15, 23, 42, .08);
    }

    .tab-content {
        display: none;
        padding: 18px;
    }

    .tab-content.active {
        display: block;
    }

    .table-container {
        overflow: hidden;
        border-radius: 20px;
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

    .button {
        min-height: 42px;
        padding: 10px 15px;
        color: #fff;
        background: linear-gradient(135deg, #2563eb, #0f172a);
        font-size: 14px;
        font-weight: 950;
        box-shadow: 0 12px 24px rgba(37, 99, 235, .18);
    }

    .button:hover {
        transform: translateY(-1px);
        box-shadow: 0 16px 32px rgba(37, 99, 235, .22);
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
        box-shadow: none;
    }

    .button-muted:hover {
        color: #fff;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        border-color: transparent;
        box-shadow: 0 12px 24px rgba(37, 99, 235, .2);
        transform: translateY(-1px);
    }

    .modal {
        position: fixed;
        inset: 0;
        z-index: 999;
        display: grid;
        place-items: center;
        padding: 18px;
        background: rgba(15, 23, 42, .58);
        backdrop-filter: blur(10px);
    }

    .modal[hidden] {
        display: none;
    }

    .modal-content {
        width: min(560px, 100%);
        border-radius: 28px;
        background: #fff;
        box-shadow: 0 30px 90px rgba(15, 23, 42, .32);
        overflow: hidden;
        border: 1px solid rgba(255,255,255,.5);
    }

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 20px 24px;
        border-bottom: 1px solid rgba(148, 163, 184, .22);
        background:
            linear-gradient(135deg, rgba(255, 255, 255, .96), rgba(248, 250, 252, .86));
    }

    .modal-header h3 {
        margin: 0;
        color: #0f172a;
        font-size: 20px;
        font-weight: 950;
        letter-spacing: -.03em;
    }

    .modal-close {
        width: 38px;
        height: 38px;
        border: 0;
        border-radius: 14px;
        background: #f1f5f9;
        color: #0f172a;
        font-size: 24px;
        line-height: 1;
        cursor: pointer;
        transition: .2s ease;
    }

    .modal-close:hover {
        color: #fff;
        background: #ef4444;
    }

    .form-grid label {
        display: grid;
        gap: 8px;
        color: #334155;
        font-size: 14px;
        font-weight: 900;
    }

    .form-grid textarea,
    .form-grid select {
        width: 100%;
        border: 1px solid rgba(148, 163, 184, .35);
        border-radius: 16px;
        padding: 12px 14px;
        color: #0f172a;
        background: #fff;
        outline: none;
        font-size: 14px;
        transition: .2s ease;
    }

    .form-grid textarea:focus,
    .form-grid select:focus {
        border-color: rgba(37, 99, 235, .45);
        box-shadow: 0 0 0 5px rgba(37, 99, 235, .1);
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 16px;
    }

    @media (max-width: 1050px) {
        .detail-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .review-notes-item {
            grid-column: span 2;
        }
    }

    @media (max-width: 900px) {
        .staff-topbar,
        .staff-card-header {
            align-items: stretch;
            flex-direction: column;
        }

        .staff-top-actions,
        .header-right {
            justify-content: flex-start;
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

        .header-right {
            display: grid;
            grid-template-columns: 1fr;
            width: 100%;
        }

        .connection-pill,
        .staff-action-btn {
            width: 100%;
            justify-content: flex-start;
        }

        .staff-action-btn {
            justify-content: center;
        }

        .staff-content {
            padding: 18px;
        }

        .detail-panel {
            padding: 14px;
            border-radius: 20px;
        }

        .detail-panel-header {
            flex-direction: column;
        }

        .detail-grid {
            grid-template-columns: 1fr;
        }

        .review-notes-item {
            grid-column: span 1;
        }

        .tabs {
            display: grid;
            grid-template-columns: 1fr;
        }

        .tab-button {
            width: 100%;
        }

        .tab-content {
            padding: 14px;
        }

        .table-container {
            overflow-x: auto;
        }

        .data-table {
            min-width: 760px;
        }

        .modal-actions {
            flex-direction: column-reverse;
        }

        .modal-actions .button {
            width: 100%;
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
                    <h1 data-submission-title>Submission Detail</h1>
                    <p>Detail pemeriksaan administrasi pengajuan RPL.</p>
                </div>
            </div>

            <div class="staff-top-actions">
                <a href="/submissions" class="staff-nav-link">
                    Back to Submissions
                </a>

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
                        <p class="eyebrow" data-submission-status-badge>Status</p>
                        <h2 data-submission-number>Submission Number</h2>
                        <p class="staff-subtitle">
                            Lihat informasi pemohon, detail pengajuan, dokumen pendukung, serta lakukan proses review administrasi.
                        </p>
                    </div>
                </div>

                <div class="header-right">
                    <span class="connection-pill">
                        <span class="connection-text">
                            <span class="connection-label">API Status</span>
                            <span class="connection-value" data-api-status>Connecting</span>
                        </span>
                    </span>

                    <button class="staff-action-btn" type="button" data-review-application>
                        Review
                    </button>

                    <button class="staff-action-btn" type="button" data-return-application hidden>
                        Kembalikan
                    </button>

                    <button class="staff-action-btn" type="button" data-assign-assessor hidden>
                        Tugaskan Assessor
                    </button>
                </div>
            </div>

            <div class="staff-content">
                <div data-page-message></div>

                <section class="detail-panel">
                    <div class="detail-panel-header">
                        <div class="detail-panel-title">
                            <h3>Informasi Submission</h3>
                            <p>Ringkasan data pemohon dan status administrasi submission.</p>
                        </div>

                        <span class="detail-mini-badge">
                            Administrative Review
                        </span>
                    </div>

                    <div class="detail-grid" data-submission-info>
                        <div class="detail-item">
                            <span class="detail-label">Pemohon</span>
                            <span class="detail-value" data-detail-applicant-name>-</span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Email</span>
                            <span class="detail-value" data-detail-applicant-email>-</span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Program Studi</span>
                            <span class="detail-value" data-detail-study-program>-</span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Tipe RPL</span>
                            <span class="detail-value" data-detail-rpl-type>-</span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Diajukan</span>
                            <span class="detail-value" data-detail-submitted-at>-</span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Revisi</span>
                            <span class="detail-value" data-detail-revision-count>-</span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Assessor Ditugaskan</span>
                            <span class="detail-value" data-detail-assessor>-</span>
                        </div>

                        <div class="detail-item review-notes-item">
                            <span class="detail-label">Review Notes</span>
                            <span class="detail-value" data-detail-review-notes>-</span>
                        </div>
                    </div>
                </section>

                <section class="staff-tabs-panel">
                    <div class="tabs" data-tabs>
                        <button class="tab-button active" data-tab-button="a1-courses" data-rpl-section="a1">
                            A1 Courses
                        </button>

                        <button class="tab-button" data-tab-button="a2-learning-experiences" data-rpl-section="a2">
                            A2 Learning Experiences
                        </button>

                        <button class="tab-button" data-tab-button="documents">
                            Documents
                        </button>
                    </div>

                    <div class="tab-content active" data-tab-content="a1-courses" data-rpl-section="a1">
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Mata Kuliah</th>
                                        <th>SKS</th>
                                        <th>Nilai</th>
                                        <th>Institusi</th>
                                    </tr>
                                </thead>

                                <tbody data-a1-courses-body>
                                    <tr>
                                        <td colspan="5">Memuat data...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-content" data-tab-content="a2-learning-experiences" data-rpl-section="a2">
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Tipe</th>
                                        <th>Organisasi</th>
                                        <th>Periode</th>
                                    </tr>
                                </thead>

                                <tbody data-a2-experiences-body>
                                    <tr>
                                        <td colspan="4">Memuat data...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-content" data-tab-content="documents">
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Nama Dokumen</th>
                                        <th>Jenis</th>
                                        <th>Ukuran</th>
                                        <th>Diunggah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody data-documents-body>
                                    <tr>
                                        <td colspan="5">Memuat data...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

            </div>
        </main>

    </div>
</section>

<div class="modal" data-modal="return-submission" hidden>
    <div class="modal-content">
        <div class="modal-header">
            <h3>Kembalikan Aplikasi</h3>
            <button class="modal-close" type="button" data-close-modal="return-submission">&times;</button>
        </div>

        <div class="form-grid" style="padding:24px;">
            <form data-return-form class="form-grid" style="border:0;background:transparent;box-shadow:none;padding:0;">
                <div class="form-grid-full">
                    <label>
                        Catatan Review
                        <textarea name="review_notes" rows="4" placeholder="Tuliskan alasan pengembalian aplikasi..."></textarea>
                    </label>
                </div>

                <div data-form-message></div>

                <div class="modal-actions">
                    <button class="button button-muted" type="button" data-close-modal="return-submission">
                        Batal
                    </button>

                    <button class="button" type="button" data-submit-return>
                        Kembalikan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" data-modal="assign-assessor" hidden>
    <div class="modal-content">
        <div class="modal-header">
            <h3>Tugaskan Assessor</h3>
            <button class="modal-close" type="button" data-close-modal="assign-assessor">&times;</button>
        </div>

        <div class="form-grid" style="padding:24px;">
            <form data-assign-form class="form-grid" style="border:0;background:transparent;box-shadow:none;padding:0;">
                <div class="form-grid-full">
                    <label>
                        Pilih Assessor
                        <select name="assessor_id" data-assessor-select>
                            <option value="">Memuat assessor...</option>
                        </select>
                    </label>
                </div>

                <div data-form-message></div>

                <div class="modal-actions">
                    <button class="button button-muted" type="button" data-close-modal="assign-assessor">
                        Batal
                    </button>

                    <button class="button" type="button" data-submit-assign>
                        Tugaskan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
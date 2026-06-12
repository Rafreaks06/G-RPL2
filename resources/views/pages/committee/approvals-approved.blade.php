@extends('layouts.app')

@section('title', 'Approved Applications - G-RPL2')
@section('page', 'approvals-approved')
@section('authRequired', 'true')
@section('roleRequired', 'committee')

@section('content')
<style>
    :root {
        --committee-dark: #0f172a;
        --committee-dark-2: #111827;
        --committee-blue: #2563eb;
        --committee-blue-2: #1d4ed8;
        --committee-blue-soft: #dbeafe;
        --committee-gold: #f59e0b;
        --committee-green: #10b981;
        --committee-red: #ef4444;
        --committee-slate: #64748b;
        --committee-muted: #94a3b8;
        --committee-border: rgba(148, 163, 184, .28);
        --committee-card: rgba(255, 255, 255, .92);
        --committee-shadow: 0 24px 70px rgba(15, 23, 42, .1);
    }

    * {
        box-sizing: border-box;
    }

    .committee-workspace {
        min-width: 0;
    }

    .committee-container {
        width: 100%;
        min-width: 0;
    }

    .committee-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        margin-bottom: 24px;
        padding: 18px;
        border: 1px solid var(--committee-border);
        border-radius: 28px;
        background: rgba(255, 255, 255, .84);
        backdrop-filter: blur(18px);
        box-shadow: 0 18px 50px rgba(15, 23, 42, .075);
    }

    .committee-brand {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
    }

    .committee-logo {
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
            linear-gradient(135deg, var(--committee-dark), var(--committee-green) 58%, var(--committee-gold));
        box-shadow: 0 14px 32px rgba(16, 185, 129, .25);
    }

    .committee-brand-text {
        min-width: 0;
    }

    .committee-brand-text small {
        display: block;
        margin-bottom: 4px;
        color: var(--committee-gold);
        font-size: 12px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: .09em;
    }

    .committee-brand-text h1 {
        margin: 0;
        color: var(--committee-dark);
        font-size: 24px;
        line-height: 1.08;
        font-weight: 950;
        letter-spacing: -.045em;
    }

    .committee-brand-text p {
        margin: 6px 0 0;
        color: var(--committee-slate);
        font-size: 13px;
        line-height: 1.45;
    }

    .committee-top-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        flex-wrap: wrap;
        flex: 0 0 auto;
    }

    .connection-pill {
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 0 17px;
        border-radius: 999px;
        border: 1px solid #93c5fd;
        color: #1d4ed8;
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        box-shadow:
            0 12px 28px rgba(15, 23, 42, .08),
            inset 0 1px 0 rgba(255, 255, 255, .65);
        font-size: 13px;
        line-height: 1;
        font-weight: 950;
        white-space: nowrap;
    }

    .connection-pill::before {
        content: "";
        width: 9px;
        height: 9px;
        flex: 0 0 9px;
        border-radius: 999px;
        background: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, .15);
    }

    .connection-pill.is-connected {
        color: #14532d;
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        border-color: #4ade80;
        box-shadow:
            0 12px 28px rgba(34, 197, 94, .16),
            inset 0 1px 0 rgba(255, 255, 255, .72);
    }

    .connection-pill.is-connected::before {
        background: #16a34a;
        box-shadow: 0 0 0 4px rgba(34, 197, 94, .18);
    }

    .connection-pill.is-error,
    .connection-pill.is-disconnected {
        color: #991b1b;
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border-color: #fca5a5;
        box-shadow:
            0 12px 28px rgba(239, 68, 68, .14),
            inset 0 1px 0 rgba(255, 255, 255, .65);
    }

    .connection-pill.is-error::before,
    .connection-pill.is-disconnected::before {
        background: #dc2626;
        box-shadow: 0 0 0 4px rgba(220, 38, 38, .16);
    }

    .committee-page-card {
        border: 1px solid var(--committee-border);
        border-radius: 32px;
        background: var(--committee-card);
        box-shadow: var(--committee-shadow);
        overflow: hidden;
    }

    .committee-card-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 22px;
        padding: 28px;
        border-bottom: 1px solid rgba(148, 163, 184, .22);
        background:
            linear-gradient(135deg, rgba(255, 255, 255, .94), rgba(248, 250, 252, .76)),
            radial-gradient(circle at top right, rgba(16, 185, 129, .13), transparent 36%);
    }

    .committee-title-group {
        display: flex;
        gap: 15px;
        align-items: flex-start;
        min-width: 0;
    }

    .committee-title-line {
        width: 10px;
        height: 72px;
        flex: 0 0 10px;
        border-radius: 999px;
        background: linear-gradient(180deg, var(--committee-green), var(--committee-gold));
        box-shadow: 0 10px 22px rgba(16, 185, 129, .18);
    }

    .eyebrow {
        margin: 0 0 7px;
        color: var(--committee-gold);
        font-size: 12px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: .09em;
    }

    .committee-card-header h2 {
        margin: 0;
        color: var(--committee-dark);
        font-size: 32px;
        line-height: 1.12;
        font-weight: 950;
        letter-spacing: -.05em;
        word-break: break-word;
    }

    .committee-subtitle {
        max-width: 720px;
        margin: 9px 0 0;
        color: var(--committee-slate);
        font-size: 14px;
        line-height: 1.65;
    }

    .committee-content {
        padding: 26px 28px 30px;
    }

    [data-page-message] {
        margin-bottom: 16px;
    }

    .committee-summary-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .committee-summary-card {
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px;
        border: 1px solid rgba(148, 163, 184, .30);
        border-radius: 24px;
        background:
            linear-gradient(135deg, rgba(248, 250, 252, .98), rgba(255, 255, 255, .98));
        box-shadow:
            0 16px 38px rgba(15, 23, 42, .075),
            inset 0 1px 0 rgba(255, 255, 255, .9);
    }

    .committee-summary-card::after {
        content: "";
        position: absolute;
        width: 86px;
        height: 86px;
        right: -42px;
        bottom: -44px;
        border-radius: 999px;
        background: rgba(16, 185, 129, .08);
    }

    .committee-summary-icon {
        width: 52px;
        height: 52px;
        flex: 0 0 52px;
        display: grid;
        place-items: center;
        border-radius: 18px;
        font-size: 22px;
        position: relative;
        z-index: 1;
    }

    .committee-summary-icon.blue {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .committee-summary-icon.green {
        background: #d1fae5;
        color: #047857;
    }

    .committee-summary-icon.gold {
        background: #fef3c7;
        color: #92400e;
    }

    .committee-summary-card div:last-child {
        min-width: 0;
        position: relative;
        z-index: 1;
    }

    .committee-summary-card span {
        display: block;
        color: var(--committee-slate);
        font-size: 13px;
        font-weight: 850;
        margin-bottom: 5px;
    }

    .committee-summary-card strong {
        display: block;
        color: var(--committee-dark);
        font-size: 20px;
        line-height: 1.15;
        font-weight: 950;
        letter-spacing: -.035em;
    }

    .committee-info-panel {
        margin-bottom: 22px;
        padding: 18px;
        border: 1px solid rgba(148, 163, 184, .32);
        border-radius: 24px;
        background:
            linear-gradient(135deg, rgba(248, 250, 252, .98), rgba(255, 255, 255, .98));
        box-shadow:
            0 16px 38px rgba(15, 23, 42, .075),
            inset 0 1px 0 rgba(255, 255, 255, .9);
    }

    .committee-info-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
    }

    .committee-info-header h3 {
        margin: 0;
        color: var(--committee-dark);
        font-size: 18px;
        font-weight: 950;
        letter-spacing: -.03em;
    }

    .committee-info-header p {
        margin: 5px 0 0;
        color: var(--committee-slate);
        font-size: 13px;
        line-height: 1.55;
    }

    .committee-mini-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 34px;
        padding: 7px 12px;
        border-radius: 999px;
        color: #047857;
        background: #d1fae5;
        border: 1px solid rgba(16, 185, 129, .24);
        font-size: 12px;
        font-weight: 950;
        white-space: nowrap;
    }

    .table-container {
        overflow: hidden;
        border-radius: 24px;
        border: 1px solid rgba(148, 163, 184, .30);
        background: #fff;
        box-shadow:
            0 18px 44px rgba(15, 23, 42, .075),
            inset 0 1px 0 rgba(255, 255, 255, .92);
    }

    .table-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        padding: 18px 20px;
        border-bottom: 1px solid rgba(148, 163, 184, .20);
        background: linear-gradient(135deg, rgba(248, 250, 252, .95), rgba(255, 255, 255, .95));
    }

    .table-header h3 {
        margin: 0;
        color: var(--committee-dark);
        font-size: 18px;
        font-weight: 950;
        letter-spacing: -.03em;
    }

    .table-header p {
        margin: 5px 0 0;
        color: var(--committee-slate);
        font-size: 13px;
        line-height: 1.55;
    }

    .committee-table-tools {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        flex-wrap: wrap;
        flex: 0 0 auto;
    }

    .committee-search-wrap {
        position: relative;
        min-width: 360px;
    }

    .committee-search-wrap::before {
        content: "";
        position: absolute;
        left: 14px;
        top: 50%;
        width: 14px;
        height: 14px;
        transform: translateY(-50%);
        border: 2px solid #64748b;
        border-radius: 999px;
        opacity: .72;
        pointer-events: none;
    }

    .committee-search-wrap::after {
        content: "";
        position: absolute;
        left: 27px;
        top: 57%;
        width: 7px;
        height: 2px;
        transform: rotate(45deg);
        border-radius: 999px;
        background: #64748b;
        opacity: .72;
        pointer-events: none;
    }

    .committee-search-input {
        width: 100%;
        min-height: 42px;
        padding: 0 14px 0 40px;
        border-radius: 999px;
        border: 1px solid rgba(148, 163, 184, .34);
        background: #fff;
        color: #0f172a;
        box-shadow:
            0 12px 26px rgba(15, 23, 42, .055),
            inset 0 1px 0 rgba(255, 255, 255, .95);
        font-size: 13px;
        font-weight: 800;
        outline: none;
        transition:
            border-color .2s ease,
            box-shadow .2s ease;
    }

    .committee-search-input::placeholder {
        color: #94a3b8;
        font-weight: 750;
    }

    .committee-search-input:focus {
        border-color: rgba(16, 185, 129, .45);
        box-shadow:
            0 0 0 5px rgba(16, 185, 129, .10),
            0 12px 26px rgba(15, 23, 42, .06),
            inset 0 1px 0 rgba(255, 255, 255, .95);
    }

    .committee-search-count {
        min-height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 12px;
        border-radius: 999px;
        color: #047857;
        background: #d1fae5;
        border: 1px solid rgba(16, 185, 129, .24);
        font-size: 12px;
        font-weight: 950;
        white-space: nowrap;
    }

    .table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        min-width: 980px;
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
        font-weight: 700;
        background: #fff;
    }

    .data-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .data-table tbody tr {
        transition: .18s ease;
    }

    .data-table tbody tr:hover td {
        background: #f8fafc;
    }

    .data-table td[colspan="7"],
    .committee-empty-cell {
        padding: 34px 18px !important;
        color: var(--committee-slate);
        text-align: center;
        font-weight: 850;
    }

    .approved-badge {
        min-height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 950;
        white-space: nowrap;
    }

    .approved-badge.yes {
        color: #047857;
        background: #d1fae5;
        border: 1px solid rgba(16, 185, 129, .24);
    }

    .approved-badge.no {
        color: #b91c1c;
        background: #fee2e2;
        border: 1px solid rgba(239, 68, 68, .25);
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
        color: #047857;
        background: #d1fae5;
        border: 1px solid rgba(16, 185, 129, .24);
        box-shadow: none;
    }

    .button-muted:hover {
        color: #fff;
        background: linear-gradient(135deg, #10b981, #047857);
        border-color: transparent;
        box-shadow: 0 12px 24px rgba(16, 185, 129, .2);
        transform: translateY(-1px);
    }

    @media (max-width: 900px) {
        .committee-topbar,
        .committee-card-header,
        .table-header {
            align-items: stretch;
            flex-direction: column;
        }

        .committee-top-actions {
            justify-content: flex-start;
        }

        .connection-pill {
            width: fit-content;
        }

        .committee-info-header {
            flex-direction: column;
        }

        .committee-table-tools {
            justify-content: flex-start;
            width: 100%;
        }

        .committee-search-wrap {
            min-width: 0;
            width: 100%;
        }

        .committee-summary-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .committee-topbar {
            border-radius: 22px;
            padding: 16px;
        }

        .committee-brand {
            align-items: flex-start;
        }

        .committee-logo {
            width: 46px;
            height: 46px;
            flex-basis: 46px;
            border-radius: 16px;
        }

        .committee-brand-text h1 {
            font-size: 21px;
        }

        .committee-brand-text p {
            font-size: 12px;
        }

        .committee-top-actions {
            display: grid;
            grid-template-columns: 1fr;
            width: 100%;
        }

        .connection-pill {
            width: 100%;
        }

        .committee-page-card {
            border-radius: 24px;
        }

        .committee-card-header {
            padding: 22px 18px;
        }

        .committee-title-line {
            height: 66px;
        }

        .committee-card-header h2 {
            font-size: 25px;
        }

        .committee-subtitle {
            font-size: 13px;
        }

        .committee-content {
            padding: 18px;
        }

        .committee-info-panel,
        .committee-summary-card {
            padding: 14px;
            border-radius: 20px;
        }

        .table-container {
            border-radius: 20px;
        }

        .table-header {
            padding: 16px;
        }

        .committee-table-tools {
            display: grid;
            grid-template-columns: 1fr;
        }

        .committee-search-count {
            width: 100%;
        }

        .data-table {
            min-width: 940px;
        }
    }
</style>

<section class="committee-shell app-shell" data-protected-shell hidden>
    <x-committee-sidebar />

    <div class="committee-workspace workspace">
        <div class="committee-container">

            <header class="committee-topbar">
                <div class="committee-brand">
                    <div class="committee-logo">RPL</div>

                    <div class="committee-brand-text">
                        <small>Committee Panel</small>
                        <h1>Approved Applications</h1>
                        <p>Daftar pengajuan RPL yang sudah mendapatkan approval akhir.</p>
                    </div>
                </div>

                <div class="committee-top-actions">
                    <span class="connection-pill" data-api-status>Connecting</span>
                </div>
            </header>

            <main class="committee-page-card">
                <div class="committee-card-header">
                    <div class="committee-title-group">
                        <span class="committee-title-line"></span>

                        <div>
                            <p class="eyebrow">Approval Board</p>
                            <h2>Pengajuan sudah disetujui</h2>
                            <p class="committee-subtitle">
                                Lihat daftar pengajuan RPL yang sudah lolos proses approval committee.
                                Data pada halaman ini dapat digunakan untuk memeriksa hasil keputusan akhir, catatan review, dan status kelanjutan aplikasi.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="committee-content">
                    <div data-page-message></div>

                    <div class="committee-summary-grid">
                        <div class="committee-summary-card">
                            <div class="committee-summary-icon green">✅</div>
                            <div>
                                <span>Total Disetujui</span>
                                <strong data-total-approved>0</strong>
                            </div>
                        </div>

                        <div class="committee-summary-card">
                            <div class="committee-summary-icon blue">📄</div>
                            <div>
                                <span>Status Pengajuan</span>
                                <strong>Approved</strong>
                            </div>
                        </div>

                        <div class="committee-summary-card">
                            <div class="committee-summary-icon gold">🎓</div>
                            <div>
                                <span>Role Aktif</span>
                                <strong>Committee</strong>
                            </div>
                        </div>
                    </div>

                    <section class="committee-info-panel">
                        <div class="committee-info-header">
                            <div>
                                <h3>Ringkasan Approved Applications</h3>
                                <p>
                                    Semua aplikasi yang sudah disetujui akan tampil pada tabel di bawah.
                                    Gunakan search bar untuk mencari nomor aplikasi, pemohon, program studi, total SKS, catatan, atau status approval.
                                </p>
                            </div>

                            <span class="committee-mini-badge">Approved List</span>
                        </div>
                    </section>

                    <div class="table-container">
                        <div class="table-header">
                            <div>
                                <h3>Data Approved Applications</h3>
                                <p>Daftar applicant yang sudah mendapatkan persetujuan akhir dari committee.</p>
                            </div>

                            <div class="committee-table-tools">
                                <label class="committee-search-wrap" for="committeeApprovedSearch">
                                    <input
                                        type="search"
                                        id="committeeApprovedSearch"
                                        class="committee-search-input"
                                        data-approved-search
                                        placeholder="Cari approved application..."
                                        autocomplete="off"
                                    >
                                </label>

                                <span class="committee-search-count" data-approved-search-count>
                                    0 Data
                                </span>
                            </div>
                        </div>

                        <div class="table-wrap">
                            <table class="data-table" id="approvedTable">
                                <thead>
                                    <tr>
                                        <th>Nomor Aplikasi</th>
                                        <th>Pemohon</th>
                                        <th>Program Studi</th>
                                        <th>Total SKS</th>
                                        <th>Catatan</th>
                                        <th>Disetujui</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody data-approved-body>
                                    <tr>
                                        <td colspan="7" class="committee-empty-cell">
                                            Memuat data...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </main>

        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const apiStatus = document.querySelector('[data-api-status]');
        const searchInput = document.querySelector('[data-approved-search]');
        const searchCount = document.querySelector('[data-approved-search-count]');
        const approvedBody = document.querySelector('[data-approved-body]');
        const totalApproved = document.querySelector('[data-total-approved]');

        function normalizeText(value) {
            return String(value || '').trim().toLowerCase();
        }

        function refreshApiStatusClass() {
            if (!apiStatus) return;

            const text = normalizeText(apiStatus.textContent);

            apiStatus.classList.remove(
                'is-connected',
                'is-connecting',
                'is-error',
                'is-disconnected'
            );

            if (text.includes('connected') && !text.includes('disconnect')) {
                apiStatus.classList.add('is-connected');
                return;
            }

            if (
                text.includes('error') ||
                text.includes('failed') ||
                text.includes('offline') ||
                text.includes('disconnected') ||
                text.includes('gagal')
            ) {
                apiStatus.classList.add('is-error');
                return;
            }

            apiStatus.classList.add('is-connecting');
        }

        function isUtilityRow(row) {
            if (!row) return true;

            const colspanCell = row.querySelector('td[colspan]');
            if (!colspanCell) return false;

            const text = normalizeText(row.textContent);

            return (
                text.includes('memuat') ||
                text.includes('gagal') ||
                text.includes('tidak ada') ||
                text.includes('belum ada') ||
                text.includes('data tidak ditemukan') ||
                text.includes('no data') ||
                text.includes('empty')
            );
        }

        function isDetailElement(element) {
            if (!element) return false;

            const text = normalizeText(element.textContent);
            const href = element.getAttribute ? String(element.getAttribute('href') || '') : '';

            return text.includes('detail') || href.includes('/approvals/');
        }

        function makeBadgeYes() {
            const badge = document.createElement('span');
            badge.className = 'approved-badge yes';
            badge.textContent = 'Iya';
            return badge;
        }

        function makeDash() {
            const span = document.createElement('span');
            span.textContent = '-';
            span.style.color = '#94a3b8';
            span.style.fontWeight = '900';
            return span;
        }

        function fixApprovedRowsOnce() {
            if (!approvedBody) return;

            const rows = Array.from(approvedBody.querySelectorAll('tr'));

            rows.forEach(function (row) {
                if (row.dataset.fixedApproved === 'true') return;
                if (row.hasAttribute('data-search-empty-row')) return;
                if (isUtilityRow(row)) return;

                const cells = Array.from(row.children);

                if (cells.length === 6) {
                    const detail =
                        Array.from(cells[5].querySelectorAll('a, button')).find(isDetailElement) ||
                        Array.from(row.querySelectorAll('a, button')).find(isDetailElement);

                    const catatanCell = document.createElement('td');
                    catatanCell.textContent = '-';

                    const disetujuiCell = document.createElement('td');
                    disetujuiCell.appendChild(makeBadgeYes());

                    const aksiCell = document.createElement('td');

                    if (detail) {
                        aksiCell.appendChild(detail);
                    } else {
                        aksiCell.appendChild(makeDash());
                    }

                    cells[4].replaceWith(catatanCell);
                    cells[5].replaceWith(disetujuiCell);
                    row.appendChild(aksiCell);

                    row.dataset.fixedApproved = 'true';
                    return;
                }

                if (cells.length >= 7) {
                    const catatanCell = cells[4];
                    const disetujuiCell = cells[5];
                    const aksiCell = cells[6];

                    const detailInDisetujui =
                        Array.from(disetujuiCell.querySelectorAll('a, button')).find(isDetailElement);

                    const detailAnywhere =
                        Array.from(row.querySelectorAll('a, button')).find(isDetailElement);

                    if (detailInDisetujui) {
                        aksiCell.innerHTML = '';
                        aksiCell.appendChild(detailInDisetujui);
                    } else if (detailAnywhere && !aksiCell.querySelector('a, button')) {
                        aksiCell.innerHTML = '';
                        aksiCell.appendChild(detailAnywhere);
                    }

                    disetujuiCell.innerHTML = '';
                    disetujuiCell.appendChild(makeBadgeYes());

                    const catatanText = normalizeText(catatanCell.textContent);

                    if (
                        !catatanText ||
                        catatanText === 'null' ||
                        catatanText === 'undefined' ||
                        catatanText.includes('detail') ||
                        /^\d{1,2}\/\d{1,2}\/\d{4}$/.test(catatanText) ||
                        /^\d{4}-\d{1,2}-\d{1,2}$/.test(catatanText)
                    ) {
                        catatanCell.textContent = '-';
                    }

                    if (!aksiCell.querySelector('a, button')) {
                        aksiCell.innerHTML = '';
                        aksiCell.appendChild(makeDash());
                    }

                    row.dataset.fixedApproved = 'true';
                }
            });
        }

        function getDataRows() {
            if (!approvedBody) return [];

            return Array.from(approvedBody.querySelectorAll('tr'))
                .filter(function (row) {
                    return !row.hasAttribute('data-search-empty-row');
                })
                .filter(function (row) {
                    return !isUtilityRow(row);
                });
        }

        function removeSearchEmptyRow() {
            if (!approvedBody) return;

            const emptyRow = approvedBody.querySelector('[data-search-empty-row]');
            if (emptyRow) {
                emptyRow.remove();
            }
        }

        function ensureSearchEmptyRow() {
            if (!approvedBody) return;

            let emptyRow = approvedBody.querySelector('[data-search-empty-row]');

            if (!emptyRow) {
                emptyRow = document.createElement('tr');
                emptyRow.setAttribute('data-search-empty-row', 'true');
                emptyRow.innerHTML = '<td colspan="7">Data tidak ditemukan sesuai pencarian.</td>';
                approvedBody.appendChild(emptyRow);
            }
        }

        function updateTotalRows() {
            const dataRows = getDataRows();

            if (totalApproved) {
                totalApproved.textContent = dataRows.length;
            }

            if (searchCount && (!searchInput || !normalizeText(searchInput.value))) {
                searchCount.textContent = dataRows.length + ' Data';
            }
        }

        function filterApprovedTable() {
            if (!approvedBody) {
                return;
            }

            const query = searchInput ? normalizeText(searchInput.value) : '';
            const dataRows = getDataRows();

            updateTotalRows();

            if (!dataRows.length) {
                removeSearchEmptyRow();

                if (searchCount) {
                    searchCount.textContent = '0 Data';
                }

                return;
            }

            let visibleCount = 0;

            dataRows.forEach(function (row) {
                const text = normalizeText(row.textContent);
                const match = !query || text.includes(query);

                row.hidden = !match;

                if (match) {
                    visibleCount++;
                }
            });

            if (!query) {
                removeSearchEmptyRow();

                if (searchCount) {
                    searchCount.textContent = dataRows.length + ' Data';
                }

                return;
            }

            if (visibleCount === 0) {
                ensureSearchEmptyRow();
            } else {
                removeSearchEmptyRow();
            }

            if (searchCount) {
                searchCount.textContent = visibleCount + ' / ' + dataRows.length + ' Data';
            }
        }

        function runSafeFix() {
            fixApprovedRowsOnce();
            updateTotalRows();
            filterApprovedTable();
        }

        refreshApiStatusClass();

        if (apiStatus) {
            const statusObserver = new MutationObserver(refreshApiStatusClass);
            statusObserver.observe(apiStatus, {
                childList: true,
                subtree: true,
                characterData: true
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', filterApprovedTable);
        }

        let tries = 0;
        const interval = setInterval(function () {
            tries += 1;
            runSafeFix();

            if (tries >= 12) {
                clearInterval(interval);
            }
        }, 400);

        runSafeFix();
    });
</script>
@endsection
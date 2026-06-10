@extends('layouts.app')

@section('title', 'Profil Saya - G-RPL2')
@section('page', 'profile')
@section('authRequired', 'true')
@section('roleRequired', 'applicant')

@section('content')
    <section class="app-shell" data-protected-shell hidden>

        {{-- Sidebar Applicant Blade --}}
        <x-applicant-sidebar />

        <div class="workspace profile-page-workspace">

            {{-- HERO --}}
            <div class="profile-page-hero">
                <div class="profile-page-hero-content">
                    <div>
                        <p class="eyebrow profile-page-eyebrow">Data Diri</p>

                        <h2>Data Profil</h2>

                        <p class="profile-page-subtitle">
                            Pastikan data profil Anda lengkap sebelum membuat atau mengirim pengajuan RPL.
                            Data ini akan digunakan untuk kebutuhan validasi pelamar.
                        </p>
                    </div>

                    <div class="profile-page-header-actions">
                        <span class="connection-pill profile-page-status-pill" data-api-status>
                            Connecting
                        </span>

                        <a href="/profile/edit" class="profile-page-edit-top-btn">
                            <span class="profile-page-edit-top-icon">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25Zm17.71-10.04c.39-.39.39-1.02 0-1.41L18.2 3.29a.9959.9959 0 0 0-1.41 0l-1.96 1.96L18.58 9l2.13-1.79Z"/>
                                </svg>
                            </span>
                            <span>Edit Profil</span>
                        </a>
                    </div>
                </div>

                <div class="profile-page-stats">
                    <div class="profile-page-stat-card">
                        <span class="profile-page-stat-icon profile-page-stat-blue">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 12c2.76 0 5-2.24 5-5S14.76 2 12 2 7 4.24 7 7s2.24 5 5 5Zm0 2c-3.33 0-10 1.67-10 5v3h20v-3c0-3.33-6.67-5-10-5Z"/>
                            </svg>
                        </span>

                        <div>
                            <p>Status Profil</p>
                            <strong data-profile-completeness-badge>-</strong>
                        </div>
                    </div>

                    <div class="profile-page-stat-card">
                        <span class="profile-page-stat-icon profile-page-stat-green">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M9 16.17 4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17Z"/>
                            </svg>
                        </span>

                        <div>
                            <p>Kesiapan</p>
                            <strong>RPL</strong>
                        </div>
                    </div>

                    <div class="profile-page-stat-card">
                        <span class="profile-page-stat-icon profile-page-stat-yellow">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6Zm-1 7V3.5L18.5 9H13ZM8 13h8v2H8v-2Zm0 4h8v2H8v-2Z"/>
                            </svg>
                        </span>

                        <div>
                            <p>Dokumen</p>
                            <strong>Validasi</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="profile-page-panel">
                <div class="profile-page-panel-head">
                    <div>
                        <h3>                        Ringkasan Profil</h3>
                        <p>
                            Data pelamar yang terhubung dengan akun Anda. Lengkapi bagian yang masih kosong
                            supaya proses pengajuan RPL berjalan lebih lancar.
                        </p>
                    </div>

                    <span class="profile-page-panel-badge">Area Pelamar</span>
                </div>

                <p class="form-message profile-page-message" data-page-message aria-live="polite"></p>
            </div>

            <div class="profile-page-card profile-view" data-profile-card>

                {{-- SUMMARY --}}
                <div class="profile-page-summary">
                    <div class="profile-page-avatar-wrap">
                        <div class="profile-page-avatar" aria-hidden="true">
                            AP
                        </div>
                    </div>

                    <div class="profile-page-summary-main">
                        <p class="eyebrow profile-page-eyebrow">Applicant Profile</p>

                        <h3 data-user-name>
                            Applicant
                        </h3>

                        <div class="profile-page-summary-meta">
                            <span>
                                NIK:
                                <strong data-profile-nik>-</strong>
                            </span>

                            <span>
                                Telepon:
                                <strong data-profile-phone>-</strong>
                            </span>
                        </div>
                    </div>

                    <div class="profile-page-status-panel">
                        <span class="profile-page-status-badge" data-profile-completeness-badge>
                            -
                        </span>

                        <p data-profile-completeness-note>
                            Pastikan data profil lengkap sebelum membuat pengajuan RPL.
                        </p>
                    </div>
                </div>

                {{-- DATA IDENTITAS --}}
                <div class="profile-page-section">
                    <div class="profile-page-section-header">
                        <span class="profile-page-section-icon profile-page-section-blue">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 12c2.76 0 5-2.24 5-5S14.76 2 12 2 7 4.24 7 7s2.24 5 5 5Zm0 2c-3.33 0-10 1.67-10 5v3h20v-3c0-3.33-6.67-5-10-5Z"/>
                            </svg>
                        </span>

                        <div>
                            <p class="eyebrow profile-page-eyebrow">Data Identitas</p>
                            <h3>Kontak dan alamat</h3>
                        </div>
                    </div>

                    <div class="profile-page-detail-grid">
                        <div class="profile-page-detail-item">
                            <span>NIK</span>
                            <strong data-profile-nik>-</strong>
                        </div>

                        <div class="profile-page-detail-item">
                            <span>Nomor Telepon</span>
                            <strong data-profile-phone>-</strong>
                        </div>

                        <div class="profile-page-detail-item profile-page-detail-wide">
                            <span>Alamat</span>
                            <strong data-profile-address>-</strong>
                        </div>
                    </div>
                </div>

                {{-- DATA PRIBADI --}}
                <div class="profile-page-section">
                    <div class="profile-page-section-header">
                        <span class="profile-page-section-icon profile-page-section-green">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 2a10 10 0 1 0 10 10A10.011 10.011 0 0 0 12 2Zm1 15h-2v-6h2v6Zm0-8h-2V7h2v2Z"/>
                            </svg>
                        </span>

                        <div>
                            <p class="eyebrow profile-page-eyebrow">Data Pribadi</p>
                            <h3>Informasi personal</h3>
                        </div>
                    </div>

                    <div class="profile-page-detail-grid">
                        <div class="profile-page-detail-item">
                            <span>Tempat Lahir <b>*</b></span>
                            <strong data-profile-birth-place>-</strong>
                        </div>

                        <div class="profile-page-detail-item">
                            <span>Tanggal Lahir <b>*</b></span>
                            <strong data-profile-birth-date>-</strong>
                        </div>

                        <div class="profile-page-detail-item">
                            <span>Jenis Kelamin <b>*</b></span>
                            <strong data-profile-gender>-</strong>
                        </div>

                        <div class="profile-page-detail-item">
                            <span>Status Perkawinan <b>*</b></span>
                            <strong data-profile-marital-status>-</strong>
                        </div>

                        <div class="profile-page-detail-item">
                            <span>Kewarganegaraan <b>*</b></span>
                            <strong data-profile-nationality>-</strong>
                        </div>

                        <div class="profile-page-detail-item">
                            <span>Kode Pos</span>
                            <strong data-profile-postal-code>-</strong>
                        </div>
                    </div>
                </div>

                {{-- RIWAYAT PENDIDIKAN --}}
                <div class="profile-page-section">
                    <div class="profile-page-section-header">
                        <span class="profile-page-section-icon profile-page-section-yellow">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 3 1 9l11 6 9-4.91V17h2V9L12 3Zm0 14L5 13.18V16c0 2 4.66 4 7 4s7-2 7-4v-2.82L12 17Z"/>
                            </svg>
                        </span>

                        <div>
                            <p class="eyebrow profile-page-eyebrow">Riwayat Pendidikan</p>
                            <h3>Pendidikan terakhir</h3>
                        </div>
                    </div>

                    <div class="profile-page-detail-grid">
                        <div class="profile-page-detail-item">
                            <span>Pendidikan Terakhir <b>*</b></span>
                            <strong data-profile-last-education>-</strong>
                        </div>

                        <div class="profile-page-detail-item">
                            <span>Nama Institusi <b>*</b></span>
                            <strong data-profile-institution-name>-</strong>
                        </div>

                        <div class="profile-page-detail-item">
                            <span>Program Studi</span>
                            <strong data-profile-study-program>-</strong>
                        </div>

                        <div class="profile-page-detail-item">
                            <span>Tahun Lulus <b>*</b></span>
                            <strong data-profile-graduation-year>-</strong>
                        </div>
                    </div>
                </div>

                {{-- ACTION --}}
                <div class="profile-page-actions">
                    <div>
                        <h3>Perlu memperbarui data?</h3>
                        <p>
                            Klik tombol edit untuk melengkapi atau memperbaiki data profil Anda.
                        </p>
                    </div>

                    <a href="/profile/edit" class="profile-page-edit-btn">
                        <span>Edit Profil</span>

                        <span class="profile-page-edit-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25Zm17.71-10.04c.39-.39.39-1.02 0-1.41L18.2 3.29a.9959.9959 0 0 0-1.41 0l-1.96 1.96L18.58 9l2.13-1.79Z"/>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <style>
        /*
        |--------------------------------------------------------------------------
        | PROFILE PAGE - PREMIUM APPLICANT STYLE
        |--------------------------------------------------------------------------
        */

        .profile-page-workspace,
        .profile-page-workspace * {
            box-sizing: border-box;
        }

        .profile-page-workspace {
            position: relative;
            display: grid;
            gap: 18px;
            min-width: 0;
        }

        .profile-page-hero {
            position: relative;
            overflow: hidden;
            padding: 24px;
            border-radius: 30px;
            background:
                radial-gradient(circle at 8% 0%, rgba(249, 168, 37, 0.18), transparent 28%),
                radial-gradient(circle at 92% 0%, rgba(21, 101, 192, 0.18), transparent 32%),
                linear-gradient(135deg, #ffffff 0%, #f8fafc 54%, #eef6ff 100%);
            border: 1px solid rgba(226, 232, 240, 0.92);
            box-shadow:
                0 22px 60px rgba(15, 23, 42, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.85);
        }

        .profile-page-hero::before {
            content: "";
            position: absolute;
            inset: 0 0 auto;
            height: 5px;
            background: linear-gradient(90deg, #1565C0, #F9A825, #E53935);
        }

        .profile-page-hero::after {
            content: "";
            position: absolute;
            width: 170px;
            height: 170px;
            right: -76px;
            bottom: -86px;
            border-radius: 999px;
            background: rgba(21, 101, 192, 0.08);
            pointer-events: none;
        }

        .profile-page-hero-content {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 22px;
        }

        .profile-page-eyebrow {
            margin-bottom: 8px;
            color: #1565C0;
            font-weight: 950;
        }

        .profile-page-hero h2 {
            margin: 0;
            color: #0f172a;
            font-family: 'Sora', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-size: clamp(1.65rem, 3vw, 2.45rem);
            line-height: 1.08;
            font-weight: 950;
            letter-spacing: -0.065em;
        }

        .profile-page-subtitle {
            max-width: 760px;
            margin: 10px 0 0;
            color: #64748b;
            font-size: 0.94rem;
            line-height: 1.72;
            font-weight: 650;
        }

        .profile-page-header-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            flex-shrink: 0;
        }

        /*
        |--------------------------------------------------------------------------
        | CONNECTED / API STATUS - DIBIKIN SAMA PERSIS KAYA APPLICATIONS
        |--------------------------------------------------------------------------
        */

        .connection-pill,
        .profile-page-status-pill {
            min-height: 42px;
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
                0 12px 28px rgba(15, 23, 42, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.65);
            font-size: 0.82rem;
            line-height: 1;
            font-weight: 950;
            white-space: nowrap;
        }

        .connection-pill::before,
        .profile-page-status-pill::before {
            content: "";
            width: 9px;
            height: 9px;
            flex: 0 0 9px;
            border-radius: 999px;
            background: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
        }

        .connection-pill.is-connected,
        .profile-page-status-pill.is-connected {
            color: #14532d !important;
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%) !important;
            border-color: #4ade80 !important;
            box-shadow:
                0 12px 28px rgba(34, 197, 94, 0.16),
                inset 0 1px 0 rgba(255, 255, 255, 0.72) !important;
        }

        .connection-pill.is-connected::before,
        .profile-page-status-pill.is-connected::before {
            background: #16a34a !important;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.18) !important;
        }

        .connection-pill.is-connecting,
        .profile-page-status-pill.is-connecting {
            color: #1d4ed8 !important;
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%) !important;
            border-color: #93c5fd !important;
        }

        .connection-pill.is-error,
        .profile-page-status-pill.is-error,
        .connection-pill.is-disconnected,
        .profile-page-status-pill.is-disconnected {
            color: #991b1b !important;
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%) !important;
            border-color: #fca5a5 !important;
            box-shadow:
                0 12px 28px rgba(239, 68, 68, 0.14),
                inset 0 1px 0 rgba(255, 255, 255, 0.65) !important;
        }

        .connection-pill.is-error::before,
        .profile-page-status-pill.is-error::before,
        .connection-pill.is-disconnected::before,
        .profile-page-status-pill.is-disconnected::before {
            background: #dc2626 !important;
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.16) !important;
        }

        .profile-page-edit-top-btn {
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 0 16px;
            border-radius: 999px;
            color: #ffffff;
            background: linear-gradient(135deg, #1565C0 0%, #0f4fa3 100%);
            border: 1px solid rgba(21, 101, 192, 0.20);
            box-shadow:
                0 16px 30px rgba(21, 101, 192, 0.22),
                inset 0 1px 0 rgba(255, 255, 255, 0.24);
            font-size: 0.86rem;
            line-height: 1;
            font-weight: 950;
            text-decoration: none;
            white-space: nowrap;
            transition:
                transform 0.22s ease,
                box-shadow 0.22s ease,
                filter 0.22s ease;
        }

        .profile-page-edit-top-btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.03);
            box-shadow:
                0 20px 36px rgba(21, 101, 192, 0.28),
                inset 0 1px 0 rgba(255, 255, 255, 0.28);
        }

        .profile-page-edit-top-icon {
            width: 22px;
            height: 22px;
            display: grid;
            place-items: center;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.18);
        }

        .profile-page-edit-top-icon svg {
            width: 14px;
            height: 14px;
            display: block;
            fill: currentColor;
        }

        .profile-page-stats {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-top: 22px;
        }

        .profile-page-stat-card {
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 15px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.78);
            border: 1px solid rgba(226, 232, 240, 0.92);
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.06);
            backdrop-filter: blur(10px);
        }

        .profile-page-stat-icon {
            width: 44px;
            height: 44px;
            flex: 0 0 44px;
            display: grid;
            place-items: center;
            border-radius: 17px;
        }

        .profile-page-stat-icon svg {
            width: 22px;
            height: 22px;
            fill: currentColor;
            display: block;
        }

        .profile-page-stat-blue {
            color: #1565C0;
            background: rgba(21, 101, 192, 0.10);
            border: 1px solid rgba(21, 101, 192, 0.12);
        }

        .profile-page-stat-green {
            color: #16a34a;
            background: rgba(22, 163, 74, 0.10);
            border: 1px solid rgba(22, 163, 74, 0.12);
        }

        .profile-page-stat-yellow {
            color: #b77905;
            background: rgba(249, 168, 37, 0.14);
            border: 1px solid rgba(249, 168, 37, 0.18);
        }

        .profile-page-stat-card p {
            margin: 0 0 4px;
            color: #64748b;
            font-size: 0.74rem;
            font-weight: 900;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .profile-page-stat-card strong {
            display: block;
            color: #0f172a;
            font-family: 'Sora', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-size: 1.28rem;
            line-height: 1;
            font-weight: 950;
            letter-spacing: -0.045em;
        }

        .profile-page-panel,
        .profile-page-card {
            overflow: hidden;
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.88);
            border: 1px solid rgba(226, 232, 240, 0.95);
            box-shadow:
                0 18px 50px rgba(15, 23, 42, 0.06),
                inset 0 1px 0 rgba(255, 255, 255, 0.9);
        }

        .profile-page-panel {
            padding: 20px;
        }

        .profile-page-panel-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
        }

        .profile-page-panel-head h3 {
            margin: 0;
            color: #0f172a;
            font-family: 'Sora', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-size: 1.08rem;
            line-height: 1.2;
            font-weight: 950;
            letter-spacing: -0.04em;
        }

        .profile-page-panel-head p {
            margin: 6px 0 0;
            color: #64748b;
            font-size: 0.84rem;
            line-height: 1.55;
            font-weight: 650;
        }

        .profile-page-panel-badge {
            min-height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 12px;
            border-radius: 999px;
            color: #1565C0;
            background: rgba(21, 101, 192, 0.08);
            border: 1px solid rgba(21, 101, 192, 0.10);
            font-size: 0.74rem;
            font-weight: 950;
            white-space: nowrap;
        }

        .profile-page-message {
            margin: 13px 0 0;
            font-weight: 800;
        }

        .profile-page-card {
            padding: 0;
        }

        .profile-page-summary {
            position: relative;
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) minmax(260px, 0.55fr);
            gap: 18px;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid rgba(226, 232, 240, 0.88);
            background:
                radial-gradient(circle at 0% 0%, rgba(21, 101, 192, 0.10), transparent 35%),
                linear-gradient(135deg, rgba(248, 250, 252, 0.95), rgba(255, 255, 255, 0.95));
        }

        .profile-page-avatar-wrap {
            position: relative;
        }

        .profile-page-avatar-wrap::after {
            content: "";
            position: absolute;
            right: -2px;
            bottom: -2px;
            width: 18px;
            height: 18px;
            border-radius: 999px;
            background: #16a34a;
            border: 4px solid #ffffff;
            box-shadow: 0 8px 18px rgba(22, 163, 74, 0.18);
        }

        .profile-page-avatar {
            width: 72px;
            height: 72px;
            display: grid;
            place-items: center;
            border-radius: 24px;
            color: #ffffff;
            background:
                radial-gradient(circle at 30% 15%, rgba(255, 255, 255, 0.28), transparent 30%),
                linear-gradient(135deg, #1565C0 0%, #0f4fa3 100%);
            box-shadow:
                0 16px 30px rgba(21, 101, 192, 0.22),
                inset 0 1px 0 rgba(255, 255, 255, 0.22);
            font-family: 'Sora', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-size: 1.18rem;
            line-height: 1;
            font-weight: 950;
            letter-spacing: -0.04em;
        }

        .profile-page-summary-main {
            min-width: 0;
        }

        .profile-page-summary-main h3 {
            margin: 0;
            color: #0f172a;
            font-family: 'Sora', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-size: 1.5rem;
            line-height: 1.15;
            font-weight: 950;
            letter-spacing: -0.045em;
            overflow-wrap: anywhere;
        }

        .profile-page-summary-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }

        .profile-page-summary-meta span {
            min-height: 31px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0 11px;
            border-radius: 999px;
            color: #64748b;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 9px 22px rgba(15, 23, 42, 0.035);
            font-size: 0.76rem;
            line-height: 1;
            font-weight: 850;
            white-space: nowrap;
        }

        .profile-page-summary-meta strong {
            color: #0f172a;
            font-weight: 950;
        }

        .profile-page-status-panel {
            min-width: 0;
            padding: 16px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.88);
            border: 1px solid rgba(226, 232, 240, 0.92);
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.05);
        }

        .profile-page-status-badge {
            min-height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 12px;
            border-radius: 999px;
            color: #1565C0;
            background: rgba(21, 101, 192, 0.08);
            border: 1px solid rgba(21, 101, 192, 0.12);
            font-size: 0.76rem;
            line-height: 1;
            font-weight: 950;
            white-space: nowrap;
        }

        .profile-page-status-panel p {
            margin: 10px 0 0;
            color: #64748b;
            font-size: 0.84rem;
            line-height: 1.55;
            font-weight: 700;
        }

        .profile-page-section {
            padding: 22px;
            border-bottom: 1px solid rgba(226, 232, 240, 0.88);
            background: #ffffff;
        }

        .profile-page-section:nth-of-type(even) {
            background:
                linear-gradient(135deg, rgba(248, 250, 252, 0.85), rgba(255, 255, 255, 0.95));
        }

        .profile-page-section-header {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 16px;
        }

        .profile-page-section-icon {
            width: 44px;
            height: 44px;
            flex: 0 0 44px;
            display: grid;
            place-items: center;
            border-radius: 17px;
        }

        .profile-page-section-icon svg {
            width: 22px;
            height: 22px;
            fill: currentColor;
            display: block;
        }

        .profile-page-section-blue {
            color: #1565C0;
            background: rgba(21, 101, 192, 0.10);
            border: 1px solid rgba(21, 101, 192, 0.12);
        }

        .profile-page-section-green {
            color: #16a34a;
            background: rgba(22, 163, 74, 0.10);
            border: 1px solid rgba(22, 163, 74, 0.12);
        }

        .profile-page-section-yellow {
            color: #b77905;
            background: rgba(249, 168, 37, 0.14);
            border: 1px solid rgba(249, 168, 37, 0.18);
        }

        .profile-page-section-header h3 {
            margin: 0;
            color: #0f172a;
            font-family: 'Sora', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-size: 1.12rem;
            line-height: 1.2;
            font-weight: 950;
            letter-spacing: -0.04em;
        }

        .profile-page-detail-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .profile-page-detail-item {
            min-width: 0;
            min-height: 88px;
            display: grid;
            align-content: center;
            gap: 8px;
            padding: 15px;
            border-radius: 18px;
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.94);
            box-shadow: 0 9px 22px rgba(15, 23, 42, 0.035);
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                border-color 0.2s ease;
        }

        .profile-page-detail-item:hover {
            transform: translateY(-2px);
            border-color: rgba(21, 101, 192, 0.18);
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.06);
        }

        .profile-page-detail-wide {
            grid-column: span 2;
        }

        .profile-page-detail-item span {
            color: #64748b;
            font-size: 0.72rem;
            line-height: 1.15;
            font-weight: 950;
            letter-spacing: 0.07em;
            text-transform: uppercase;
        }

        .profile-page-detail-item span b {
            color: #e53935;
            font-weight: 950;
        }

        .profile-page-detail-item strong {
            min-width: 0;
            overflow-wrap: anywhere;
            color: #0f172a;
            font-size: 0.94rem;
            line-height: 1.45;
            font-weight: 850;
        }

        .profile-page-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 22px;
            background:
                radial-gradient(circle at 95% 0%, rgba(249, 168, 37, 0.12), transparent 32%),
                linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        }

        .profile-page-actions h3 {
            margin: 0;
            color: #0f172a;
            font-family: 'Sora', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-size: 1.12rem;
            line-height: 1.2;
            font-weight: 950;
            letter-spacing: -0.04em;
        }

        .profile-page-actions p {
            margin: 6px 0 0;
            color: #64748b;
            font-size: 0.86rem;
            line-height: 1.55;
            font-weight: 700;
        }

        .profile-page-edit-btn {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 0 18px;
            border-radius: 999px;
            color: #ffffff;
            background: linear-gradient(135deg, #1565C0 0%, #0f4fa3 100%);
            border: 1px solid rgba(21, 101, 192, 0.20);
            box-shadow:
                0 15px 26px rgba(21, 101, 192, 0.23),
                inset 0 1px 0 rgba(255, 255, 255, 0.22);
            font-size: 0.9rem;
            line-height: 1;
            font-weight: 950;
            text-decoration: none;
            white-space: nowrap;
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                filter 0.2s ease;
        }

        .profile-page-edit-btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.04);
            box-shadow:
                0 18px 34px rgba(21, 101, 192, 0.28),
                inset 0 1px 0 rgba(255, 255, 255, 0.25);
        }

        .profile-page-edit-icon {
            width: 23px;
            height: 23px;
            display: grid;
            place-items: center;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.2);
        }

        .profile-page-edit-icon svg {
            width: 15px;
            height: 15px;
            fill: currentColor;
            display: block;
        }

        @media (max-width: 1100px) {
            .profile-page-hero,
            .profile-page-panel {
                padding: 20px;
            }

            .profile-page-hero-content {
                flex-direction: column;
            }

            .profile-page-header-actions {
                width: 100%;
                justify-content: flex-start;
                flex-wrap: wrap;
            }

            .profile-page-summary {
                grid-template-columns: auto minmax(0, 1fr);
            }

            .profile-page-status-panel {
                grid-column: 1 / -1;
            }

            .profile-page-detail-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 900px) {
            .profile-page-stats {
                grid-template-columns: 1fr;
            }

            .profile-page-stat-card {
                padding: 14px;
            }

            .profile-page-edit-top-btn {
                flex: 1;
            }

            .profile-page-status-pill {
                width: fit-content;
            }

            .profile-page-detail-grid {
                grid-template-columns: 1fr;
            }

            .profile-page-detail-wide {
                grid-column: auto;
            }

            .profile-page-actions {
                display: grid;
                align-items: flex-start;
            }

            .profile-page-edit-btn {
                width: 100%;
            }
        }

        @media (max-width: 640px) {
            .profile-page-workspace {
                gap: 14px;
            }

            .profile-page-hero,
            .profile-page-panel,
            .profile-page-card {
                border-radius: 24px;
            }

            .profile-page-hero,
            .profile-page-panel {
                padding: 16px;
            }

            .profile-page-hero h2 {
                font-size: 1.55rem;
                letter-spacing: -0.055em;
            }

            .profile-page-subtitle {
                font-size: 0.84rem;
                line-height: 1.62;
            }

            .profile-page-header-actions {
                display: grid;
                grid-template-columns: 1fr;
            }

            .profile-page-status-pill,
            .profile-page-edit-top-btn {
                width: 100%;
            }

            .profile-page-stats {
                margin-top: 18px;
            }

            .profile-page-panel-head {
                display: grid;
            }

            .profile-page-panel-badge {
                width: fit-content;
            }

            .profile-page-summary,
            .profile-page-section,
            .profile-page-actions {
                padding: 16px;
            }

            .profile-page-summary {
                grid-template-columns: 1fr;
                text-align: left;
            }

            .profile-page-avatar-wrap {
                width: fit-content;
            }

            .profile-page-avatar {
                width: 66px;
                height: 66px;
                border-radius: 22px;
                font-size: 1.08rem;
            }

            .profile-page-summary-main h3 {
                font-size: 1.28rem;
            }

            .profile-page-summary-meta {
                display: grid;
                grid-template-columns: 1fr;
            }

            .profile-page-summary-meta span {
                width: 100%;
                justify-content: space-between;
            }

            .profile-page-section-header {
                align-items: flex-start;
            }

            .profile-page-detail-item {
                min-height: 82px;
                padding: 14px;
                border-radius: 17px;
            }
        }

        @media (max-width: 420px) {
            .profile-page-hero,
            .profile-page-panel,
            .profile-page-summary,
            .profile-page-section,
            .profile-page-actions {
                padding: 14px;
            }

            .profile-page-stat-card {
                align-items: flex-start;
            }

            .profile-page-stat-icon,
            .profile-page-section-icon {
                width: 40px;
                height: 40px;
                flex-basis: 40px;
                border-radius: 15px;
            }

            .profile-page-stat-icon svg,
            .profile-page-section-icon svg {
                width: 20px;
                height: 20px;
            }

            .profile-page-stat-card strong {
                font-size: 1.12rem;
            }

            .profile-page-actions h3,
            .profile-page-section-header h3 {
                font-size: 1.02rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const apiStatus = document.querySelector('[data-api-status]');

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
                    text.includes('connecting') ||
                    text.includes('loading') ||
                    text.includes('memuat')
                ) {
                    apiStatus.classList.add('is-connecting');
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

            if (apiStatus) {
                const statusObserver = new MutationObserver(refreshApiStatusClass);

                statusObserver.observe(apiStatus, {
                    childList: true,
                    subtree: true,
                    characterData: true
                });

                refreshApiStatusClass();
            }
        });
    </script>
@endsection
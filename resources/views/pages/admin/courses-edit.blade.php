@extends('layouts.app')

@section('title', 'Edit Course - G-RPL')
@section('page', 'courses-edit')
@section('authRequired', 'true')
@section('roleRequired', 'system_admin')

@section('content')
    <section class="app-shell" data-protected-shell hidden>
        <x-admin-sidebar />

        <div class="workspace course-edit-workspace">
            <div class="course-edit-hero">
                <div class="course-edit-hero-content">
                    <div>
                        <p class="eyebrow course-edit-eyebrow">Course Management</p>
                        <h2>Ubah Mata Kuliah</h2>
                        <p class="course-edit-subtitle">
                            Perbarui data mata kuliah yang sudah tersedia di sistem G-RPL.
                            Pastikan perubahan kode, nama, program studi, semester, SKS, dan tipe RPL sudah benar.
                        </p>
                    </div>

                    <div class="course-edit-actions">
                        <span class="connection-pill course-edit-status-pill" data-api-status>Connecting</span>

                        <a href="/admin/courses" class="course-edit-back-btn">
                            <span class="course-edit-back-icon">←</span>
                            <span>Kembali</span>
                        </a>
                    </div>
                </div>

                <div class="course-edit-info-grid">
                    <div class="course-edit-info-card">
                        <span class="course-edit-info-icon course-edit-info-blue">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M18 2H7c-1.66 0-3 1.34-3 3v14c0 1.66 1.34 3 3 3h11c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2Zm0 16H7c-.55 0-1 .45-1 1s.45 1 1 1h11v-2Zm0-2H7c-.35 0-.69.06-1 .17V5c0-.55.45-1 1-1h11v12Z"/>
                            </svg>
                        </span>

                        <div>
                            <p>Form Type</p>
                            <strong>Perbarui Matakuliah</strong>
                        </div>
                    </div>

                    <div class="course-edit-info-card">
                        <span class="course-edit-info-icon course-edit-info-yellow">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 3 1 9l11 6 9-4.91V17h2V9L12 3Zm0 14L5 13.18V16c0 2 4.66 4 7 4s7-2 7-4v-2.82L12 17Z"/>
                            </svg>
                        </span>

                        <div>
                            <p>Data Scope</p>
                            <strong>Program Studi</strong>
                        </div>
                    </div>

                    <div class="course-edit-info-card">
                        <span class="course-edit-info-icon course-edit-info-green">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M9 16.17 4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17Z"/>
                            </svg>
                        </span>

                        <div>
                            <p>Status</p>
                            <strong>Pembaruan</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="course-edit-panel">
                <div class="course-edit-panel-head">
                    <div>
                        <h3>Informasi Mata Kuliah</h3>
                        <p>Ubah data mata kuliah berikut sesuai kebutuhan sistem G-RPL.</p>
                    </div>

                    <span class="course-edit-panel-badge">Update Matakuliah</span>
                </div>

                <form class="form-grid course-edit-form" data-course-form="edit">
                    <label class="course-edit-field course-edit-full">
                        <span>Program Studi</span>

                        <div class="course-edit-checkbox-group" data-study-program-select data-required></div>

                        <small>Pilih satu atau beberapa program studi yang menggunakan mata kuliah ini.</small>
                    </label>

                    <label class="course-edit-field">
                        <span>Kode MK</span>

                        <div class="course-edit-input-wrap">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M3 5c0-1.1.9-2 2-2h6v7H3V5Zm0 9h8v7H5c-1.1 0-2-.9-2-2v-5Zm10 7v-7h8v5c0 1.1-.9 2-2 2h-6Zm8-11h-8V3h6c1.1 0 2 .9 2 2v5Z"/>
                            </svg>

                            <input
                                type="text"
                                name="code"
                                required
                                maxlength="20"
                                placeholder="Contoh: IF101"
                            >
                        </div>
                    </label>

                    <label class="course-edit-field">
                        <span>Nama MK</span>

                        <div class="course-edit-input-wrap">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M4 4h16v2H4V4Zm0 4h10v2H4V8Zm0 4h16v2H4v-2Zm0 4h10v2H4v-2Z"/>
                            </svg>

                            <input
                                type="text"
                                name="name"
                                required
                                maxlength="100"
                                placeholder="Contoh: Algoritma Pemrograman"
                            >
                        </div>
                    </label>

                    <label class="course-edit-field">
                        <span>Semester</span>

                        <div class="course-edit-input-wrap">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M7 2v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2h-2V2h-2v2H9V2H7Zm12 8H5v10h14V10Z"/>
                            </svg>

                            <input
                                type="number"
                                name="semester"
                                required
                                min="1"
                                max="14"
                                placeholder="1"
                            >
                        </div>
                    </label>

                    <label class="course-edit-field">
                        <span>SKS</span>

                        <div class="course-edit-input-wrap">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 2 3 7v10l9 5 9-5V7l-9-5Zm0 2.3L18.5 8 12 11.7 5.5 8 12 4.3ZM5 9.73l6 3.39v6.61l-6-3.34V9.73Zm8 10v-6.61l6-3.39v6.66l-6 3.34Z"/>
                            </svg>

                            <input
                                type="number"
                                name="sks"
                                required
                                min="1"
                                max="4"
                                placeholder="3"
                            >
                        </div>
                    </label>

                    <label class="course-edit-field course-edit-full">
                        <span>Tipe RPL</span>

                        <div class="course-edit-select-wrap">
                            <select name="rpl_type" required>
                                <option value="a1">A1</option>
                                <option value="a2">A2</option>
                                <option value="hybrid">Hybrid</option>
                                <option value="not_supported">Not Supported</option>
                            </select>
                        </div>

                        <small>Tentukan tipe rekognisi yang berlaku untuk mata kuliah ini.</small>
                    </label>

                    <p class="form-message course-edit-message course-edit-full" data-form-message aria-live="polite"></p>

                    <div class="course-edit-submit-row course-edit-full">
                        <a href="/admin/courses" class="course-edit-cancel-btn">
                            Batal
                        </a>

                        <button class="course-edit-submit-btn" type="submit" data-submit-button>
                            <span>Perbarui Matakuliah</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <style>
        /*
        |--------------------------------------------------------------------------
        | EDIT COURSE PAGE - PREMIUM ADMIN STYLE
        |--------------------------------------------------------------------------
        */

        .course-edit-workspace,
        .course-edit-workspace * {
            box-sizing: border-box;
        }

        .course-edit-workspace {
            position: relative;
            display: grid;
            gap: 18px;
            min-width: 0;
        }

        .course-edit-hero {
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

        .course-edit-hero::before {
            content: "";
            position: absolute;
            inset: 0 0 auto;
            height: 5px;
            background: linear-gradient(90deg, #1565C0, #F9A825, #E53935);
        }

        .course-edit-hero::after {
            content: "";
            position: absolute;
            width: 180px;
            height: 180px;
            right: -78px;
            bottom: -90px;
            border-radius: 999px;
            background: rgba(21, 101, 192, 0.08);
            pointer-events: none;
        }

        .course-edit-hero-content {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 22px;
        }

        .course-edit-eyebrow {
            margin-bottom: 8px;
            color: #1565C0;
        }

        .course-edit-hero h2 {
            margin: 0;
            color: #0f172a;
            font-family: 'Sora', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-size: clamp(1.65rem, 3vw, 2.45rem);
            line-height: 1.08;
            font-weight: 950;
            letter-spacing: -0.065em;
        }

        .course-edit-subtitle {
            max-width: 760px;
            margin: 10px 0 0;
            color: #64748b;
            font-size: 0.94rem;
            line-height: 1.72;
            font-weight: 650;
        }

        .course-edit-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            flex-shrink: 0;
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS PILL
        |--------------------------------------------------------------------------
        */

        .connection-pill,
        .course-edit-status-pill {
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
            letter-spacing: 0.01em;
            white-space: nowrap;
        }

        .connection-pill::before,
        .course-edit-status-pill::before {
            content: "";
            width: 9px;
            height: 9px;
            flex: 0 0 9px;
            border-radius: 999px;
            background: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
        }

        .connection-pill.is-connected,
        .course-edit-status-pill.is-connected {
            color: #14532d;
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            border-color: #4ade80;
            box-shadow:
                0 12px 28px rgba(34, 197, 94, 0.16),
                inset 0 1px 0 rgba(255, 255, 255, 0.72);
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.45);
        }

        .connection-pill.is-connected::before,
        .course-edit-status-pill.is-connected::before {
            background: #16a34a;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.18);
        }

        .connection-pill.is-connecting,
        .course-edit-status-pill.is-connecting {
            color: #1d4ed8;
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-color: #93c5fd;
        }

        .connection-pill.is-connecting::before,
        .course-edit-status-pill.is-connecting::before {
            background: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
        }

        .connection-pill.is-error,
        .course-edit-status-pill.is-error,
        .connection-pill.is-disconnected,
        .course-edit-status-pill.is-disconnected {
            color: #991b1b;
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border-color: #fca5a5;
            box-shadow:
                0 12px 28px rgba(239, 68, 68, 0.14),
                inset 0 1px 0 rgba(255, 255, 255, 0.65);
        }

        .connection-pill.is-error::before,
        .course-edit-status-pill.is-error::before,
        .connection-pill.is-disconnected::before,
        .course-edit-status-pill.is-disconnected::before {
            background: #dc2626;
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.16);
        }

        .course-edit-back-btn {
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 0 16px;
            border-radius: 999px;
            color: #0f172a;
            background: rgba(255, 255, 255, 0.82);
            border: 1px solid rgba(203, 213, 225, 0.95);
            box-shadow:
                0 14px 28px rgba(15, 23, 42, 0.07),
                inset 0 1px 0 rgba(255, 255, 255, 0.92);
            font-size: 0.86rem;
            line-height: 1;
            font-weight: 950;
            text-decoration: none;
            white-space: nowrap;
            transition:
                transform 0.22s ease,
                box-shadow 0.22s ease,
                border-color 0.22s ease;
        }

        .course-edit-back-btn:hover {
            transform: translateY(-2px);
            border-color: rgba(21, 101, 192, 0.35);
            box-shadow:
                0 18px 34px rgba(15, 23, 42, 0.10),
                inset 0 1px 0 rgba(255, 255, 255, 0.94);
        }

        .course-edit-back-icon {
            width: 22px;
            height: 22px;
            display: grid;
            place-items: center;
            border-radius: 999px;
            color: #1565C0;
            background: rgba(21, 101, 192, 0.09);
            font-size: 1rem;
            line-height: 1;
            font-weight: 950;
        }

        .course-edit-info-grid {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-top: 22px;
        }

        .course-edit-info-card {
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

        .course-edit-info-icon {
            width: 44px;
            height: 44px;
            flex: 0 0 44px;
            display: grid;
            place-items: center;
            border-radius: 17px;
        }

        .course-edit-info-icon svg {
            width: 22px;
            height: 22px;
            fill: currentColor;
            display: block;
        }

        .course-edit-info-blue {
            color: #1565C0;
            background: rgba(21, 101, 192, 0.10);
            border: 1px solid rgba(21, 101, 192, 0.12);
        }

        .course-edit-info-yellow {
            color: #b77905;
            background: rgba(249, 168, 37, 0.14);
            border: 1px solid rgba(249, 168, 37, 0.18);
        }

        .course-edit-info-green {
            color: #16a34a;
            background: rgba(22, 163, 74, 0.10);
            border: 1px solid rgba(22, 163, 74, 0.12);
        }

        .course-edit-info-card p {
            margin: 0 0 4px;
            color: #64748b;
            font-size: 0.74rem;
            font-weight: 900;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .course-edit-info-card strong {
            display: block;
            color: #0f172a;
            font-family: 'Sora', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-size: 1.04rem;
            line-height: 1.2;
            font-weight: 950;
            letter-spacing: -0.04em;
        }

        .course-edit-panel {
            overflow: hidden;
            padding: 22px;
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(226, 232, 240, 0.95);
            box-shadow:
                0 18px 50px rgba(15, 23, 42, 0.06),
                inset 0 1px 0 rgba(255, 255, 255, 0.9);
        }

        .course-edit-panel-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            padding-bottom: 18px;
            border-bottom: 1px solid rgba(226, 232, 240, 0.85);
        }

        .course-edit-panel-head h3 {
            margin: 0;
            color: #0f172a;
            font-family: 'Sora', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-size: 1.08rem;
            line-height: 1.2;
            font-weight: 950;
            letter-spacing: -0.04em;
        }

        .course-edit-panel-head p {
            margin: 6px 0 0;
            color: #64748b;
            font-size: 0.84rem;
            line-height: 1.55;
            font-weight: 650;
        }

        .course-edit-panel-badge {
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

        .course-edit-form {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            margin-top: 20px;
            padding: 0;
            background: transparent;
            border: 0;
        }

        .course-edit-full {
            grid-column: 1 / -1;
        }

        .course-edit-field {
            display: grid;
            gap: 8px;
            min-width: 0;
            color: inherit;
            font-size: initial;
            font-weight: initial;
        }

        .course-edit-field > span {
            color: #475569;
            font-size: 0.72rem;
            line-height: 1;
            font-weight: 950;
            letter-spacing: 0.07em;
            text-transform: uppercase;
        }

        .course-edit-field small {
            color: #64748b;
            font-size: 0.78rem;
            line-height: 1.45;
            font-weight: 650;
        }

        .course-edit-input-wrap,
        .course-edit-select-wrap {
            position: relative;
            min-width: 0;
        }

        .course-edit-input-wrap svg {
            position: absolute;
            top: 50%;
            left: 13px;
            width: 18px;
            height: 18px;
            fill: #94a3b8;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .course-edit-input-wrap input {
            padding-left: 42px !important;
        }

        .course-edit-field input,
        .course-edit-field select {
            width: 100%;
            min-height: 48px;
            border-radius: 16px;
            border: 1px solid #dbe3ee;
            background: #ffffff;
            color: #0f172a;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.035);
            font-size: 0.92rem;
            font-weight: 750;
            outline: none;
            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease;
        }

        .course-edit-field select[multiple] {
            min-height: 145px;
            padding: 12px;
            line-height: 1.55;
        }

        .course-edit-checkbox-group {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 8px;
            max-height: 190px;
            overflow-y: auto;
            padding: 14px;
            border-radius: 16px;
            border: 1px solid #dbe3ee;
            background: #ffffff;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.035);
        }

        .course-edit-checkbox-group .checkbox-option {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 10px;
            border-radius: 12px;
            font-size: 0.86rem;
            font-weight: 650;
            color: #0f172a;
            cursor: pointer;
            transition: background 0.15s ease;
        }

        .course-edit-checkbox-group .checkbox-option:hover {
            background: #f1f5f9;
        }

        .course-edit-checkbox-group .checkbox-option input[type="checkbox"] {
            width: 17px;
            height: 17px;
            accent-color: #1565C0;
            flex-shrink: 0;
        }

        .course-edit-checkbox-group .form-hint {
            grid-column: 1 / -1;
            margin: 0;
            color: #94a3b8;
            font-size: 0.86rem;
        }

        .course-edit-field input::placeholder {
            color: #94a3b8;
            font-weight: 650;
        }

        .course-edit-field input:focus,
        .course-edit-field select:focus {
            border-color: rgba(21, 101, 192, 0.55);
            box-shadow:
                0 0 0 4px rgba(21, 101, 192, 0.10),
                0 12px 28px rgba(15, 23, 42, 0.05);
        }

        .course-edit-message {
            margin: 0;
            font-weight: 850;
        }

        .course-edit-submit-row {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 12px;
            margin-top: 4px;
            padding-top: 16px;
            border-top: 1px solid rgba(226, 232, 240, 0.85);
        }

        .course-edit-cancel-btn,
        .course-edit-submit-btn {
            min-height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 20px;
            border-radius: 16px;
            font-family: inherit;
            font-size: 0.88rem;
            line-height: 1;
            font-weight: 950;
            text-decoration: none;
            cursor: pointer;
            transition:
                transform 0.22s ease,
                box-shadow 0.22s ease,
                filter 0.22s ease,
                border-color 0.22s ease;
        }

        .course-edit-cancel-btn {
            color: #475569;
            background: #ffffff;
            border: 1px solid #dbe3ee;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.035);
        }

        .course-edit-cancel-btn:hover {
            color: #0f172a;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        .course-edit-submit-btn {
            color: #ffffff;
            background: linear-gradient(135deg, #1565C0 0%, #0f4fa3 100%);
            border: 1px solid rgba(21, 101, 192, 0.22);
            box-shadow:
                0 16px 30px rgba(21, 101, 192, 0.22),
                inset 0 1px 0 rgba(255, 255, 255, 0.24);
        }

        .course-edit-submit-btn:hover {
            transform: translateY(-1px);
            filter: brightness(1.03);
            box-shadow:
                0 20px 36px rgba(21, 101, 192, 0.28),
                inset 0 1px 0 rgba(255, 255, 255, 0.28);
        }

        .course-edit-submit-btn:disabled {
            cursor: not-allowed;
            opacity: 0.65;
            transform: none;
            box-shadow: none;
        }

        @media (max-width: 1100px) {
            .course-edit-hero,
            .course-edit-panel {
                padding: 20px;
            }

            .course-edit-hero-content {
                flex-direction: column;
            }

            .course-edit-actions {
                width: 100%;
                justify-content: flex-start;
                flex-wrap: wrap;
            }
        }

        @media (max-width: 900px) {
            .course-edit-info-grid {
                grid-template-columns: 1fr;
            }

            .course-edit-info-card {
                padding: 14px;
            }

            .course-edit-back-btn {
                flex: 1;
            }

            .course-edit-status-pill {
                width: fit-content;
            }
        }

        @media (max-width: 640px) {
            .course-edit-workspace {
                gap: 14px;
            }

            .course-edit-hero,
            .course-edit-panel {
                border-radius: 24px;
                padding: 16px;
            }

            .course-edit-hero h2 {
                font-size: 1.55rem;
                letter-spacing: -0.055em;
            }

            .course-edit-subtitle {
                font-size: 0.84rem;
                line-height: 1.62;
            }

            .course-edit-actions {
                display: grid;
                grid-template-columns: 1fr;
            }

            .course-edit-status-pill,
            .course-edit-back-btn {
                width: 100%;
            }

            .course-edit-info-grid {
                margin-top: 18px;
            }

            .course-edit-panel-head {
                display: grid;
            }

            .course-edit-panel-badge {
                width: fit-content;
            }

            .course-edit-form {
                grid-template-columns: 1fr;
                gap: 14px;
            }

            .course-edit-full {
                grid-column: auto;
            }

            .course-edit-field > span {
                font-size: 0.68rem;
            }

            .course-edit-field input,
            .course-edit-field select,
            .course-edit-cancel-btn,
            .course-edit-submit-btn {
                min-height: 44px;
                border-radius: 15px;
            }

            .course-edit-submit-row {
                display: grid;
                grid-template-columns: 1fr;
            }

            .course-edit-cancel-btn,
            .course-edit-submit-btn {
                width: 100%;
            }
        }

        @media (max-width: 420px) {
            .course-edit-hero,
            .course-edit-panel {
                padding: 14px;
            }

            .course-edit-info-card {
                align-items: flex-start;
            }

            .course-edit-info-icon {
                width: 40px;
                height: 40px;
                flex-basis: 40px;
                border-radius: 15px;
            }

            .course-edit-info-card strong {
                font-size: 0.98rem;
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

                if (text.includes('connecting') || text.includes('loading')) {
                    apiStatus.classList.add('is-connecting');
                    return;
                }

                if (
                    text.includes('error') ||
                    text.includes('failed') ||
                    text.includes('offline') ||
                    text.includes('disconnected')
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
@extends('layouts.app')

@section('title', 'Create Application - G-RPL2')
@section('page', 'applications-create')
@section('authRequired', 'true')
@section('roleRequired', 'applicant')

@section('content')
    <section class="app-shell" data-protected-shell hidden>

        {{-- Sidebar Applicant Blade --}}
        <x-applicant-sidebar />

        <div class="workspace application-create-workspace">

            {{-- HERO --}}
            <div class="application-create-hero">
                <div class="application-create-hero-main">
                    <div>
                        <p class="eyebrow application-create-eyebrow">Pengajuan Baru</p>

                        <h2>Buat Pengajuan RPL</h2>

                        <p class="application-create-subtitle">
                            Pilih program studi dan tipe rekognisi yang sesuai dengan riwayat pendidikan,
                            pengalaman kerja, pelatihan, sertifikasi, atau pembelajaran yang pernah Anda tempuh.
                        </p>
                    </div>

                    <div class="application-create-hero-actions">
                        <span class="connection-pill application-create-status-pill" data-api-status>
                            Connecting
                        </span>

                        <a href="/applications" class="application-create-back-btn">
                            <span class="application-create-back-icon">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.42-1.41L7.83 13H20v-2Z"/>
                                </svg>
                            </span>
                            <span>Kembali</span>
                        </a>
                    </div>
                </div>

                <div class="application-create-steps">
                    <div class="application-create-step-card">
                        <span class="application-create-step-number">1</span>
                        <div>
                            <p>Pilih Program Studi</p>
                            <strong>Tentukan tujuan pengajuan</strong>
                        </div>
                    </div>

                    <div class="application-create-step-card">
                        <span class="application-create-step-number">2</span>
                        <div>
                            <p>Pilih Tipe RPL</p>
                            <strong>A1, A2, atau Hybrid</strong>
                        </div>
                    </div>

                    <div class="application-create-step-card">
                        <span class="application-create-step-number">3</span>
                        <div>
                            <p>Lanjutkan</p>
                            <strong>Lengkapi data pendukung</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div data-page-message></div>

            {{-- FORM CARD --}}
            <div class="application-create-card">
                <div class="application-create-card-head">
                    <div>
                        <p class="eyebrow application-create-eyebrow">Pengaturan Pengajuan</p>
                        <h3>Pilih tipe rekognisi</h3>
                        <p>
                            Pastikan pilihan Anda sudah benar karena tipe rekognisi menentukan data apa saja
                            yang perlu dilengkapi pada tahap berikutnya.
                        </p>
                    </div>

                    <span class="application-create-badge">
                        Area Pelamar
                    </span>
                </div>

                <form class="application-create-form form-grid" data-create-application-form>
                    <label class="application-create-field">
                        <span>Program Studi</span>

                        <select name="study_program_id" data-study-program-select required>
                            <option value="">Pilih program studi</option>
                        </select>
                    </label>

                    <label class="application-create-field">
                        <span>Tipe Rekognisi</span>

                        <select name="rpl_type" data-rpl-type-select required>
                            <option value="">Pilih tipe</option>
                            <option value="a1">
                                A1 - Pengakuan Terhadap Pencapaian Pembelajaran Formal
                            </option>
                            <option value="a2">
                                A2 - Pengakuan Terhadap Pencapaian Pembelajaran Informal dan Nonformal
                            </option>
                            <option value="hybrid">
                                Hybrid - Kombinasi A1 dan A2
                            </option>
                        </select>
                    </label>

                    <div class="application-create-type-grid" aria-label="Informasi tipe rekognisi">
                        <button type="button" class="application-create-type-card" data-rpl-type-card="a1">
                            <span class="application-create-type-icon application-create-type-blue">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M18 2H7c-1.66 0-3 1.34-3 3v14c0 1.66 1.34 3 3 3h11c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2Zm0 16H7c-.55 0-1 .45-1 1s.45 1 1 1h11v-2Zm0-2H7c-.35 0-.69.06-1 .17V5c0-.55.45-1 1-1h11v12Z"/>
                                </svg>
                            </span>

                            <span>
                                <strong>A1 Formal</strong>
                                <small>Untuk rekognisi pembelajaran dari pendidikan formal.</small>
                            </span>
                        </button>

                        <button type="button" class="application-create-type-card" data-rpl-type-card="a2">
                            <span class="application-create-type-icon application-create-type-green">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M12 2 1 7l11 5 9-4.09V17h2V7L12 2Zm0 12L5 10.82V15c0 2.21 3.13 4 7 4s7-1.79 7-4v-4.18L12 14Z"/>
                                </svg>
                            </span>

                            <span>
                                <strong>A2 Nonformal</strong>
                                <small>Untuk pengalaman kerja, pelatihan, sertifikasi, atau proyek.</small>
                            </span>
                        </button>

                        <button type="button" class="application-create-type-card" data-rpl-type-card="hybrid">
                            <span class="application-create-type-icon application-create-type-yellow">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M11 7H5c-1.1 0-2 .9-2 2v9c0 1.1.9 2 2 2h6v-2H5V9h6V7Zm2 0v2h6v9h-6v2h6c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2h-6Zm-5 6h8v-2H8v2Zm0 4h8v-2H8v2Z"/>
                                </svg>
                            </span>

                            <span>
                                <strong>Hybrid</strong>
                                <small>Kombinasi A1 dan A2 dalam satu pengajuan RPL.</small>
                            </span>
                        </button>
                    </div>

                    <div class="application-create-form-message" data-form-message></div>

                    <div class="application-create-actions">
                        <a href="/applications" class="application-create-muted-btn">
                            Batal
                        </a>

                        <button class="application-create-submit-btn button" type="button" data-create-application>
                            <span>Lanjutkan</span>
                            <span class="application-create-submit-icon">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M12 4 10.59 5.41 16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8-8-8Z"/>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- INFO PANEL --}}
            <div class="application-create-info-grid">
                <div class="application-create-info-card">
                    <span class="application-create-info-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 2a10 10 0 1 0 10 10A10.011 10.011 0 0 0 12 2Zm1 15h-2v-6h2v6Zm0-8h-2V7h2v2Z"/>
                        </svg>
                    </span>

                    <div>
                        <h4>Pastikan data profil lengkap</h4>
                        <p>
                            Lengkapi profil terlebih dahulu agar proses pengajuan RPL lebih mudah divalidasi.
                        </p>
                    </div>
                </div>

                <div class="application-create-info-card">
                    <span class="application-create-info-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6Zm-1 7V3.5L18.5 9H13ZM8 13h8v2H8v-2Zm0 4h8v2H8v-2Z"/>
                        </svg>
                    </span>

                    <div>
                        <h4>Siapkan dokumen pendukung</h4>
                        <p>
                            Dokumen seperti transkrip, sertifikat, portofolio, atau bukti pengalaman akan dibutuhkan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .application-create-workspace,
        .application-create-workspace * {
            box-sizing: border-box;
        }

        .application-create-workspace {
            display: grid;
            gap: 18px;
            min-width: 0;
        }

        .application-create-hero,
        .application-create-card,
        .application-create-info-card {
            border: 1px solid rgba(203, 213, 225, 0.78);
            background: rgba(255, 255, 255, 0.94);
            box-shadow: 0 18px 55px rgba(15, 23, 42, 0.07);
        }

        .application-create-hero {
            position: relative;
            overflow: hidden;
            padding: 24px;
            border-radius: 30px;
            background:
                radial-gradient(circle at 8% 0%, rgba(249, 168, 37, 0.15), transparent 28%),
                radial-gradient(circle at 92% 0%, rgba(21, 101, 192, 0.16), transparent 32%),
                linear-gradient(135deg, #ffffff 0%, #f8fafc 55%, #eef6ff 100%);
        }

        .application-create-hero::before {
            content: "";
            position: absolute;
            inset: 0 0 auto;
            height: 5px;
            background: linear-gradient(90deg, #1565C0, #F9A825, #E53935);
        }

        .application-create-hero-main {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 22px;
        }

        .application-create-eyebrow {
            margin-bottom: 8px;
            color: #1565C0;
            font-weight: 950;
        }

        .application-create-hero h2 {
            margin: 0;
            color: #0f172a;
            font-family: 'Sora', system-ui, sans-serif;
            font-size: clamp(1.85rem, 3vw, 2.75rem);
            line-height: 1.05;
            font-weight: 950;
            letter-spacing: -0.07em;
        }

        .application-create-subtitle {
            max-width: 780px;
            margin: 12px 0 0;
            color: #64748b;
            font-size: 0.95rem;
            line-height: 1.7;
            font-weight: 650;
        }

        .application-create-hero-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            flex-shrink: 0;
        }

        .application-create-status-pill,
        .connection-pill {
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 0 16px;
            border-radius: 999px;
            color: #1d4ed8;
            background: #dbeafe;
            border: 1px solid #93c5fd;
            font-size: 0.82rem;
            font-weight: 950;
            white-space: nowrap;
        }

        .application-create-status-pill::before,
        .connection-pill::before {
            content: "";
            width: 9px;
            height: 9px;
            border-radius: 999px;
            background: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.14);
        }

        .connection-pill.is-connected {
            color: #14532d;
            background: #dcfce7;
            border-color: #86efac;
        }

        .connection-pill.is-connected::before {
            background: #16a34a;
        }

        .connection-pill.is-error,
        .connection-pill.is-disconnected {
            color: #991b1b;
            background: #fee2e2;
            border-color: #fca5a5;
        }

        .connection-pill.is-error::before,
        .connection-pill.is-disconnected::before {
            background: #dc2626;
        }

        .application-create-back-btn,
        .application-create-submit-btn {
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 0 16px;
            border: 0;
            border-radius: 999px;
            color: #ffffff;
            background: linear-gradient(135deg, #1565C0, #0f4fa3);
            box-shadow: 0 15px 26px rgba(21, 101, 192, 0.23);
            font-family: inherit;
            font-size: 0.86rem;
            line-height: 1;
            font-weight: 950;
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
            transition: 0.2s ease;
        }

        .application-create-back-btn:hover,
        .application-create-submit-btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.04);
            box-shadow: 0 18px 34px rgba(21, 101, 192, 0.28);
        }

        .application-create-back-icon,
        .application-create-submit-icon {
            width: 22px;
            height: 22px;
            display: grid;
            place-items: center;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.2);
            font-weight: 950;
        }

        .application-create-back-icon svg,
        .application-create-submit-icon svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
        }

        .application-create-steps {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-top: 22px;
        }

        .application-create-step-card {
            display: flex;
            align-items: center;
            gap: 13px;
            min-width: 0;
            padding: 16px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.05);
        }

        .application-create-step-number {
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            display: grid;
            place-items: center;
            border-radius: 15px;
            color: #ffffff;
            background: linear-gradient(135deg, #1565C0, #0f4fa3);
            box-shadow: 0 12px 22px rgba(21, 101, 192, 0.2);
            font-weight: 950;
        }

        .application-create-step-card p {
            margin: 0 0 5px;
            color: #64748b;
            font-size: 0.74rem;
            font-weight: 950;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .application-create-step-card strong {
            color: #0f172a;
            font-size: 0.92rem;
            font-weight: 950;
        }

        .application-create-card {
            overflow: hidden;
            padding: 20px;
            border-radius: 28px;
        }

        .application-create-card-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 18px;
            padding-bottom: 18px;
            border-bottom: 1px solid rgba(226, 232, 240, 0.9);
        }

        .application-create-card-head h3 {
            margin: 0;
            color: #0f172a;
            font-family: 'Sora', system-ui, sans-serif;
            font-size: 1.25rem;
            line-height: 1.2;
            font-weight: 950;
            letter-spacing: -0.045em;
        }

        .application-create-card-head p {
            max-width: 760px;
            margin: 7px 0 0;
            color: #64748b;
            font-size: 0.88rem;
            line-height: 1.55;
            font-weight: 700;
        }

        .application-create-badge {
            min-height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 13px;
            border-radius: 999px;
            color: #1565C0;
            background: rgba(21, 101, 192, 0.08);
            border: 1px solid rgba(21, 101, 192, 0.12);
            font-size: 0.75rem;
            font-weight: 950;
            white-space: nowrap;
        }

        .application-create-form {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            margin-top: 20px;
        }

        .application-create-field {
            display: grid;
            gap: 8px;
            min-width: 0;
        }

        .application-create-field span {
            color: #475569;
            font-size: 0.72rem;
            font-weight: 950;
            letter-spacing: 0.07em;
            text-transform: uppercase;
        }

        .application-create-field select {
            width: 100%;
            min-height: 50px;
            padding: 0 15px;
            border: 1px solid #cbd5e1;
            border-radius: 17px;
            background: #ffffff;
            color: #0f172a;
            font: inherit;
            font-size: 0.92rem;
            font-weight: 800;
            outline: none;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.035);
            transition: 0.2s ease;
        }

        .application-create-field select:hover {
            border-color: rgba(21, 101, 192, 0.45);
            box-shadow: 0 12px 28px rgba(21, 101, 192, 0.08);
        }

        .application-create-field select:focus {
            border-color: rgba(21, 101, 192, 0.62);
            box-shadow: 0 0 0 4px rgba(21, 101, 192, 0.1);
        }

        .application-create-type-grid {
            grid-column: 1 / -1;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-top: 2px;
        }

        .application-create-type-card {
            min-height: 128px;
            display: flex;
            align-items: flex-start;
            gap: 13px;
            padding: 16px;
            border-radius: 22px;
            border: 1px solid #cbd5e1;
            background: linear-gradient(135deg, #ffffff, #f8fafc);
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.04);
            font-family: inherit;
            text-align: left;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .application-create-type-card:hover {
            transform: translateY(-2px);
            border-color: rgba(21, 101, 192, 0.36);
            box-shadow: 0 16px 36px rgba(21, 101, 192, 0.1);
        }

        .application-create-type-card.active {
            border-color: rgba(21, 101, 192, 0.55);
            background:
                radial-gradient(circle at 95% 0%, rgba(21, 101, 192, 0.12), transparent 36%),
                linear-gradient(135deg, #ffffff, #eef6ff);
            box-shadow:
                0 18px 42px rgba(21, 101, 192, 0.13),
                inset 0 0 0 1px rgba(21, 101, 192, 0.08);
        }

        .application-create-type-icon {
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            display: grid;
            place-items: center;
            border-radius: 16px;
        }

        .application-create-type-icon svg {
            width: 22px;
            height: 22px;
            fill: currentColor;
        }

        .application-create-type-blue {
            color: #1565C0;
            background: rgba(21, 101, 192, 0.1);
        }

        .application-create-type-green {
            color: #16a34a;
            background: rgba(22, 163, 74, 0.1);
        }

        .application-create-type-yellow {
            color: #b77905;
            background: rgba(249, 168, 37, 0.15);
        }

        .application-create-type-card strong {
            display: block;
            margin: 1px 0 7px;
            color: #0f172a;
            font-size: 0.98rem;
            font-weight: 950;
        }

        .application-create-type-card small {
            display: block;
            color: #64748b;
            font-size: 0.82rem;
            line-height: 1.45;
            font-weight: 700;
        }

        .application-create-form-message {
            grid-column: 1 / -1;
        }

        .application-create-actions {
            grid-column: 1 / -1;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            padding-top: 16px;
            border-top: 1px solid rgba(226, 232, 240, 0.9);
        }

        .application-create-muted-btn {
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 18px;
            border-radius: 999px;
            color: #334155;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            font-size: 0.9rem;
            font-weight: 950;
            text-decoration: none;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .application-create-muted-btn:hover {
            border-color: rgba(21, 101, 192, 0.45);
            color: #1565C0;
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(15, 23, 42, 0.08);
        }

        .application-create-info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .application-create-info-card {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 18px;
            border-radius: 24px;
        }

        .application-create-info-icon {
            width: 44px;
            height: 44px;
            flex: 0 0 44px;
            display: grid;
            place-items: center;
            border-radius: 16px;
            color: #1565C0;
            background: rgba(21, 101, 192, 0.1);
        }

        .application-create-info-icon svg {
            width: 22px;
            height: 22px;
            fill: currentColor;
        }

        .application-create-info-card h4 {
            margin: 0;
            color: #0f172a;
            font-size: 1rem;
            font-weight: 950;
        }

        .application-create-info-card p {
            margin: 6px 0 0;
            color: #64748b;
            font-size: 0.86rem;
            line-height: 1.55;
            font-weight: 700;
        }

        @media (max-width: 1200px) {
            .application-create-hero-main,
            .application-create-card-head {
                flex-direction: column;
            }

            .application-create-hero-actions {
                width: 100%;
                justify-content: flex-start;
                flex-wrap: wrap;
            }

            .application-create-type-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 900px) {
            .application-create-steps,
            .application-create-form,
            .application-create-info-grid {
                grid-template-columns: 1fr;
            }

            .application-create-back-btn,
            .application-create-status-pill,
            .application-create-submit-btn,
            .application-create-muted-btn {
                width: 100%;
            }

            .application-create-hero-actions,
            .application-create-actions {
                display: grid;
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .application-create-workspace {
                gap: 14px;
            }

            .application-create-hero,
            .application-create-card,
            .application-create-info-card {
                border-radius: 24px;
            }

            .application-create-hero,
            .application-create-card {
                padding: 16px;
            }

            .application-create-hero h2 {
                font-size: 1.65rem;
            }

            .application-create-subtitle {
                font-size: 0.84rem;
                line-height: 1.62;
            }

            .application-create-card-head {
                padding-bottom: 15px;
            }

            .application-create-badge {
                width: fit-content;
            }

            .application-create-type-card {
                min-height: auto;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const apiStatus = document.querySelector('[data-api-status]');
            const typeSelect = document.querySelector('[data-rpl-type-select]');
            const typeCards = document.querySelectorAll('[data-rpl-type-card]');

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

            function syncTypeCards(value) {
                typeCards.forEach(function (card) {
                    card.classList.toggle('active', card.dataset.rplTypeCard === value);
                });
            }

            typeCards.forEach(function (card) {
                card.addEventListener('click', function () {
                    const value = card.dataset.rplTypeCard;

                    if (typeSelect) {
                        typeSelect.value = value;
                        typeSelect.dispatchEvent(new Event('change', { bubbles: true }));
                    }

                    syncTypeCards(value);
                });
            });

            if (typeSelect) {
                typeSelect.addEventListener('change', function () {
                    syncTypeCards(typeSelect.value);
                });

                syncTypeCards(typeSelect.value);
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
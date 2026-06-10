@extends('layouts.app')

@section('title', 'Edit Profil - G-RPL2')
@section('page', 'profile-edit')
@section('authRequired', 'true')
@section('roleRequired', 'applicant')

@section('content')
    <section class="app-shell" data-protected-shell hidden>

        {{-- Sidebar Applicant Blade --}}
        <x-applicant-sidebar />

        <div class="workspace profile-edit-workspace">

            {{-- HERO --}}
            <div class="profile-edit-hero">
                <div class="profile-edit-hero-main">
                    <div>
                        <p class="eyebrow profile-edit-eyebrow">Edit Profil</p>

                        <h2>Lengkapi Data Profil</h2>

                        <p class="profile-edit-subtitle">
                            Perbarui data identitas, informasi pribadi, dan riwayat pendidikan.
                            Field bertanda bintang wajib diisi agar profil siap digunakan untuk pengajuan RPL.
                        </p>
                    </div>

                    <div class="profile-edit-hero-actions">
                        <span class="connection-pill profile-edit-status-pill" data-api-status>
                            Connecting
                        </span>

                        <a href="/profile" class="profile-edit-back-btn">
                            <span class="profile-edit-back-icon">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.42-1.41L7.83 13H20v-2Z"/>
                                </svg>
                            </span>
                            <span>Kembali</span>
                        </a>
                    </div>
                </div>
            </div>

            <div data-page-message></div>

            <form class="profile-edit-card profile-form" data-profile-form>
                <div class="profile-edit-card-head">
                    <div>
                        <p class="eyebrow profile-edit-eyebrow">Profil Pelamar</p>
                        <h3>Data utama pelamar</h3>
                        <p>
                            Isi data dengan benar. Data profil akan digunakan untuk proses validasi pengajuan RPL.
                        </p>
                    </div>

                    <span class="profile-edit-badge">
                        Field * wajib
                    </span>
                </div>

                {{-- DATA IDENTITAS --}}
                <div class="profile-edit-section">
                    <div class="profile-edit-section-header">
                        <span class="profile-edit-section-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 12c2.76 0 5-2.24 5-5S14.76 2 12 2 7 4.24 7 7s2.24 5 5 5Zm0 2c-3.33 0-10 1.67-10 5v3h20v-3c0-3.33-6.67-5-10-5Z"/>
                            </svg>
                        </span>

                        <div>
                            <p class="eyebrow profile-edit-eyebrow">Data Identitas</p>
                            <h3>Kontak dan alamat</h3>
                        </div>
                    </div>

                    <div class="profile-edit-grid">
                        <label>
                            <span>Nomor Telepon</span>
                            <input
                                type="tel"
                                name="phone"
                                data-profile-phone
                                placeholder="Contoh: 081234567890"
                            >
                        </label>

                        <label class="profile-edit-full-field">
                            <span>Alamat</span>
                            <textarea
                                name="address"
                                data-profile-address
                                rows="3"
                                placeholder="Masukkan alamat lengkap"
                            ></textarea>
                        </label>
                    </div>
                </div>

                {{-- DATA PRIBADI --}}
                <div class="profile-edit-section">
                    <div class="profile-edit-section-header">
                        <span class="profile-edit-section-icon profile-edit-section-icon-green">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 2a10 10 0 1 0 10 10A10.011 10.011 0 0 0 12 2Zm1 15h-2v-6h2v6Zm0-8h-2V7h2v2Z"/>
                            </svg>
                        </span>

                        <div>
                            <p class="eyebrow profile-edit-eyebrow">Data Pribadi</p>
                            <h3>Informasi personal</h3>
                        </div>
                    </div>

                    <div class="profile-edit-grid">
                        <label>
                            <span>Tempat Lahir <b>*</b></span>
                            <input
                                type="text"
                                name="birth_place"
                                data-profile-birth-place
                                placeholder="Contoh: Jakarta"
                                required
                            >
                        </label>

                        <label>
                            <span>Tanggal Lahir <b>*</b></span>
                            <input
                                type="date"
                                name="birth_date"
                                data-profile-birth-date
                                required
                            >
                        </label>

                        <label>
                            <span>Jenis Kelamin <b>*</b></span>
                            <select name="gender" data-profile-gender required>
                                <option value="">Pilih jenis kelamin</option>
                                <option value="male">Laki-laki</option>
                                <option value="female">Perempuan</option>
                            </select>
                        </label>

                        <label>
                            <span>Status Perkawinan <b>*</b></span>
                            <select name="marital_status" data-profile-marital-status required>
                                <option value="">Pilih status</option>
                                <option value="single">Belum Kawin</option>
                                <option value="married">Kawin</option>
                                <option value="divorced">Cerai</option>
                            </select>
                        </label>

                        <label>
                            <span>Kewarganegaraan <b>*</b></span>
                            <input
                                type="text"
                                name="nationality"
                                data-profile-nationality
                                placeholder="Contoh: Indonesia"
                                required
                            >
                        </label>

                        <label>
                            <span>Kode Pos</span>
                            <input
                                type="text"
                                name="postal_code"
                                data-profile-postal-code
                                placeholder="Contoh: 15117"
                            >
                        </label>
                    </div>
                </div>

                {{-- RIWAYAT PENDIDIKAN --}}
                <div class="profile-edit-section">
                    <div class="profile-edit-section-header">
                        <span class="profile-edit-section-icon profile-edit-section-icon-yellow">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 3 1 9l11 6 9-4.91V17h2V9L12 3Zm0 14L5 13.18V16c0 2 4.66 4 7 4s7-2 7-4v-2.82L12 17Z"/>
                            </svg>
                        </span>

                        <div>
                            <p class="eyebrow profile-edit-eyebrow">Riwayat Pendidikan</p>
                            <h3>Pendidikan terakhir</h3>
                        </div>
                    </div>

                    <div class="profile-edit-grid">
                        <label>
                            <span>Pendidikan Terakhir <b>*</b></span>
                            <select name="last_education" data-profile-last-education required>
                                <option value="">Pilih pendidikan terakhir</option>
                                <option value="SMA">SMA / SMK / Sederajat</option>
                                <option value="D3">Diploma 3 (D3)</option>
                                <option value="D4">Diploma 4 (D4) / Sarjana Terapan</option>
                                <option value="S1">Sarjana (S1)</option>
                                <option value="S2">Magister (S2)</option>
                                <option value="S3">Doktor (S3)</option>
                            </select>
                        </label>

                        <label>
                            <span>Nama Institusi <b>*</b></span>
                            <input
                                type="text"
                                name="institution_name"
                                data-profile-institution-name
                                placeholder="Contoh: SMAN 1 Tangerang"
                                required
                            >
                        </label>

                        <label>
                            <span>Program Studi</span>
                            <input
                                type="text"
                                name="study_program"
                                data-profile-study-program
                                placeholder="Contoh: Teknik Informatika"
                            >
                        </label>

                        <label>
                            <span>Tahun Lulus <b>*</b></span>
                            <input
                                type="number"
                                name="graduation_year"
                                data-profile-graduation-year
                                min="1950"
                                max="{{ date('Y') }}"
                                placeholder="Contoh: 2018"
                                required
                            >
                        </label>
                    </div>
                </div>

                <div class="profile-edit-actions">
                    <div data-form-message></div>

                    <div class="profile-edit-action-buttons">
                        <a href="/profile" class="profile-edit-muted-btn">
                            Batal
                        </a>

                        <button class="profile-edit-submit-btn button" type="submit" data-save-profile>
                            Simpan Profil
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <style>
        .profile-edit-workspace,
        .profile-edit-workspace * {
            box-sizing: border-box;
        }

        .profile-edit-workspace {
            display: grid;
            gap: 18px;
            min-width: 0;
        }

        .profile-edit-hero,
        .profile-edit-card {
            border: 1px solid rgba(203, 213, 225, 0.78);
            background: rgba(255, 255, 255, 0.94);
            box-shadow: 0 18px 55px rgba(15, 23, 42, 0.07);
        }

        .profile-edit-hero {
            position: relative;
            overflow: hidden;
            padding: 24px;
            border-radius: 30px;
            background:
                radial-gradient(circle at 8% 0%, rgba(249, 168, 37, 0.15), transparent 28%),
                radial-gradient(circle at 92% 0%, rgba(21, 101, 192, 0.16), transparent 32%),
                linear-gradient(135deg, #ffffff 0%, #f8fafc 55%, #eef6ff 100%);
        }

        .profile-edit-hero::before {
            content: "";
            position: absolute;
            inset: 0 0 auto;
            height: 5px;
            background: linear-gradient(90deg, #1565C0, #F9A825, #E53935);
        }

        .profile-edit-hero-main {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 22px;
        }

        .profile-edit-eyebrow {
            color: #1565C0;
            font-weight: 950;
        }

        .profile-edit-hero h2 {
            margin: 0;
            color: #0f172a;
            font-family: 'Sora', system-ui, sans-serif;
            font-size: clamp(1.85rem, 3vw, 2.75rem);
            line-height: 1.05;
            font-weight: 950;
            letter-spacing: -0.07em;
        }

        .profile-edit-subtitle {
            max-width: 780px;
            margin: 12px 0 0;
            color: #64748b;
            font-size: 0.95rem;
            line-height: 1.7;
            font-weight: 650;
        }

        .profile-edit-hero-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            flex-shrink: 0;
        }

        .profile-edit-status-pill,
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

        .profile-edit-status-pill::before,
        .connection-pill::before {
            content: "";
            width: 9px;
            height: 9px;
            border-radius: 999px;
            background: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.14);
        }

        .profile-edit-back-btn,
        .profile-edit-submit-btn {
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 0 18px;
            border: 0;
            border-radius: 999px;
            color: #ffffff;
            background: linear-gradient(135deg, #1565C0, #0f4fa3);
            box-shadow: 0 15px 26px rgba(21, 101, 192, 0.23);
            font-family: inherit;
            font-size: 0.9rem;
            line-height: 1;
            font-weight: 950;
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
            transition: 0.2s ease;
        }

        .profile-edit-back-btn:hover,
        .profile-edit-submit-btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.04);
            box-shadow: 0 18px 34px rgba(21, 101, 192, 0.28);
        }

        .profile-edit-back-icon {
            width: 22px;
            height: 22px;
            display: grid;
            place-items: center;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.2);
        }

        .profile-edit-back-icon svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
        }

        .profile-edit-card {
            overflow: hidden;
            border-radius: 30px;
        }

        .profile-edit-card-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 18px;
            padding: 22px;
            border-bottom: 1px solid #e2e8f0;
            background: linear-gradient(135deg, #ffffff, #f8fafc);
        }

        .profile-edit-card-head h3,
        .profile-edit-section-header h3 {
            margin: 0;
            color: #0f172a;
            font-size: 1.2rem;
            font-weight: 950;
            letter-spacing: -0.045em;
        }

        .profile-edit-card-head p {
            max-width: 760px;
            margin: 7px 0 0;
            color: #64748b;
            font-size: 0.88rem;
            line-height: 1.55;
            font-weight: 700;
        }

        .profile-edit-badge {
            min-height: 34px;
            display: inline-flex;
            align-items: center;
            padding: 0 13px;
            border-radius: 999px;
            color: #1565C0;
            background: rgba(21, 101, 192, 0.08);
            border: 1px solid rgba(21, 101, 192, 0.12);
            font-size: 0.75rem;
            font-weight: 950;
            white-space: nowrap;
        }

        .profile-edit-section {
            padding: 22px;
            border-bottom: 1px solid #e2e8f0;
        }

        .profile-edit-section-header {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 16px;
        }

        .profile-edit-section-icon {
            width: 44px;
            height: 44px;
            flex: 0 0 44px;
            display: grid;
            place-items: center;
            border-radius: 16px;
            color: #1565C0;
            background: rgba(21, 101, 192, 0.1);
        }

        .profile-edit-section-icon svg {
            width: 22px;
            height: 22px;
            fill: currentColor;
        }

        .profile-edit-section-icon-green {
            color: #16a34a;
            background: rgba(22, 163, 74, 0.1);
        }

        .profile-edit-section-icon-yellow {
            color: #b77905;
            background: rgba(249, 168, 37, 0.15);
        }

        .profile-edit-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .profile-edit-grid label {
            display: grid;
            gap: 8px;
            min-width: 0;
        }

        .profile-edit-grid label span {
            color: #475569;
            font-size: 0.72rem;
            font-weight: 950;
            letter-spacing: 0.07em;
            text-transform: uppercase;
        }

        .profile-edit-grid label span b {
            color: #e53935;
        }

        .profile-edit-grid input,
        .profile-edit-grid select,
        .profile-edit-grid textarea {
            width: 100%;
            min-height: 48px;
            padding: 0 14px;
            border: 1px solid #cbd5e1;
            border-radius: 16px;
            background: #ffffff;
            color: #0f172a;
            font: inherit;
            font-size: 0.9rem;
            font-weight: 750;
            outline: none;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.035);
            transition: 0.2s ease;
        }

        .profile-edit-grid textarea {
            min-height: 110px;
            padding: 13px 14px;
            resize: vertical;
            line-height: 1.5;
        }

        .profile-edit-grid input:hover,
        .profile-edit-grid select:hover,
        .profile-edit-grid textarea:hover {
            border-color: rgba(21, 101, 192, 0.45);
            box-shadow: 0 12px 28px rgba(21, 101, 192, 0.08);
        }

        .profile-edit-grid input:focus,
        .profile-edit-grid select:focus,
        .profile-edit-grid textarea:focus {
            border-color: rgba(21, 101, 192, 0.62);
            box-shadow: 0 0 0 4px rgba(21, 101, 192, 0.1);
        }

        .profile-edit-full-field {
            grid-column: 1 / -1;
        }

        .profile-edit-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 22px;
            background:
                radial-gradient(circle at 95% 0%, rgba(249, 168, 37, 0.12), transparent 32%),
                linear-gradient(135deg, #ffffff, #f8fafc);
        }

        .profile-edit-action-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
        }

        .profile-edit-muted-btn {
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

        .profile-edit-muted-btn:hover {
            border-color: rgba(21, 101, 192, 0.45);
            color: #1565C0;
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(15, 23, 42, 0.08);
        }

        @media (max-width: 900px) {
            .profile-edit-hero-main,
            .profile-edit-card-head,
            .profile-edit-actions {
                flex-direction: column;
                align-items: flex-start;
            }

            .profile-edit-hero-actions,
            .profile-edit-action-buttons {
                width: 100%;
                display: grid;
                grid-template-columns: 1fr;
            }

            .profile-edit-grid {
                grid-template-columns: 1fr;
            }

            .profile-edit-back-btn,
            .profile-edit-muted-btn,
            .profile-edit-submit-btn {
                width: 100%;
            }
        }

        @media (max-width: 640px) {
            .profile-edit-hero,
            .profile-edit-card {
                border-radius: 24px;
            }

            .profile-edit-hero,
            .profile-edit-card-head,
            .profile-edit-section,
            .profile-edit-actions {
                padding: 16px;
            }

            .profile-edit-hero h2 {
                font-size: 1.65rem;
            }

            .profile-edit-subtitle {
                font-size: 0.84rem;
                line-height: 1.62;
            }
        }
    </style>
@endsection
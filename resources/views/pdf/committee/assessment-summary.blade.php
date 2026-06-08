<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rangkuman Assessment - {{ $student->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #000;
            margin: 15mm 15mm 12mm 20mm;
        }

        /* ── HEADER ── */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        .header-table td { padding: 0; vertical-align: middle; }

        .td-logo { width: 65px; text-align: left; }
        .td-logo img { width: 58px; height: auto; }

        .td-center { text-align: center; }
        .td-center .line1 {
            font-size: 12pt;
            font-weight: bold;
            color: #1a3a6b;
            text-transform: uppercase;
        }
        .td-center .line2 {
            font-size: 14pt;
            font-weight: bold;
            color: #c0392b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .td-address {
            width: 155px;
            text-align: right;
            font-size: 7pt;
            line-height: 1.6;
            color: #333;
        }

        .header-border {
            border-top: 4px solid #1a3a6b;
            margin-top: 6px;
            margin-bottom: 3px;
        }

        .header-sk {
            text-align: center;
            font-size: 7pt;
            font-weight: bold;
            color: #333;
        }

        /* ── JUDUL ── */
        .title-wrap {
            text-align: center;
            margin: 14px 0 12px;
        }
        .title-wrap .t1 {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
        }
        .title-wrap .t2 { font-size: 10pt; margin-top: 2px; }
        .title-wrap .t3 {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 4px;
        }

        /* ── DIVIDER ── */
        .divider { border: none; border-top: 1px solid #bbb; margin: 10px 0; }

        /* ── SECTION LABEL ── */
        .section-label {
            font-size: 10pt;
            font-weight: bold;
            margin: 12px 0 4px;
            color: #1a3a6b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-left: 3px solid #1a3a6b;
            padding-left: 6px;
        }

        /* ── TABEL BIODATA ── */
        .biodata-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
            margin-bottom: 4px;
        }
        .biodata-table td { padding: 3px 0; vertical-align: top; }
        .biodata-table .b-label { width: 145px; font-weight: bold; }
        .biodata-table .b-colon { width: 14px; }

        /* ── TABEL ASESOR ── */
        .assessor-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
            margin-bottom: 4px;
        }
        .assessor-table td { padding: 3px 0; vertical-align: top; }
        .assessor-table .b-label { width: 145px; font-weight: bold; }
        .assessor-table .b-colon { width: 14px; }

        /* ── TABEL DETAIL MK ── */
        .mk-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin: 6px 0 10px;
        }
        .mk-table th {
            background-color: #1a3a6b;
            color: #fff;
            padding: 5px 6px;
            text-align: center;
            border: 1px solid #1a3a6b;
        }
        .mk-table td {
            padding: 3px 6px;
            border: 1px solid #999;
            vertical-align: middle;
        }
        .mk-table td.c { text-align: center; }
        .mk-table tbody tr:nth-child(even) { background-color: #f4f7fb; }
        .mk-table tfoot td {
            font-weight: bold;
            background-color: #dce8f7;
            border: 1px solid #999;
            padding: 3px 6px;
        }



        /* ── PENUTUP ── */
        .closing { margin: 8px 0 0; font-size: 10pt; }

        /* ── TANGGAL ── */
        .ttd-date {
            text-align: right;
            margin-top: 16px;
            margin-bottom: 10px;
            font-size: 10pt;
        }

        /* ── TANDA TANGAN ── */
        .ttd-outer {
            width: 100%;
            border-collapse: collapse;
        }
        .ttd-outer td {
            width: 50%;
            vertical-align: top;
            padding: 0;
        }

        .ttd-block { text-align: center; margin-bottom: 16px; }
        .ttd-block .jabatan { min-height: 32pt; font-size: 10pt; }
        .ttd-block .ttd-space { height: 44px; }
        .ttd-block .nama {
            font-weight: bold;
            text-decoration: underline;
            font-size: 10pt;
        }
        .ttd-block .nip { font-size: 9pt; }

        /* ── TEMBUSAN ── */
        .tembusan {
            margin-top: 16px;
            font-size: 9pt;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
        .tembusan ol { margin-left: 16px; margin-top: 2px; }
    </style>
</head>
<body>

{{-- HEADER --}}
<table class="header-table">
    <tr>
        <td class="td-logo">
            <img src="{{ public_path('images/logo.png') }}" alt="Logo">
        </td>
        <td class="td-center">
            <div class="line1">Institut Teknologi &amp; Bisnis</div>
            <div class="line2">Bina Sarana Global</div>
        </td>
        <td class="td-address">
            Jl. Aria Santika No. 43A<br>
            Margasari, Karawaci<br>
            Kota Tangerang, 15113<br>
            Telp: 021-5522727
        </td>
    </tr>
</table>
<div class="header-border"></div>
<div class="header-sk">SK. KEMDIKBUD RI. NO. 23/E/O/2021</div>

{{-- JUDUL --}}
<div class="title-wrap">
    <div class="t1">Rangkuman Hasil Assessment RPL</div>
    <div class="t2">Nomor Aplikasi: {{ $application->application_number }}</div>
    <div class="t3">Program Rekognisi Pembelajaran Lampau (RPL)</div>
</div>

<hr class="divider">

{{-- DATA MAHASISWA --}}
<div class="section-label">Data Mahasiswa</div>
<table class="biodata-table">
    <tr>
        <td class="b-label">Nama Mahasiswa</td>
        <td class="b-colon">:</td>
        <td><strong>{{ $student->name }}</strong></td>
    </tr>
    <tr>
        <td class="b-label">Nomor Aplikasi</td>
        <td class="b-colon">:</td>
        <td>{{ $application->application_number }}</td>
    </tr>
    <tr>
        <td class="b-label">Program Studi</td>
        <td class="b-colon">:</td>
        <td>{{ $application->studyProgram->name }}</td>
    </tr>
    <tr>
        <td class="b-label">Jenis RPL</td>
        <td class="b-colon">:</td>
        <td>{{ strtoupper($application->rpl_type) }}</td>
    </tr>
    <tr>
        <td class="b-label">Tanggal Cetak</td>
        <td class="b-colon">:</td>
        <td>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
    </tr>
</table>

{{-- DATA ASESOR --}}
@if ($assessment && $assessment->assessor)
<div class="section-label">Data Asesor</div>
<table class="assessor-table">
    <tr>
        <td class="b-label">Nama Asesor</td>
        <td class="b-colon">:</td>
        <td><strong>{{ $assessment->assessor->user->name }}</strong></td>
    </tr>
    @if ($assessment->assessed_at)
    <tr>
        <td class="b-label">Tanggal Assessment</td>
        <td class="b-colon">:</td>
        <td>{{ \Carbon\Carbon::parse($assessment->assessed_at)->translatedFormat('d F Y') }}</td>
    </tr>
    @endif
    @if ($assessment->notes)
    <tr>
        <td class="b-label">Catatan Asesor</td>
        <td class="b-colon">:</td>
        <td>{{ $assessment->notes }}</td>
    </tr>
    @endif
</table>
@endif

{{-- DETAIL MATA KULIAH --}}
<div class="section-label">Detail Mata Kuliah yang Diakui</div>
<table class="mk-table">
    <thead>
        <tr>
            <th style="width:4%">No</th>
            <th style="width:14%">Jenis Sumber</th>
            <th style="width:14%">Kode MK</th>
            <th style="width:42%">Nama Mata Kuliah / Pengalaman</th>
            <th style="width:10%">SKS</th>
            <th style="width:16%">Status</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; $totalSKS = 0; @endphp
        @foreach ($mappings as $mapping)
            @if ($mapping->is_recognized && $mapping->course)
            <tr>
                <td class="c">{{ $no++ }}</td>
                <td class="c">{{ $mapping->application_a1_course_id ? 'Mata Kuliah' : 'Pengalaman' }}</td>
                <td class="c">{{ $mapping->course->code ?? '-' }}</td>
                <td>{{ $mapping->course->name ?? ($mapping->applicationA2LearningExperience->title ?? '-') }}</td>
                <td class="c">{{ $mapping->course->sks ?? 0 }}</td>
                <td class="c">Diakui</td>
            </tr>
            @php $totalSKS += $mapping->course->sks ?? 0; @endphp
            @endif
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="c">Total</td>
            <td>{{ $mappings->where('is_recognized', true)->count() }} Mata Kuliah Diakui</td>
            <td class="c">{{ $totalSKS }} SKS</td>
            <td></td>
        </tr>
    </tfoot>
</table>

{{-- CATATAN COMMITTEE --}}
@if ($application->review_notes)
<div class="section-label">Catatan Committee</div>
<div class="closing">{{ $application->review_notes }}</div>
@endif

{{-- PENUTUP --}}
<div class="closing" style="margin-top: 10px;">
    Dokumen ini merupakan rangkuman resmi hasil assessment RPL yang telah diverifikasi dan disetujui oleh pihak yang berwenang.
</div>

{{-- TANGGAL --}}
<div class="ttd-date">
    Tangerang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
</div>

{{-- TANDA TANGAN --}}
<table class="ttd-outer">
    <tr>
        {{-- KIRI: Kaprodi & Dekan --}}
        <td>
            <div class="ttd-block">
                <div class="jabatan">Ketua Program Studi<br>{{ $application->studyProgram->name }},</div>
                <div class="ttd-space"></div>
                <div class="nama">(............................................)</div>
                <div class="nip">NIP.</div>
            </div>
            <div class="ttd-block">
                <div class="jabatan">Dekan Fakultas,</div>
                <div class="ttd-space"></div>
                <div class="nama">(............................................)</div>
                <div class="nip">NIP.</div>
            </div>
        </td>

        {{-- KANAN: Warek & Rektor --}}
        <td>
            <div class="ttd-block">
                <div class="jabatan">Wakil Rektor Bidang Akademik,</div>
                <div class="ttd-space"></div>
                <div class="nama">(............................................)</div>
                <div class="nip">NIP.</div>
            </div>
            <div class="ttd-block">
                <div class="jabatan">Rektor,</div>
                <div class="ttd-space"></div>
                <div class="nama">(............................................)</div>
                <div class="nip">NIP.</div>
            </div>
        </td>
    </tr>
</table>

{{-- TEMBUSAN --}}
<div class="tembusan">
    <strong>Tembusan:</strong>
    <ol>
        <li>Wakil Rektor Bidang Akademik</li>
        <li>Dekan Fakultas</li>
        <li>Ketua Program Studi {{ $application->studyProgram->name }}</li>
        <li>Asesor yang Bersangkutan</li>
        <li>Arsip</li>
    </ol>
</div>

</body>
</html>
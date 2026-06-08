<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Keputusan Rektor</title>
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

        .header-table td {
            padding: 0;
            vertical-align: middle;
        }

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

        /* ── BARIS KONTEN ── */
        .row-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .row-table td { padding: 0; vertical-align: top; }
        .col-label { width: 115px; font-weight: bold; }
        .col-colon { width: 14px; text-align: center; }

        /* ── BIODATA ── */
        .biodata-wrap { margin-left: 129px; margin-bottom: 10px; }
        .biodata-table { width: 100%; border-collapse: collapse; }
        .biodata-table td { padding: 1px 0; vertical-align: top; }
        .biodata-table .b-label { width: 145px; }
        .biodata-table .b-colon { width: 14px; }

        /* ── DIVIDER ── */
        .divider { border: none; border-top: 1px solid #bbb; margin: 10px 0; }

        /* ── TABEL MK ── */
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
        /*
         * Tiap blok TTD pakai padding-top yang sama (fixed height ruang tanda tangan).
         * Jabatan dibatasi tingginya dengan min-height, biar semua blok sejajar
         * meski teksnya beda panjang.
         */
        .ttd-outer {
            width: 100%;
            border-collapse: collapse;
        }
        .ttd-outer td {
            width: 50%;
            vertical-align: top;
            padding: 0;
        }

        .ttd-block {
            text-align: center;
            margin-bottom: 16px;
        }

        /* Jabatan diberi min-height 2 baris biar semua sejajar */
        .ttd-block .jabatan {
            min-height: 32pt;
            font-size: 10pt;
        }

        /* Ruang kosong tanda tangan — fixed 44px biar semua kolom sama */
        .ttd-block .ttd-space {
            height: 44px;
        }

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
    <div class="t1">Surat Keputusan Rektor</div>
    <div class="t2">Nomor: SK/RPL/{{ $application->application_number }}/{{ \Carbon\Carbon::now()->year }}</div>
    <div class="t3">Tentang Pengakuan Capaian Pembelajaran dan Konversi SKS<br>Program Rekognisi Pembelajaran Lampau (RPL)</div>
</div>

{{-- MENIMBANG --}}
<table class="row-table">
    <tr>
        <td class="col-label">Menimbang</td>
        <td class="col-colon">:</td>
        <td>bahwa berdasarkan hasil penilaian capaian pembelajaran yang telah dilaksanakan, mahasiswa berikut telah memenuhi syarat untuk mendapatkan pengakuan kredit melalui mekanisme Rekognisi Pembelajaran Lampau (RPL).</td>
    </tr>
</table>

{{-- MENGINGAT --}}
<table class="row-table">
    <tr>
        <td class="col-label">Mengingat</td>
        <td class="col-colon">:</td>
        <td>Peraturan Menteri Pendidikan dan Kebudayaan Republik Indonesia Nomor 41 Tahun 2021 tentang Rekognisi Pembelajaran Lampau.</td>
    </tr>
</table>

{{-- MEMPERHATIKAN --}}
<table class="row-table">
    <tr>
        <td class="col-label">Memperhatikan</td>
        <td class="col-colon">:</td>
        <td>Hasil asesmen capaian pembelajaran atas nama mahasiswa yang bersangkutan pada Program Studi {{ $application->studyProgram->name }}.</td>
    </tr>
</table>

<hr class="divider">

{{-- MENETAPKAN --}}
<table class="row-table">
    <tr>
        <td class="col-label">Menetapkan</td>
        <td class="col-colon">:</td>
        <td><strong>Keputusan Rektor tentang Pengakuan Capaian Pembelajaran dan Konversi SKS atas nama:</strong></td>
    </tr>
</table>

{{-- BIODATA --}}
<div class="biodata-wrap">
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
    </table>
</div>

{{-- RINCIAN MK --}}
<table class="row-table">
    <tr>
        <td class="col-label">Dengan rincian</td>
        <td class="col-colon">:</td>
        <td>mata kuliah yang diakui sebagai berikut:</td>
    </tr>
</table>

<table class="mk-table">
    <thead>
        <tr>
            <th style="width:5%">No</th>
            <th style="width:17%">Kode MK</th>
            <th style="width:58%">Mata Kuliah</th>
            <th style="width:20%">SKS</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @foreach ($mappings as $mapping)
            @if ($mapping->is_recognized && $mapping->course)
            <tr>
                <td class="c">{{ $no++ }}</td>
                <td class="c">{{ $mapping->course->code }}</td>
                <td>{{ $mapping->course->name }}</td>
                <td class="c">{{ $mapping->course->sks }}</td>
            </tr>
            @endif
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" class="c">Total</td>
            <td>{{ $mappings->where('is_recognized', true)->count() }} Mata Kuliah Diakui</td>
            <td class="c">{{ $mappings->where('is_recognized', true)->sum(fn($m) => $m->course->sks ?? 0) }} SKS</td>
        </tr>
    </tfoot>
</table>

{{-- PENUTUP --}}
<div class="closing">
    Keputusan ini berlaku sejak tanggal ditetapkan dan akan ditinjau kembali apabila terdapat kekeliruan dalam penetapannya.
</div>

{{-- TANGGAL — di atas semua TTD, rata kanan --}}
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
        <li>Arsip</li>
    </ol>
</div>

</body>
</html>
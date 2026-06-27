<p align="center">
  <img src="public/images/logo.png" width="150" alt="G-RPL Logo">
</p>

<h1 align="center">G-RPL (Global Rekognisi Pembelajaran Lampau)</h1>

<p align="center">
  <strong>Sistem Digital Terintegrasi Rekognisi Pembelajaran Lampau Berbasis Web</strong>
</p>

<p align="center">
  <a href="#tentang-project">Tentang Project</a> •
  <a href="#fitur-utama">Fitur Utama</a> •
  <a href="#alur-sistem-bisnis">Alur Sistem</a> •
  <a href="#modul-sistem">Modul Sistem</a> •
  <a href="#tech-stack">Tech Stack</a> •
  <a href="#instalasi">Instalasi</a> •
  <a href="#dokumentasi">Dokumentasi</a> •
  <a href="#contributors">Contributors</a>
</p>

---

## 🚀 Tentang Project

**Global RPL (G-RPL)** adalah sistem informasi akademik modern yang dirancang khusus untuk mendigitalkan dan mengelola proses **Rekognisi Pembelajaran Lampau (RPL)**. Sistem ini memfasilitasi pengakuan atas capaian pembelajaran seseorang yang diperoleh dari pendidikan formal, nonformal, informal, dan/atau pengalaman kerja sebagai dasar untuk melanjutkan pendidikan formal.

Dikembangkan dengan prinsip desain *Clean, Modern, dan Profesional*, G-RPL mengintegrasikan seluruh entitas yang terlibat (Pendaftar, Komite RPL, dan Asesor) ke dalam satu platform yang terpusat, menghilangkan kebutuhan administrasi berbasis kertas, dan mempercepat proses evaluasi.

## ✨ Fitur Utama

*   **Pendaftaran 100% Digital:** Pendaftar dapat mengunggah portofolio, sertifikat, dan bukti pengalaman kerja secara online.
*   **Dukungan Tipe RPL A1, A2, Maupun Hybrid (Kombinasi A1 dan A2):** Mengakomodasi berbagai skema RPL sesuai standar akademik.
*   **Role-Based Access Control (RBAC):** Portal dan dashboard spesifik untuk masing-masing peran (Applicant, Committee, Assessor, Admin).
*   **Sistem Penilaian Terstruktur:** Asesor memiliki modul khusus untuk mengevaluasi dan memetakan pengalaman kerja ke dalam SKS/Mata Kuliah.
*   **Pelacakan Status Real-time:** Pendaftar dapat memantau status aplikasi mereka dari `Draft` hingga `Approved` atau `Rejected`.
*   **Cetak Dokumen Resmi:** Komite RPL dapat mencetak SK Rektor dan dokumen hasil assessment setelah proses validasi akhir selesai.
*   **UI/UX Modern:** Menggunakan pendekatan Glassmorphism, animasi halus, dan tata letak yang bersih untuk meningkatkan pengalaman pengguna (Detail di `DESIGN.md`).
*   **API Ready:** Arsitektur backend berbasis REST API, siap untuk integrasi dengan aplikasi mobile atau sistem pihak ketiga di masa depan.

## 🔄 Alur Sistem (Bisnis Flow)

Sistem G-RPL digerakkan oleh status aplikasi yang ketat untuk memastikan integritas data dan proses evaluasi yang benar:

1.  **Draft:** Pendaftar (Applicant) membuat *Application Header* awal (Tipe A1/A2/Hybrid). Di tahap ini, pendaftar melengkapi profil dan dokumen pendukung.
2.  **Submitted:** Pendaftar mengirimkan aplikasi untuk ditinjau. Data terkunci dan tidak dapat diubah oleh pendaftar.
3.  **Under Review (Komite RPL):** Komite RPL melakukan verifikasi administratif terhadap kelengkapan dokumen. Jika valid, komite menugaskan Asesor yang sesuai.
4.  **Under Assessment (Asesor):** Asesor yang ditugaskan melakukan evaluasi akademik/profesional terhadap dokumen pendaftar dan memetakannya ke mata kuliah yang relevan. Status berubah menjadi `Assessed`.
5.  **Approved (Validasi Akhir oleh Komite RPL):** Komite RPL meninjau hasil penilaian asesor dan memberikan validasi akhir bahwa pengajuan telah disetujui. Setelah divalidasi, dokumen resmi (SK Rektor dan hasil assessment) siap untuk dicetak.

## 📦 Modul Sistem

Project ini dibagi menjadi beberapa modul API utama:

*   **Applicant Module:** Registrasi, pembuatan aplikasi (A1/A2/Hybrid), upload dokumen, pelacakan status.
*   **Staff Module:** Verifikasi dokumen administrasi, dan penugasan asesor.
*   **Assessor Module:** Penilaian portofolio, evaluasi pengalaman kerja, pemetaan kompetensi ke kurikulum (Mata Kuliah/SKS).
*   **Committee Module:** Validasi akhir pengajuan, dan cetak SK Rektor serta dokumen hasil assessment.
*   **Admin Module:** Manajemen program studi, manajemen pengguna, konfigurasi sistem.

## 💻 Tech Stack

*   **Backend Framework:** Laravel 13.x (PHP 8.5+)
*   **Frontend:** React (App.jsx) terintegrasi via Laravel Vite
*   **Styling:** Tailwind CSS (Kustomisasi spesifik di `app.css`)
*   **Database:** MySQL / PostgreSQL
*   **Authentication:** Laravel Sanctum (Token-based API Auth)

## 🛠️ Instalasi

Ikuti langkah-langkah berikut untuk menjalankan project di lingkungan lokal:

1.  **Clone repositori:**
    ```bash
    git clone <repository-url>
    cd G-RPL
    ```

2.  **Install dependensi PHP:**
    ```bash
    composer install
    ```

3.  **Install dependensi Node.js:**
    ```bash
    npm install
    ```

4.  **Konfigurasi Environment:**
    Salin file `.env.example` menjadi `.env` lalu generate application key.
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Penting: Sesuaikan konfigurasi database (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) di file `.env`.*

5.  **Migrasi Database dan Seeding:**
    Jalankan migrasi untuk membuat struktur tabel dan seeding data awal (Role, Admin, Prodi).
    ```bash
    php artisan migrate --seed
    ```

6.  **Jalankan Server Development:**
    Buka dua terminal terpisah.
    
    Terminal 1 (Backend API):
    ```bash
    php artisan serve
    ```
    Terminal 2 (Frontend Build/Vite):
    ```bash
    npm run dev
    ```

## 📚 Dokumentasi Lengkap

Untuk memahami struktur API dan routing lebih detail, silakan baca dokumentasi teknis di dalam folder `/Documentation`:

*   [`Assessor_Module_API_Documentation_Full.md`](Documentation/Assessor_Module_API_Documentation_Full.md) - Endpoint khusus penugasan dan penilaian oleh Asesor.
*   [`Backend_application_api.md`](Documentation/Backend_application_api.md) - Alur pembuatan dan pengiriman aplikasi pendaftar.
*   [`Backend_profile_api.md`](Documentation/Backend_profile_api.md) - Endpoint manajemen profil pengguna.
*   [`Backend_StaffRPL_Api.md`](Documentation/Backend_StaffRPL_Api.md) - Endpoint pengelolaan data master oleh Admin/Staff.
*   [`Committee_Api_Docs.md`](Documentation/Committee_Api_Docs.md) - Endpoint verifikasi dan validasi akhir oleh Komite RPL.
*   [`Frontend_routing.md`](Documentation/Frontend_routing.md) - Struktur routing untuk antarmuka frontend (React).
*   [`DESIGN.md`](DESIGN.md) - Panduan sistem desain (Warna, Tipografi, Komponen UI).

## 👥 Contributors

Terima kasih kepada semua yang telah berkontribusi dalam pengembangan G-RPL:

<table>
  <tr>
    <td align="center">
      <a href="https://github.com/Codenames-Ren">
        <img src="https://avatars.githubusercontent.com/u/187786585?v=4" width="80px;" alt="Bayu Sukma"/><br />
        <sub><b>Bayu Sukma</b></sub>
      </a><br />
      <sub>Backend Developer</sub>
    </td>
    <td align="center">
      <a href="https://github.com/Rafreaks06">
        <img src="https://avatars.githubusercontent.com/u/200058222?v=4" width="80px;" alt="Muhammad Raffi Ar-Rosyid"/><br />
        <sub><b>Muhammad Raffi Ar-Rosyid</b></sub>
      </a><br />
      <sub>Frontend Developer</sub>
    </td>
    <td align="center">
      <a href="https://github.com/Diasmyri">
        <img src="https://avatars.githubusercontent.com/u/199187325?v=4" width="80px;" alt="Diasmyriii"/><br />
        <sub><b>Diasmyriii</b></sub>
      </a><br />
      <sub>Frontend Developer</sub>
    </td>
  </tr>
</table>

## 📄 Lisensi

Hak cipta dilindungi. Project ini adalah perangkat lunak berpemilik (Proprietary). Tidak untuk didistribusikan tanpa izin tertulis.
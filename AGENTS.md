# SIPDIRA SPASIAL — CORE ENGINEERING & DESIGN BLUEPRINT
> **Panduan Fundamental Proyek WebGIS BWS Kalimantan I (Kementerian Pekerjaan Umum)**
> Berkas ini dibaca secara otomatis oleh AI di setiap sesi kerja untuk menjamin kualitas kode, keamanan, dan desain UI/UX selalu 100% berstandar Senior Software Engineer & UI/UX Architect kelas dunia.

---

## 1. IDENTITAS & KONTEKS SISTEM

* **Nama Sistem**: **SIPDIRA SPASIAL** (*Sistem Informasi Peta Daerah Irigasi Berbasis Data Spasial*)
* **Instansi Induk**: **Kementerian Pekerjaan Umum (PU)**
* **Unit Kerja Teknis**: **Balai Wilayah Sungai (BWS) Kalimantan I Pontianak**, Direktorat Jenderal Sumber Daya Air
* **Fokus Utama**: Pemetaan geospasial interaktif daerah irigasi (DIR), lahan baku, fungsional, potensial, jaringan saluran, dan infrastruktur bangunan air di Kalimantan Barat.
* **Teknologi Stack**:
  * **Backend**: Laravel 10 (PHP 8.2+), MySQL/PostgreSQL
  * **Frontend / GIS**: Leaflet.js, OpenStreetMap (Default Basemap), Google Hybrid/Streets
  * **UI Framework**: Bootstrap + Custom Clean Spatial Design System (Terinspirasi 1:1 dari QROP Spasial Pontianak)
  * **Autentikasi & RBAC**: Multi-role (Super Admin & Admin Operasional / Staff)

---

## 2. STANDAR UI/UX & SISTEM DESAIN KELAS DUNIA (1:1 ALA QROP SPASIAL)

Setiap pembuatan atau pembaruan antarmuka (UI) wajib mengikuti aturan desain baku ini:

### A. Tata Letak (Layout & Proportions)
1. **Wadah Halaman (Page Container)**:
   * Menggunakan kontainer fluida modern: `max-width: 1600px; margin: 0 auto; padding: 30px clamp(20px, 4vw, 72px) 40px;`.
   * Hindari membungkus halaman ke ukuran kaku kecil (seperti 1200px atau 1240px) agar tampilan tidak terjepit di monitor besar.
2. **Workspace Peta & Sidebar**:
   * Wajib menggunakan **CSS Grid**: `grid-template-columns: 285px minmax(0, 1fr); gap: 18px; align-items: stretch;`.
   * Lebar Sidebar kiri dikunci pada **`285px`**.
   * Tinggi kanvas peta diatur fluida: `clamp(540px, 62vh, 800px)` dengan posisi `relative`. Sidebar dan peta dijamin berakhir pada garis horizontal yang sama persis (stretch rata).
3. **Bagian Informasi Layanan & Kontak**:
   * Bagian *Tentang Layanan* menggunakan grid 2 kolom: Kartu logo brand di kiri (`280px`) dan teks penjelasan di kanan (`minmax(0, 1fr)`).
   * Banner *Kring Pengawasan / Kontak* diletakkan di atas footer dengan kartu aksi cepat (WhatsApp & Instagram) ber-border halus.

### B. Palet Warna Resmi (Official PU & Spatial Tokens)
* **PU Navy (Utama/Primer)**: `#10143a` (Warna utama navbar, hero card, dan tombol aksi dominan)
* **PU Gold/Amber (Aksen Resmi)**: `#f59e0b` / `#fad605` (Aksen teks spasial, bintang, dot indikator)
* **Cartographic Green (Irigasi & Pertanian)**: `#10b981` / `#15803d` (Aksen layer aktif, status operasional, ikon WhatsApp)
* **Netral & Teks**:
  * Judul Utama (*Heading*): `#0f172a` (Slate 900)
  * Teks Tubuh (*Body*): `#334155` (Slate 700)
  * Teks Pendukung (*Muted*): `#64748b` (Slate 500)
  * Garis Pembatas (*Borders*): `#e2e8f0` (Slate 200)
  * Background Halaman (*Paper*): `#f8fafc` (Slate 50)
  * Kartu / Panel (*Surface*): `#ffffff` murni

### C. Tipografi & Hierarki Teks
* Font utama: `'Plus Jakarta Sans', system-ui, -apple-system, sans-serif`.
* **Skala Font**:
  * Hero Display: `clamp(24px, 2.3vw, 32px)` font-weight 700.
  * H1 / Title: `20px - 22px` font-weight 800.
  * Card Heading: `14px - 15px` font-weight 700.
  * Body Text: `12.5px - 13.5px` line-height 1.6.
  * Micro-Labels & Badges: `8.5px - 10.5px` uppercase bold.

### D. Tombol & Mikro-Interaksi
* **Tombol Standar QROP**: `border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 18px; font-weight: 600; font-size: 13.5px; background: #ffffff; color: #0f172a;`.
* Efek hover: `transform: translateY(-1px); box-shadow: 0 3px 10px rgba(16, 20, 58, 0.08); transition: all 0.2s ease;`.
* Kartu panel: Sudut tumpul halus `border-radius: 14px`, border `1px solid #e2e8f0`, bayangan lembut `box-shadow: 0 2px 12px rgba(15, 23, 42, 0.03);`.

---

## 3. STANDAR REKAYASA KODE & KEAMANAN (BACKEND & ARSITEKTUR)

1. **Prinsip Non-Breaking Changes**:
   * Kode baru TIDAK BOLEH merusak fungsionalitas, route, data model, atau layer peta yang telah berjalan.
   * Pertahankan dokumentasi, komentar, dan nama helper yang sudah ada.
2. **Keamanan & Validasi**:
   * Seluruh form mutasi data (POST/PUT/DELETE) wajib memiliki `@csrf`.
   * Sanitasi semua input untuk mencegah celah XSS dan SQL Injection.
   * Gunakan hak akses ketat (RBAC):
     * **Super Admin**: Akses penuh ke manajemen pengguna, konfigurasi sistem, backup data, dan menu *Log Aktivitas Admin*.
     * **Staff / Admin Operasional**: Khusus input/edit data irigasi spasial; tab *Log Aktivitas* disembunyikan/dibatasi.
3. **Kinerja Database**:
   * Hindari N+1 query: gunakan *eager loading* `with(['kategori', 'kabupaten'])`.
   * Simpan file GIS dan upload dokumen dengan nama file yang aman dan terstruktur di `storage/app/public`.

---

## 4. PERILAKU KERJA OTOMATIS AI (SENIOR DEVELOPER HABITS)

Setiap kali pengguna memberikan perintah (walaupun hanya 1 kalimat pendek), AI harus otomatis:
1. **Analisis Mendalam**: Memeriksa file terkait sebelum melakukan penulisan kode.
2. **Kualitas Tanpa Kompromi**: Menghasilkan kode yang lengkap (bukan placeholder/todo), responsif di layar ponsel hingga monitor 4K.
3. **Verifikasi Mandiri**: Menguji sintaks kode (`php -l`) dan membersihkan cache view Laravel (`php artisan view:clear`) setelah setiap perubahan.
4. **Disiplin Git**: Melakukan commit rapi dengan format *Conventional Commits* (`feat:`, `fix:`, `style:`, `refactor:`) dan langsung menyinkronkannya ke GitHub.

---

*Berkas ini merupakan acuan mutlak tata kelola pengembangan perangkat lunak SIPDIRA SPASIAL BWS Kalimantan I.*


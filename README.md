# GRES-AKTIF &mdash; Gresik Asset Activation & Community Intelligence Platform

> *"Aset yang Diam, Menjadi Ekonomi."*

**GRES-AKTIF** adalah platform *Smart Governance* berbasis AI & *Crowdsourcing* yang dirancang untuk mengidentifikasi aset desa/daerah yang tidur, terbengkalai, atau kurang produktif di seluruh 16 kecamatan Kabupaten Gresik, menganalisis kelayakan ekonomi, mengumpulkan konsensus kebutuhan warga, dan mengaktifkan aset menjadi fungsi produktif (Sentra UMKM, Pusat Kuliner, Pertanian/Tambak, Balai Vokasi, Ekowisata).

---

## 🌟 Fitur Utama

1. **Peta Spasial GIS & Google Earth Satellite Mode (`/map`)**:
   - Engine Leaflet.js dengan citra satelit resolusi tinggi fotorealistik (Google Earth Hybrid).
   - Marker clustering, filter multi-sektor, dan layer switcher (Satelit vs Peta Jalan).
2. **Kajian Kelayakan & Potensi Aset (AI Decision Support)**:
   - Algoritma indeks multivariat (Tata Ruang, Aksesibilitas, Kebutuhan Warga, Nilai Ekonomi BUMDes).
   - Rekomendasi fungsi optimal dan simulator model bisnis.
3. **Crowdsourcing Aspirasi & Ide Warga**:
   - Form pengajuan gagasan pemanfaatan aset langsung dari peta atau detail aset.
   - Upvoting ide terpopuler dan agregasi konsensus komunitas otomatis.
   - Reward gamifikasi (+10 Poin Warga & Lencana Kontribusi).
4. **Alur Tata Kelola Multi-Level (Role-Based)**:
   - **Masyarakat / Warga**: Melaporkan aset tidur, menyumbang ide, voting gagasan.
   - **Pemerintah Desa (Pemdes)**: Verifikasi lapangan, kelayakan BUMDes, manajemen aset desa.
   - **Kecamatan**: Agregasi desa, perbandingan potensi antar desa, prioritas usulan.
   - **Kabupaten (Bappedalitbang & Dinas PMD)**: Macro GIS radar, alokasi APBD/CSR, monitoring realisasi proyek.

---

## 🛠️ Tech Stack

- **Backend**: Laravel 12 (PHP 8.3+)
- **Frontend**: Livewire 3, Alpine.js, Tailwind CSS
- **Mapping & GIS**: Leaflet.js, Leaflet.markercluster, Google Earth Satellite Tiles, OpenStreetMap
- **Charts**: ApexCharts
- **Database**: SQLite / MySQL ready

---

## 🚀 Panduan Instalasi

```bash
# 1. Clone repository
git clone https://github.com/esnpendosa/GRES-AKTIF.git
cd GRES-AKTIF

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Migrate database & seed authentic Gresik data
php artisan migrate --seed

# 5. Build frontend assets
npm run build

# 6. Jalankan local server
php artisan serve
```

Akses aplikasi melalui browser di `http://127.0.0.1:8000`.

---

## 👥 Akun Demo

| Role | Email | Password |
| :--- | :--- | :--- |
| **Masyarakat** | `masyarakat@gresaktif.id` | `password` |
| **Pemerintah Desa (Sukomulyo)** | `pemdes.sukomulyo@gresaktif.id` | `password` |
| **Kecamatan (Manyar)** | `kecamatan.manyar@gresaktif.id` | `password` |
| **Bappeda / Kabupaten** | `admin.bappeda@gresaktif.id` | `password` |

---

## 📄 Lisensi
Dikembangkan untuk Pemerintah Kabupaten Gresik, Bappedalitbang & Dinas PMD. Open Source under the MIT License.


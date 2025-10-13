# Laravel API SIG - Sistem Informasi Geografis

Project Laravel untuk API Sistem Informasi Geografis yang menyediakan endpoint untuk data wisata di Bali.

## Deskripsi

API ini menyediakan endpoint untuk mengakses data wisata dengan fitur pencarian, filter berdasarkan kategori, dan pagination. Data wisata mencakup informasi lokasi geografis (latitude dan longitude), nama, alamat, dan kategori wisata.

## Teknologi yang Digunakan

- **Laravel 12**: Framework PHP
- **SQLite**: Database
- **PHP 8.2+**: Bahasa pemrograman

## Instalasi dan Setup

1. **Clone repository**
   ```bash
   git clone <repository-url>
   cd laravel-sig
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Setup environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Setup database**
   ```bash
   php artisan migrate
   php artisan db:seed --class=WisataSeeder
   ```

5. **Jalankan server**
   ```bash
   php artisan serve
   ```

Server akan berjalan di `http://127.0.0.1:8000`

## API Endpoints

### Base URL
```
http://127.0.0.1:8000/api
```

### 1. API Lokasi Sederhana
**GET** `/lokasi`

Mengembalikan data lokasi sederhana dalam format JSON.

**Response:**
```json
[
  {
    "id": 1,
    "nama": "Gedung A",
    "lat": -8.65,
    "lng": 115.22
  },
  {
    "id": 2,
    "nama": "Perpustakaan",
    "lat": -8.64,
    "lng": 115.23
  }
]
```

### 2. Daftar Wisata
**GET** `/wisata`

Mengembalikan daftar semua wisata dengan pagination.

**Query Parameters:**
- `q` (optional): Pencarian berdasarkan nama wisata
- `kategori` (optional): Filter berdasarkan kategori (alam, budaya, belanja)
- `per_page` (optional): Jumlah item per halaman (default: 10)
- `page` (optional): Nomor halaman (default: 1)

**Contoh Request:**
```bash
# Semua wisata
curl "http://127.0.0.1:8000/api/wisata"

# Pencarian wisata
curl "http://127.0.0.1:8000/api/wisata?q=Pantai"

# Filter berdasarkan kategori
curl "http://127.0.0.1:8000/api/wisata?kategori=alam"

# Kombinasi pencarian dan filter
curl "http://127.0.0.1:8000/api/wisata?q=Pantai&kategori=alam"

# Pagination
curl "http://127.0.0.1:8000/api/wisata?per_page=5"
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "nama": "Pantai Kuta",
      "alamat": "Kuta, Badung, Bali",
      "lat": -8.7183,
      "lng": 115.1686,
      "kategori": "alam",
      "created_at": "2025-10-13T03:13:54.000000Z",
      "updated_at": "2025-10-13T03:13:54.000000Z"
    }
  ],
  "links": {
    "first": "http://127.0.0.1:8000/api/wisata?page=1",
    "last": "http://127.0.0.1:8000/api/wisata?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "per_page": 10,
    "to": 8,
    "total": 8
  }
}
```

### 3. Detail Wisata
**GET** `/wisata/{id}`

Mengembalikan detail wisata berdasarkan ID.

**Contoh Request:**
```bash
curl "http://127.0.0.1:8000/api/wisata/1"
```

**Response:**
```json
{
  "data": {
    "id": 1,
    "nama": "Pantai Kuta",
    "alamat": "Kuta, Badung, Bali",
    "lat": -8.7183,
    "lng": 115.1686,
    "kategori": "alam",
    "created_at": "2025-10-13T03:13:54.000000Z",
    "updated_at": "2025-10-13T03:13:54.000000Z"
  }
}
```

## Struktur Database

### Tabel `wisatas`
| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| nama | varchar | Nama wisata |
| alamat | text | Alamat wisata |
| lat | decimal(10,7) | Latitude |
| lng | decimal(10,7) | Longitude |
| kategori | varchar | Kategori wisata |
| created_at | timestamp | Waktu pembuatan |
| updated_at | timestamp | Waktu update |

## Data Sample

API ini sudah diisi dengan data sample wisata di Bali:
- Pantai Kuta
- Pura Tanah Lot
- Gunung Batur
- Taman Ujung Sukasada
- Pantai Sanur
- Pasar Sukawati
- Air Terjun Tegenungan
- Museum Puri Lukisan

## Testing API

### Menggunakan cURL

1. **Test endpoint lokasi sederhana:**
   ```bash
   curl "http://127.0.0.1:8000/api/lokasi"
   ```

2. **Test endpoint wisata:**
   ```bash
   curl "http://127.0.0.1:8000/api/wisata"
   ```

3. **Test pencarian:**
   ```bash
   curl "http://127.0.0.1:8000/api/wisata?q=Pantai"
   ```

4. **Test filter kategori:**
   ```bash
   curl "http://127.0.0.1:8000/api/wisata?kategori=alam"
   ```

### Menggunakan Browser

Buka URL berikut di browser:
- `http://127.0.0.1:8000/api/lokasi`
- `http://127.0.0.1:8000/api/wisata`
- `http://127.0.0.1:8000/api/wisata/1`

## Fitur

- ✅ Endpoint API sederhana `/api/lokasi`
- ✅ Endpoint daftar wisata `/api/wisata` dengan pagination
- ✅ Endpoint detail wisata `/api/wisata/{id}`
- ✅ Fitur pencarian berdasarkan nama (`q` parameter)
- ✅ Fitur filter berdasarkan kategori (`kategori` parameter)
- ✅ Pagination dengan metadata lengkap
- ✅ Response format JSON yang konsisten
- ✅ Database SQLite untuk kemudahan setup
- ✅ Sample data wisata Bali

## Struktur Project

```
laravel-sig/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       └── WisataController.php
│   │   └── Resources/
│   │       └── WisataResource.php
│   └── Models/
│       └── Wisata.php
├── database/
│   ├── migrations/
│   │   └── 2025_10_13_031240_create_wisatas_table.php
│   └── seeders/
│       └── WisataSeeder.php
├── routes/
│   └── api.php
└── README.md
```

## Author

Tugas Praktik Sistem Informasi Geografis - Laravel untuk API SIG
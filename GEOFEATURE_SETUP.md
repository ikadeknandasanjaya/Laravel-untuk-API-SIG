# GeoFeature System - Setup Guide

## Overview
Sistem GIS yang telah direfactor dari `Wisata` menjadi `GeoFeature` dengan dukungan untuk:
- ✅ Markers (Points of Interest)
- ✅ Polygons (Areas)
- ✅ Polylines (Routes/Roads)
- ✅ Circles (Coverage Areas)

Backend menggunakan **Laravel** dengan database **Supabase** (PostgreSQL).

---

## 🔧 Setup Instructions

### 1. Database Migration

```bash
# Drop old tables (jika ada)
php artisan migrate:fresh

# Atau jalankan migration baru
php artisan migrate
```

### 2. Seed Sample Data

```bash
php artisan db:seed --class=GeoFeatureSeeder
```

Ini akan membuat sample data:
- 5 markers (Tanah Lot, Ubud Monkey Forest, Kuta Beach, Mount Batur, Seminyak Square)
- 2 polygons (Nusa Dua Resort Area, Ubud Art District)
- 2 polylines (Jalan Raya Ubud, Jalan Sunset Road)
- 2 circles (Wifi Hotspot, Emergency Response Zone)

### 3. Environment Configuration

Update `.env` untuk Supabase:

```env
DB_CONNECTION=pgsql
DB_HOST=your-supabase-host.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-supabase-password

# API URL untuk frontend
VITE_API_URL=http://localhost:8000/api
```

### 4. Install Dependencies

```bash
# Backend
composer install

# Frontend
npm install
npm run dev
```

---

## 📡 API Endpoints

Base URL: `http://localhost:8000/api`

### Get All Features
```http
GET /geo-features
Query Parameters: ?type=marker|polygon|polyline|circle
```

### Get By Type
```http
GET /geo-features/markers
GET /geo-features/polygons
GET /geo-features/polylines
GET /geo-features/circles
```

### Get Single Feature
```http
GET /geo-features/{id}
```

### Create Feature
```http
POST /geo-features
Content-Type: application/json

{
  "nama": "Feature Name",
  "deskripsi": "Description",
  "kategori_id": 1,
  "geometry_type": "marker|polygon|polyline|circle",
  "coordinates": {...},
  "properties": {...}
}
```

**Marker Example:**
```json
{
  "nama": "Bali Zoo",
  "deskripsi": "Wildlife park",
  "kategori_id": 2,
  "geometry_type": "marker",
  "coordinates": {
    "lat": -8.5503,
    "lng": 115.2800
  },
  "properties": {
    "icon": "zoo",
    "color": "green"
  }
}
```

**Polygon Example:**
```json
{
  "nama": "Protected Forest",
  "deskripsi": "Conservation area",
  "kategori_id": 3,
  "geometry_type": "polygon",
  "coordinates": [
    {"lat": -8.5, "lng": 115.2},
    {"lat": -8.5, "lng": 115.3},
    {"lat": -8.6, "lng": 115.3},
    {"lat": -8.6, "lng": 115.2},
    {"lat": -8.5, "lng": 115.2}
  ],
  "properties": {
    "fillColor": "#00FF00",
    "fillOpacity": 0.3
  }
}
```

**Polyline Example:**
```json
{
  "nama": "Hiking Trail",
  "deskripsi": "Mountain trail",
  "kategori_id": 4,
  "geometry_type": "polyline",
  "coordinates": [
    {"lat": -8.5, "lng": 115.2},
    {"lat": -8.51, "lng": 115.21},
    {"lat": -8.52, "lng": 115.22}
  ],
  "properties": {
    "color": "#FF0000",
    "weight": 5
  }
}
```

**Circle Example:**
```json
{
  "nama": "Emergency Zone",
  "deskripsi": "Hospital coverage",
  "kategori_id": 5,
  "geometry_type": "circle",
  "coordinates": {
    "lat": -8.65,
    "lng": 115.22
  },
  "properties": {
    "radius": 1000,
    "color": "#FF0000",
    "fillOpacity": 0.2
  }
}
```

### Update Feature
```http
PUT /geo-features/{id}
Content-Type: application/json
```

### Delete Feature
```http
DELETE /geo-features/{id}
```

---

## 🎨 Frontend Integration

### Import Service
```javascript
import GeoFeatureService from '@/services/GeoFeatureService';
```

### Get All Markers
```javascript
const response = await GeoFeatureService.getMarkers();
if (response.success) {
  const markers = response.data;
}
```

### Create Marker
```javascript
const markerData = {
  nama: 'New Place',
  deskripsi: 'Description',
  kategori_id: 1,
  latitude: -8.65,
  longitude: 115.22
};

const response = await GeoFeatureService.createMarker(markerData);
```

### Create Polygon
```javascript
const polygonData = {
  nama: 'Area Name',
  deskripsi: 'Description',
  kategori_id: 2,
  coordinates: [
    { lat: -8.5, lng: 115.2 },
    { lat: -8.5, lng: 115.3 },
    { lat: -8.6, lng: 115.3 },
    { lat: -8.6, lng: 115.2 },
    { lat: -8.5, lng: 115.2 }
  ],
  properties: {
    fillColor: '#3388ff',
    fillOpacity: 0.3
  }
};

const response = await GeoFeatureService.createPolygon(polygonData);
```

---

## 🗂️ Database Schema

### Table: `geo_features`

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| user_id | bigint | Foreign key to users |
| nama | string | Feature name |
| deskripsi | text | Description |
| kategori_id | integer | Category ID |
| geometry_type | enum | marker, polygon, polyline, circle |
| coordinates | json | Coordinate data |
| properties | json | Additional properties |
| created_at | timestamp | Creation time |
| updated_at | timestamp | Update time |

---

## 🔄 Migration from Old System

Jika sudah ada data `wisata` lama:

```bash
# Backup data lama
php artisan tinker
>>> $wisatas = \App\Models\Wisata::all();
>>> $wisatas->toJson();

# Migrate ke GeoFeature
>>> foreach($wisatas as $w) {
...   \App\Models\GeoFeature::create([
...     'user_id' => $w->user_id ?? 1,
...     'nama' => $w->nama,
...     'deskripsi' => $w->alamat,
...     'kategori_id' => 1,
...     'geometry_type' => 'marker',
...     'coordinates' => ['lat' => $w->lat, 'lng' => $w->lng],
...     'properties' => ['kategori' => $w->kategori]
...   ]);
... }
```

---

## 📝 Notes

1. **Authentication**: API endpoints dapat diproteksi dengan middleware `auth:sanctum`
2. **Validation**: Semua input sudah divalidasi di controller
3. **Supabase**: Pastikan koneksi PostgreSQL aktif
4. **CORS**: Configure di `config/cors.php` jika frontend terpisah

---

## 🐛 Troubleshooting

### Error: "Table not found"
```bash
php artisan migrate:fresh
php artisan db:seed
```

### Error: "Connection refused"
- Check Supabase credentials di `.env`
- Pastikan IP whitelist di Supabase settings

### Error: "Class GeoFeature not found"
```bash
composer dump-autoload
```

---

## 🚀 Next Steps

- [ ] Implement authentication
- [ ] Add category management
- [ ] Implement map clustering
- [ ] Add export to GeoJSON
- [ ] Implement spatial queries

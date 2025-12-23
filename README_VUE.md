# Migrasi ke Vue.js

Aplikasi Laravel ini telah dimigrasikan dari Blade templates ke Vue.js SPA (Single Page Application).

## Instalasi

1. Install dependencies:
```bash
npm install
```

2. Build assets untuk production:
```bash
npm run build
```

Atau jalankan development server:
```bash
npm run dev
```

## Struktur Vue Components

```
resources/js/
├── App.vue              # Root component
├── app.js              # Entry point Vue
├── routes.js           # Vue Router configuration
├── bootstrap.js        # Axios & CSRF setup
├── components/
│   └── Layout.vue      # Layout component dengan navigation
└── pages/
    ├── Welcome.vue     # Welcome page
    ├── Dashboard.vue  # Dashboard page
    ├── MapPage.vue     # Map page
    ├── auth/
    │   ├── Login.vue   # Login page
    │   └── Register.vue # Register page
    └── ruasjalan/
        ├── Index.vue   # List ruas jalan
        ├── Create.vue  # Create ruas jalan
        ├── Edit.vue    # Edit ruas jalan
        └── Show.vue    # Detail ruas jalan
```

## API Endpoints

Semua API endpoints tersedia di `/api/*`:
- `GET /api/user` - User data
- `GET /api/dashboard` - Dashboard data
- `GET /api/ruasjalan` - List ruas jalan
- `GET /api/ruasjalan/{id}` - Detail ruas jalan
- `POST /api/ruasjalan` - Create ruas jalan
- `PUT /api/ruasjalan/{id}` - Update ruas jalan
- `DELETE /api/ruasjalan/{id}` - Delete ruas jalan
- `GET /api/regions` - List provinsi
- `GET /api/kabupaten/{provinsiId}` - List kabupaten
- `GET /api/kecamatan/{kabupatenId}` - List kecamatan
- `GET /api/desa/{kecamatanId}` - List desa

## Catatan

- Semua halaman menggunakan desain yang sama dengan Blade templates sebelumnya
- Vue Router menggunakan history mode
- Axios sudah dikonfigurasi dengan CSRF token
- Layout component digunakan untuk halaman yang memerlukan authentication


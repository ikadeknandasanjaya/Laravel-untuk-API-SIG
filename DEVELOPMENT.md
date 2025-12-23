# Panduan Development dengan Vue.js

## Cara Menjalankan Aplikasi

Aplikasi ini menggunakan **Laravel** sebagai backend dan **Vite** untuk build frontend Vue.js. Anda perlu menjalankan **kedua server** secara bersamaan.

### Opsi 1: Terminal Terpisah (Recommended)

**Terminal 1 - Laravel Server:**
```bash
php artisan serve
```
Server Laravel akan berjalan di `http://localhost:8000`

**Terminal 2 - Vite Dev Server:**
```bash
npm run dev
```
Vite akan berjalan di `http://localhost:5173` (untuk HMR - Hot Module Replacement)

**Akses aplikasi di:** `http://localhost:8000`

### Opsi 2: Menggunakan Laravel Sail (jika tersedia)

```bash
./vendor/bin/sail up
./vendor/bin/sail npm run dev
```

## Troubleshooting

### Masalah: Hanya melihat halaman Vite di localhost:5173

**Solusi:** 
- Akses aplikasi melalui `http://localhost:8000` (Laravel server), bukan `http://localhost:5173`
- Pastikan Laravel server berjalan dengan `php artisan serve`
- Vite dev server (`npm run dev`) hanya untuk Hot Module Replacement, bukan untuk mengakses aplikasi

### Masalah: Assets tidak ter-load

**Solusi:**
- Pastikan Vite dev server berjalan (`npm run dev`)
- Pastikan `APP_URL` di `.env` sesuai dengan URL Laravel server
- Clear cache: `php artisan config:clear`

### Masalah: Vue components tidak muncul

**Solusi:**
- Pastikan `npm install` sudah dijalankan
- Pastikan `npm run dev` berjalan
- Check browser console untuk error
- Pastikan `@vite(['resources/css/app.css', 'resources/js/app.js'])` ada di `resources/views/app.blade.php`

## Build untuk Production

```bash
npm run build
```

Setelah build, assets akan di-compile dan tidak perlu menjalankan `npm run dev` lagi.


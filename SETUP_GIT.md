# Setup Git Repository

Untuk menginisialisasi Git repository dan push ke GitHub, jalankan perintah berikut:

```bash
# Inisialisasi Git repository
git init

# Tambahkan semua file ke staging
git add .

# Commit pertama
git commit -m "Initial commit: Laravel API SIG project"

# Tambahkan remote repository (ganti URL dengan repository GitHub Anda)
git remote add origin https://github.com/username/laravel-sig.git

# Push ke GitHub
git push -u origin main
```

**Catatan:** Ganti `username` dengan username GitHub Anda dan pastikan repository sudah dibuat di GitHub.

## File yang akan di-commit:
- ✅ Semua file Laravel project
- ✅ Migration dan seeder
- ✅ API endpoints
- ✅ Dokumentasi README.md
- ✅ .gitignore file

## File yang diabaikan (.gitignore):
- ❌ .env file (berisi konfigurasi sensitif)
- ❌ vendor/ directory (akan di-install dengan composer)
- ❌ node_modules/ directory
- ❌ storage/logs/
- ❌ database/database.sqlite (akan dibuat otomatis)

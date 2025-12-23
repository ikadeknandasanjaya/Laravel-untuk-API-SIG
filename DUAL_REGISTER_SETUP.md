# Dual Registration System Setup

## Overview
Sistem registrasi yang mendaftar user ke **dua sistem sekaligus**:
1. **Lokal** - SQLite (untuk markers, polygons, circles)
2. **Remote** - API Dosen (untuk polylines/ruas jalan)

## How It Works

### Backend Flow (ApiAuthController)
1. User submit form dengan `name`, `email`, `password`, `password_confirmation`
2. Laravel memvalidasi & membuat user di SQLite lokal
3. Generate Sanctum token lokal
4. Coba register ke API dosen (`https://gisapis.manpits.xyz/api/register`)
5. Jika API dosen berhasil → Set `remote_success: true`
6. Return response dengan local token dan remote_success flag

```json
{
  "access_token": "local_sanctum_token",
  "remote_success": true,
  "token_type": "Bearer"
}
```

**Note**: Dosen's API registration tidak return token - hanya create user. User perlu login terpisah ke API dosen untuk mendapat JWT token.

### Frontend Flow (AuthService + Register.vue)

1. **Register Form** → `AuthService.registerDual(credentials)`
2. **AuthService** POST ke `/api/register` lokal
3. **Response** berisi:
   - `access_token` → Sanctum token untuk sistem lokal
   - `remote_success` → Boolean apakah API dosen registration berhasil
4. **Storage** di localStorage:
   - `local_auth_token` → Disimpan untuk API lokal
   - `login_system` → Set ke 'local'
5. **Redirect** ke dashboard dengan sistem lokal aktif

```javascript
// AuthService.registerDual() stores:
localStorage.setItem('local_auth_token', localToken);
localStorage.setItem('login_system', 'local');
```

## Key Features

✅ **Graceful Fallback**: Jika API dosen gagal, tetap berhasil register lokal
✅ **Dual Registration**: Satu form menghandle kedua sistem
✅ **Separate Login**: Marker features = lokal, Polylines = perlu login API terpisah
✅ **User Friendly**: Clear message tentang status dual registration
✅ **No Token Collision**: Lokal & API auth terpisah

## Error Scenarios

### Scenario 1: API Dosen Tidak Merespons
```
✅ Register Lokal: SUCCESS
❌ Register API: TIMEOUT/FAILED
→ User dapat login ke sistem lokal
→ User dapat akses markers (local features)
→ Pesan: "Registrasi berhasil!" (remote_success = false)
```

### Scenario 2: Email Sudah Terdaftar Lokal
```
❌ Register Lokal: FAILED (Email already exists)
→ Registrasi dibatalkan, tidak coba ke API
→ Error: "The email has already been taken"
```

### Scenario 3: Email Sudah Terdaftar di API
```
✅ Register Lokal: SUCCESS
⚠️ Register API: FAILED (Email duplicate)
→ User register lokal berhasil
→ User hanya bisa login lokal, tidak bisa login API
→ Logged: "Remote registration failed for user..."
→ Pesan: "Registrasi berhasil!" (remote_success = false)
```

### Scenario 4: User Register → Login Lokal → Access Markers
```
1. Register dual → local_auth_token stored
2. Login lokal → Get fresh local token
3. Request /api/geo-features → Interceptor inject token
4. Marker CRUD operations work
```

## Registration Fields (dari API Dosen)

| Field | Type | Required | Notes |
|-------|------|----------|-------|
| name | string | Yes | Min 3 characters |
| email | email | Yes | Unique di lokal & API |
| password | string | Yes | Min 6 characters |
| password_confirmation | string | Yes | Must match password (lokal only) |

**API Dosen Response Format**:
```json
{
  "meta": {
    "code": 200,
    "message": "Successfully create user",
    "data": {
      "id": 539,
      "name": "User Name",
      "email": "user@example.com",
      "created_at": "2025-12-23T14:01:07.000000Z"
    }
  }
}
```

## Testing Registration Flow

### 1. Register Baru
```
Email: user@example.com
Password: TestPassword123
Confirm: TestPassword123
↓
✅ Local token generated
✅ Remote token generated (jika API available)
↓
Redirect to dashboard
```

### 2. Login Lokal (setelah register)
- Credentials: email + password dari step 1
- Token: Akan gunakan `local_auth_token` dari localStorage
- Markers: Dapat dibuat (uses local API)

### 3. Marker Creation After Dual Register
- Frontend: POST ke `/api/geo-features` (lokal)
- Interceptor: Inject `local_auth_token` header
- Backend: Validate token dengan Sanctum
- Database: Save ke SQLite

## Files Modified

### Backend
- **app/Http/Controllers/ApiAuthController.php**
  - Updated `register()` method untuk dual registration
  - Added `use Illuminate\Support\Facades\Http;`
  - Graceful error handling untuk remote API

### Frontend  
- **resources/js/services/AuthService.js**
  - Added `registerDual()` method
  - Added `registerLocal()` method
  - Storage handling untuk dual tokens

- **resources/js/pages/Register.vue**
  - Updated `handleRegister()` untuk call `registerDual()`
  - Show user feedback apakah remote berhasil atau tidak

## API Endpoints

### Local Endpoints
- `POST /api/register` - Register lokal & attempt remote
- `POST /api/login` - Login lokal
- `POST /api/logout` - Logout lokal (protected)
- `GET /api/user` - Get user profile (protected)

### Remote API (Dosen)
- `POST /api/register` - Register di API dosen
- Format: `name`, `email`, `password`
- Response contains: `meta.token` (JWT)

## Important Notes

⚠️ **API Dosen Credentials**:
- URL: `https://gisapis.manpits.xyz/api/register`
- Fields: name, email, password (NO password_confirmation)
- Response format: `{"meta": {"token": "..."}}`

⚠️ **Local SQLite**:
- Require `password_confirmation` field
- Uses Sanctum tokens (not JWT)
- Password hashed dengan bcrypt

⚠️ **Token Routing**:
- If URL contains `gisapis.manpits.xyz` → Use `remote_auth_token` (JWT)
- If URL is localhost/127.0.0.1 → Use `local_auth_token` (Sanctum)

## Next Steps

1. ✅ Start servers: `php artisan serve --port=8001` & `npm run dev`
2. ✅ Test registration dengan UI: http://localhost:5173/register
3. ✅ Check localStorage untuk dua token
4. ✅ Test login lokal
5. ✅ Test marker creation
6. ✅ Verify Axios interceptors working correctly

---
**Status**: ✅ Ready for Testing  
**Last Updated**: 2025-12-23

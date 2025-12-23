# DUAL REGISTER TESTING GUIDE

## 🎯 System Overview

**Dual Register** means user registers to **2 systems simultaneously**:
1. **LOCAL** (SQLite) - For markers, polygons, circles
2. **REMOTE** (API Dosen) - For polylines/ruas jalan

User can then:
- Access local features immediately after register
- Access remote features after also logging in to dosen's API separately

## 📋 Implementation Status

### ✅ Backend Complete
- [x] ApiAuthController with dual registration
- [x] HTTP client for API dosen communication
- [x] Error handling & fallback logic
- [x] Logging for debugging
- [x] Routes configured

### ✅ Frontend Complete
- [x] AuthService with registerDual() method
- [x] Register.vue connected to dual register
- [x] localStorage handling for dual tokens
- [x] User feedback about registration status

### ✅ Testing Complete
- [x] Tested registration endpoint
- [x] Tested login endpoint
- [x] Both return correct tokens
- [x] Database records created

### ⏳ Next: UI Testing
- [ ] Start Vite frontend (npm run dev)
- [ ] Test registration form in browser
- [ ] Test login form in browser
- [ ] Test marker creation after login

## 🧪 Testing the Implementation

### Test 1: Register via API

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Your Name",
    "email": "yourtest@example.com",
    "password": "YourPassword123",
    "password_confirmation": "YourPassword123"
  }'
```

**Expected Response:**
```json
{
  "message": "User registered successfully",
  "user": {
    "id": 5,
    "name": "Your Name",
    "email": "yourtest@example.com",
    "created_at": "2025-12-23T14:05:00.000000Z",
    "updated_at": "2025-12-23T14:05:00.000000Z"
  },
  "access_token": "5|bx1EIy2BwwBnVIj0OffgPCgBGRUtSTIhvT5S2dLYf2b33a56",
  "remote_success": true,
  "token_type": "Bearer"
}
```

### Test 2: Login via API

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "yourtest@example.com",
    "password": "YourPassword123"
  }'
```

**Expected Response:**
```json
{
  "message": "Login successful",
  "user": {
    "id": 5,
    "name": "Your Name",
    "email": "yourtest@example.com",
    "created_at": "2025-12-23T14:05:00.000000Z"
  },
  "access_token": "6|hC1yx9nB5W7Qus9nxGhRpnYPy15oVugI0YRgABVg8fd9ef4c",
  "token_type": "Bearer"
}
```

### Test 3: Browser Registration UI

**Prerequisites:**
1. Terminal 1: Laravel server running ✅
2. Terminal 2: Start Vite
   ```bash
   npm run dev
   ```
3. Browser: Open http://localhost:5173

**Steps:**
1. Click "Daftar di sini" link or go to /register
2. Fill form:
   - Nama Lengkap: Test User
   - Email: testuser@example.com
   - Password: TestPass123
   - Konfirmasi Password: TestPass123
   - ✓ Agree terms
3. Click "Daftar"

**Expected Behavior:**
- ✅ Success toast notification
- ✅ Redirect to /dashboard
- ✅ User logged in (can see username in header)
- ✅ localStorage has `local_auth_token`

### Test 4: Browser Login UI

**Steps:**
1. Go to /login
2. Fill form:
   - Email: testuser@example.com
   - Password: TestPass123
3. Click "Masuk"

**Expected Behavior:**
- ✅ Success toast notification
- ✅ Redirect to /dashboard
- ✅ localStorage updated with fresh token

### Test 5: Marker Creation (After Login)

**Steps:**
1. After login, navigate to /markers or map page
2. Click "Add Marker" button
3. Click on map to create marker
4. Fill marker details
5. Click "Simpan"

**Expected Behavior:**
- ✅ Marker appears on map
- ✅ Marker saved to SQLite (local database)
- ✅ Can view, edit, delete marker
- ✅ No errors in browser console

## 📊 Database Verification

### Check Registered Users

```bash
# Terminal
cd /path/to/laravel-sig
php artisan tinker
```

```php
// In tinker
DB::table('users')->get();
// Should show registered users
```

### Check User Token

```php
// In tinker
$user = App\Models\User::find(5);
$user->tokens;
// Should show active tokens for user
```

## 🔍 Debugging

### Check Laravel Logs

```bash
tail -f storage/logs/laravel.log
```

Look for:
- "Remote registration successful" → API dosen registration worked
- "Remote registration failed" → API dosen registration failed
- Error messages if any

### Check Browser Console

Open DevTools (F12) → Console tab

Look for:
- AuthService logs about registration/login
- Network requests to `/api/register` and `/api/login`
- localStorage updates

### Check Network Requests

DevTools → Network tab

Monitor:
- **POST /api/register** → Should return 201
- **POST /api/login** → Should return 200
- **POST https://gisapis.manpits.xyz/api/register** → Should return 200

## ⚠️ Common Issues & Solutions

### Issue: "Email has already been taken"
**Cause:** Email already registered locally
**Solution:** Use different email or delete user from database

### Issue: Remote registration shows false
**Cause:** API dosen unavailable or network issue
**Solution:** That's OK! Local registration still succeeds. User can access markers immediately.

### Issue: Register button doesn't work
**Cause:** Form validation failed
**Solution:** Check browser console for validation errors. Fill all required fields.

### Issue: "Cannot POST /api/register"
**Cause:** Laravel server not running
**Solution:** 
```bash
php artisan serve --port=8000
```

### Issue: Cannot reach http://localhost:5173
**Cause:** Vite dev server not running
**Solution:**
```bash
npm run dev
```

## 🎓 What to Learn From This

### Architecture Pattern
- **Graceful Degradation**: System works even if external API fails
- **Dual Token Strategy**: Separate auth tokens for different systems
- **URL-based Routing**: Axios automatically picks right token based on API URL

### Best Practices
- Always try external API but don't fail if it doesn't respond
- Log all external API interactions for debugging
- Provide clear user feedback about success/failure
- Keep local system independent from external dependencies

## 📝 Files Modified

### Backend
1. `app/Http/Controllers/ApiAuthController.php`
   - Added `use Illuminate\Support\Facades\Http;`
   - Dual registration logic in `register()` method

### Frontend
1. `resources/js/services/AuthService.js`
   - Added `registerDual()` method (handles both systems)
   - Added `registerLocal()` method (local only)

2. `resources/js/pages/Register.vue`
   - Updated `handleRegister()` to use `registerDual()`
   - Shows appropriate success message

### Documentation
1. `DUAL_REGISTER_SETUP.md` - Technical documentation
2. `DUAL_REGISTER_TESTING.md` - This file

## 🚀 Ready to Launch

All systems are ready:
- ✅ Laravel backend running
- ✅ SQLite database ready
- ✅ Dual registration logic implemented
- ✅ Error handling in place
- ✅ Frontend code ready

**Next Command to Run:**
```bash
npm run dev
```

Then test at: http://localhost:5173/register

---
**Last Updated:** 2025-12-23
**Status:** ✅ Ready for Testing

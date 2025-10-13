#!/bin/bash

# Script untuk test API Laravel SIG
echo "=== Testing Laravel API SIG ==="
echo ""

BASE_URL="http://127.0.0.1:8000/api"

echo "1. Testing endpoint lokasi sederhana..."
curl -s "$BASE_URL/lokasi" | jq '.' 2>/dev/null || curl -s "$BASE_URL/lokasi"
echo ""
echo ""

echo "2. Testing endpoint daftar wisata..."
curl -s "$BASE_URL/wisata" | jq '.data | length' 2>/dev/null || echo "Total wisata: $(curl -s "$BASE_URL/wisata" | grep -o '"id":[0-9]*' | wc -l)"
echo ""

echo "3. Testing endpoint detail wisata (ID: 1)..."
curl -s "$BASE_URL/wisata/1" | jq '.data.nama' 2>/dev/null || curl -s "$BASE_URL/wisata/1"
echo ""
echo ""

echo "4. Testing pencarian wisata (q=Pantai)..."
curl -s "$BASE_URL/wisata?q=Pantai" | jq '.data | length' 2>/dev/null || echo "Hasil pencarian Pantai: $(curl -s "$BASE_URL/wisata?q=Pantai" | grep -o '"id":[0-9]*' | wc -l) item"
echo ""

echo "5. Testing filter kategori (kategori=budaya)..."
curl -s "$BASE_URL/wisata?kategori=budaya" | jq '.data | length' 2>/dev/null || echo "Wisata budaya: $(curl -s "$BASE_URL/wisata?kategori=budaya" | grep -o '"id":[0-9]*' | wc -l) item"
echo ""

echo "6. Testing pagination (per_page=3)..."
curl -s "$BASE_URL/wisata?per_page=3" | jq '.meta.per_page' 2>/dev/null || echo "Pagination test completed"
echo ""

echo "=== Testing selesai ==="
echo "Pastikan server Laravel berjalan dengan: php artisan serve"

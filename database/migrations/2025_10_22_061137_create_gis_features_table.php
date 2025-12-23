<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gis_features', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->enum('tipe', ['polyline', 'polygon', 'marker']);
            $table->enum('kategori', ['jalan', 'market', 'area', 'lainnya']);
            $table->json('coordinates'); // Array koordinat
            $table->json('style')->nullable(); // Style properties (color, weight, etc)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gis_features');
    }
};

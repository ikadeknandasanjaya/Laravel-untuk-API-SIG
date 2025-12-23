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
        Schema::create('geo_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->integer('kategori_id')->nullable();
            $table->enum('geometry_type', ['marker', 'polygon', 'polyline', 'circle']);
            $table->json('coordinates'); // Stores lat/lng for marker, array of points for others
            $table->json('properties')->nullable(); // Additional properties like radius for circle, color, etc
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('geometry_type');
            $table->index('kategori_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geo_features');
    }
};

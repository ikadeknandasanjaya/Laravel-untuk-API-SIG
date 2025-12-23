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
        Schema::table('wisatas', function (Blueprint $table) {
            // Make lat and lng nullable for geometry data
            $table->decimal('lat', 10, 7)->nullable()->change();
            $table->decimal('lng', 10, 7)->nullable()->change();
            
            // Add geometry fields
            $table->string('geometry_type')->nullable(); // marker, line, polygon
            $table->json('coordinates')->nullable(); // Store coordinates for line/polygon
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wisatas', function (Blueprint $table) {
            // Revert lat and lng to required
            $table->decimal('lat', 10, 7)->nullable(false)->change();
            $table->decimal('lng', 10, 7)->nullable(false)->change();
            
            // Drop geometry fields
            $table->dropColumn(['geometry_type', 'coordinates']);
        });
    }
};

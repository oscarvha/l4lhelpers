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
        Schema::create('ip_lookups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ip');
            $table->string('geo_city')->nullable();
            $table->string('geo_country')->nullable();
            $table->string('geo_timezone')->nullable();
            $table->decimal('geo_latitude', 10, 7)->nullable();
            $table->decimal('geo_longitude', 10, 7)->nullable();
            $table->integer('asn')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('owner_organization')->nullable();
            $table->string('owner_country')->nullable();
            $table->string('rir')->nullable();
            $table->string('cidr')->nullable();
            $table->string('network_start_ip')->nullable();
            $table->string('network_end_ip')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ip_lookups');
    }
};

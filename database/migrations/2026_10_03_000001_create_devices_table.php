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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('device_name')
                ->unique();
            $table->string('manufacturer')
                ->nullable();
            $table->string('model')
                ->nullable();
            $table->string('serial_number')
                ->nullable();
            $table->string('android_version')
                ->nullable();
            $table->string('security_patch')
                ->nullable();
            $table->string('management_mode')
                ->nullable();
            $table->string('management_state')
                ->nullable();
            $table->string('status')
                ->default('pending');
            $table->unsignedTinyInteger('battery_level')
                ->nullable();
            $table->timestamp('last_seen_at')
                ->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};

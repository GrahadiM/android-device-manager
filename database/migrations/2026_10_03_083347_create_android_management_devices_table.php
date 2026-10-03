<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('android_management_devices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('enterprise_id')
                ->nullable()
                ->constrained('android_management_enterprises')
                ->nullOnDelete();

            /*
             * Local/internal identifier.
             *
             * Contoh:
             * VIVO-Y12-001
             */
            $table->string('asset_code')->unique();

            /*
             * Device identity from Android Management API.
             *
             * Akan NULL sampai device benar-benar enrolled.
             */
            $table->string('google_device_name')->nullable()->unique();

            $table->string('manufacturer')->nullable();
            $table->string('model')->nullable();
            $table->string('android_version')->nullable();
            $table->string('os_name')->nullable();
            $table->string('build_number')->nullable();
            $table->string('security_patch')->nullable();

            $table->unsignedInteger('ram_gb')->nullable();

            $table->string('management_mode')->nullable();

            /*
             * local_testing
             * pending_enrollment
             * enrolled
             * retired
             */
            $table->string('status')->default('local_testing');

            $table->string('assigned_phone_number')->nullable();

            /*
             * Package name WhatsApp yang nanti menjadi
             * bagian dari policy.
             */
            $table->string('allowed_primary_app')->nullable();

            $table->boolean('is_test_device')->default(false);

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('manufacturer');
            $table->index('model');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('android_management_devices');
    }
};

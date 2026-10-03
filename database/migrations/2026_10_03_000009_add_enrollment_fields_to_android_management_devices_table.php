<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'android_management_devices',
            function (Blueprint $table) {
                $table->string('enrollment_token_name')
                    ->nullable()
                    ->after('google_device_name');

                $table->timestamp('enrollment_token_expires_at')
                    ->nullable()
                    ->after('enrollment_token_name');

                $table->string('enrollment_status')
                    ->nullable()
                    ->after('enrollment_token_expires_at');

                $table->index('enrollment_status');
            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'android_management_devices',
            function (Blueprint $table) {
                $table->dropIndex([
                    'enrollment_status',
                ]);

                $table->dropColumn([
                    'enrollment_token_name',
                    'enrollment_token_expires_at',
                    'enrollment_status',
                ]);
            }
        );
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('android_management_enterprises', function (Blueprint $table) {
            $table->uuid('callback_state')
                ->nullable()
                ->unique()
                ->after('signup_url_name');
        });
    }

    public function down(): void
    {
        Schema::table('android_management_enterprises', function (Blueprint $table) {
            $table->dropUnique([
                'callback_state',
            ]);

            $table->dropColumn('callback_state');
        });
    }
};

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
        Schema::create('android_management_enterprises', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('display_name')->nullable();
            $table->string('signup_url_name')->nullable();
            $table->text('signup_url')->nullable();
            $table->string('status')->default('pending_signup');
            $table->timestamps();
            $table->index('status');
            $table->index('signup_url_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('android_management_enterprises');
    }
};

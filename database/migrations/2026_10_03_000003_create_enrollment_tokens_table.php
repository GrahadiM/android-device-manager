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
        Schema::create('enrollment_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('token_id')
                ->nullable();
            $table->text('token')
                ->nullable();
            $table->foreignId('device_policy_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('allow_personal_usage')
                ->nullable();
            $table->timestamp('expires_at')
                ->nullable();
            $table->boolean('used')
                ->default(false);
            $table->json('metadata')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollment_tokens');
    }
};

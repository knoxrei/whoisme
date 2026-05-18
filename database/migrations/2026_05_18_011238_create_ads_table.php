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
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_campaign_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('type'); // banner, sidebar, search, trending, homepage
            $table->text('content')->nullable(); // JSON or text for ad config
            $table->string('target_url');
            $table->string('media_url')->nullable();
            $table->string('status')->default('pending'); // pending, active, paused, rejected
            $table->unsignedBigInteger('paste_id')->nullable(); // For promoted pastes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('bulk_mail_campaigns');
    }

    public function down(): void
    {
        Schema::create('bulk_mail_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('subject');
            $table->text('message');
            $table->boolean('verified_only')->default(false);
            $table->unsignedSmallInteger('timeout_seconds')->default(10);
            $table->unsignedInteger('total_recipients')->default(0);
            $table->unsignedInteger('sent_count')->default(0);
            $table->unsignedInteger('skipped_count')->default(0);
            $table->unsignedInteger('processed_count')->default(0);
            $table->string('status', 20)->default('queued');
            $table->timestamps();
        });
    }
};

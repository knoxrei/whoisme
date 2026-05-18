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
        Schema::create('ad_request_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_request_id')->constrained()->cascadeOnDelete();
            $table->text('notes');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // who requested/made revision
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_request_revisions');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pastebins', function (Blueprint $table) {
            // Composite index for the main feed filter query
            // Covers: visibility + is_self_destruct + password (IS NULL) + created_at (cursor)
            // Used by: SearchController::recent(), allPastebins(), and the WHERE clause in SearchRepository
            $table->index(['visibility', 'is_self_destruct', 'created_at'], 'idx_pastebins_feed');

            // Composite index optimized for popularity sorting
            // Covers: visibility + views_count and download_count ordering
            $table->index(['visibility', 'views_count', 'download_count'], 'idx_pastebins_popularity');

            // Composite index for author_name filtering (used in search + author filter)
            $table->index(['visibility', 'author_name'], 'idx_pastebins_author');
        });
    }

    public function down(): void
    {
        Schema::table('pastebins', function (Blueprint $table) {
            $table->dropIndex('idx_pastebins_feed');
            $table->dropIndex('idx_pastebins_popularity');
            $table->dropIndex('idx_pastebins_author');
        });
    }
};

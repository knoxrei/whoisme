<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bulk_mail_campaigns', function (Blueprint $table) {
            $table->unsignedSmallInteger('timeout_seconds')->default(10)->after('verified_only');
        });
    }

    public function down(): void
    {
        Schema::table('bulk_mail_campaigns', function (Blueprint $table) {
            $table->dropColumn('timeout_seconds');
        });
    }
};

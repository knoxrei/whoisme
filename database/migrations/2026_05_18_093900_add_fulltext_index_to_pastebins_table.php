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
            $driverName = DB::getDriverName();
            if ($driverName === 'mysql' || $driverName === 'mariadb') {
                // Safely drop indexes if they exist to prevent duplicate index errors
                try {
                    DB::statement('ALTER TABLE pastebins DROP INDEX pastebins_search_fulltext');
                } catch (\Exception $e) {}
                try {
                    DB::statement('ALTER TABLE pastebins DROP INDEX pastebins_title_fulltext');
                } catch (\Exception $e) {}
                try {
                    DB::statement('ALTER TABLE pastebins DROP INDEX pastebins_description_fulltext');
                } catch (\Exception $e) {}
                try {
                    DB::statement('ALTER TABLE pastebins DROP INDEX pastebins_content_fulltext');
                } catch (\Exception $e) {}

                // Create combined and individual FULLTEXT indexes
                DB::statement('ALTER TABLE pastebins ADD FULLTEXT pastebins_search_fulltext(title, description, content)');
                DB::statement('ALTER TABLE pastebins ADD FULLTEXT pastebins_title_fulltext(title)');
                DB::statement('ALTER TABLE pastebins ADD FULLTEXT pastebins_description_fulltext(description)');
                DB::statement('ALTER TABLE pastebins ADD FULLTEXT pastebins_content_fulltext(content)');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pastebins', function (Blueprint $table) {
            $driverName = DB::getDriverName();
            if ($driverName === 'mysql' || $driverName === 'mariadb') {
                try {
                    DB::statement('ALTER TABLE pastebins DROP INDEX pastebins_search_fulltext');
                } catch (\Exception $e) {}
                try {
                    DB::statement('ALTER TABLE pastebins DROP INDEX pastebins_title_fulltext');
                } catch (\Exception $e) {}
                try {
                    DB::statement('ALTER TABLE pastebins DROP INDEX pastebins_description_fulltext');
                } catch (\Exception $e) {}
                try {
                    DB::statement('ALTER TABLE pastebins DROP INDEX pastebins_content_fulltext');
                } catch (\Exception $e) {}
            }
        });
    }
};

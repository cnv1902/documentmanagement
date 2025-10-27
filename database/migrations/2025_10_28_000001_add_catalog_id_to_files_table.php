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
        Schema::table('files', function (Blueprint $table) {
            $table->foreignId('catalog_id')->nullable()->after('folder_id')->constrained('catalogs')->nullOnDelete();
            $table->index(['catalog_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropConstrainedForeignId('catalog_id');
            $table->dropIndex(['catalog_id']);
            $table->dropColumn('catalog_id');
        });
    }
};

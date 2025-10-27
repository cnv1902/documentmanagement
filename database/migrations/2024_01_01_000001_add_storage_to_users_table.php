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
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('storage_limit')->default(21474836480)->after('password'); // 20GB default
            $table->bigInteger('storage_used')->default(0)->after('storage_limit');
            $table->string('avatar')->nullable()->after('storage_used');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['storage_limit', 'storage_used', 'avatar']);
        });
    }
};

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
        Schema::table('stores', function (Blueprint $table) {
            $table->string('tiktok_url',255)->nullable();
            $table->string('instagram_url',255)->nullable();
            $table->string('x_url',255)->nullable();
            $table->string('website_url',255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['tiktok_url','instagram_url','x_url','website_url']);
        });
    }
};

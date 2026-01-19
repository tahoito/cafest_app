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
            $table->timestamp('basic_updated_at')->nullable()->after('updated_at');
            $table->timestamp('description_updated_at')->nullable()->after('basic_updated_at');
            $table->timestamp('contact_updated_at')->nullable()->after('description_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $tabe->dropColumn([
                'basic_updated_at',
                'description_updated_at',
                'contact_updated_at'
            ]);
        });
    }
};

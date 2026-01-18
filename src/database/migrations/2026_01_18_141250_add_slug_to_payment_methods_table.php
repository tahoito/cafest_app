<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_methods', 'slug')) {
                $table->string('slug')->after('name');
            }
        });

        // ここは別でやる（既に付いてるならOK）
        Schema::table('payment_methods', function (Blueprint $table) {
            // slug NOT NULL は既にやったなら不要
            // unique も既に付いてるなら不要
            // ただ、念のためmigrationで付けたいならtry/catchが必要だけど一旦なしでOK
        });
    }

    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            if (Schema::hasColumn('payment_methods', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }
};

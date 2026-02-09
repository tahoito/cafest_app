<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE reviews MODIFY rating DECIMAL(2,1) UNSIGNED');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE reviews MODIFY rating TINYINT UNSIGNED');
    }
};

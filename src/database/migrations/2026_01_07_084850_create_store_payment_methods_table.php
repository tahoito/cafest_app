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
        Schema::create('store_payment_methods', function (Blueprint $table) {
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('payment_method_id')->cascadeOnDelete();
            $table->unique(['store_id','payment_method_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_payment_methods');
    }
};

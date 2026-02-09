<?php 


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // PostgresにはUNSIGNEDないので普通のdecimalでOK
            $table->decimal('rating', 2, 1)->change();
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // 元の型に戻す（元がintならinteger、decimalなら合わせて）
            // 例：元が tinyInteger だったなら:
            // $table->tinyInteger('rating')->change();

            // いったん安全に戻すなら decimal(2,1) のままでもOK（卒展なら）
            $table->decimal('rating', 2, 1)->change();
        });
    }
};

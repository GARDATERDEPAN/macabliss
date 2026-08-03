<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE orders
            MODIFY total_harga BIGINT NOT NULL
        ");

        DB::statement("
            ALTER TABLE payments
            MODIFY jumlah BIGINT NOT NULL
        ");

        DB::statement("
            ALTER TABLE order_details
            MODIFY harga BIGINT NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE orders
            MODIFY total_harga INT NOT NULL
        ");

        DB::statement("
            ALTER TABLE payments
            MODIFY jumlah INT NOT NULL
        ");

        DB::statement("
            ALTER TABLE order_details
            MODIFY harga INT NOT NULL
        ");
    }
};
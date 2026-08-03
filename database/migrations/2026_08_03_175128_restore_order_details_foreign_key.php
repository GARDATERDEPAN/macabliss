<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE order_details
            MODIFY order_id BIGINT UNSIGNED NOT NULL
        ");

        Schema::table('order_details', function (Blueprint $table) {

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {

            $table->dropForeign(['order_id']);

        });

        DB::statement("
            ALTER TABLE order_details
            MODIFY order_id VARCHAR(255) NOT NULL
        ");
    }
};
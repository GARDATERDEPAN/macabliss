<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            // TOKEN MIDTRANS
            $table->text('snap_token')
                ->nullable();

            // STATUS PEMBAYARAN
            $table->enum('payment_status', [
                'pending',
                'paid',
                'expired',
                'failed',
                'cancelled'
            ])->default('pending');

            // BATAS WAKTU PEMBAYARAN
            $table->timestamp('expired_at')
                ->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->dropColumn([
                'snap_token',
                'payment_status',
                'expired_at'
            ]);

        });
    }
};
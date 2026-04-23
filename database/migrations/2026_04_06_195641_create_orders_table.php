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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('kode')->nullable()->unique();

            $table->string('nama_customer');
            $table->string('no_hp');
            $table->text('alamat');

            $table->date('tanggal_pesan');
            $table->date('tanggal_kirim');

            $table->string('metode_pembayaran'); 
            $table->integer('total_harga');      

            $table->enum('status', ['diproses', 'selesai'])
                ->default('diproses');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

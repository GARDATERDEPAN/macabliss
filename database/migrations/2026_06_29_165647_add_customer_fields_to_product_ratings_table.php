<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_ratings', function (Blueprint $table) {

            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('order_id')
                ->nullable()
                ->after('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->text('komentar')
                ->nullable()
                ->after('rating');

        });
    }

    public function down(): void
    {
        Schema::table('product_ratings', function (Blueprint $table) {

            $table->dropForeign(['user_id']);
            $table->dropForeign(['order_id']);

            $table->dropColumn([
                'user_id',
                'order_id',
                'komentar'
            ]);

        });
    }
};
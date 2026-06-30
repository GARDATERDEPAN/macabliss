<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chats', function (Blueprint $table) {

            $table->foreignId('customer_id')
                ->after('id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('admin_id')
                ->nullable()
                ->after('customer_id')
                ->constrained('users')
                ->nullOnDelete();

            $table->text('message')
                ->after('admin_id');

            $table->enum('sender', [
                'customer',
                'admin'
            ])->after('message');

            $table->boolean('is_read')
                ->default(false)
                ->after('sender');

        });
    }

    public function down(): void
    {
        Schema::table('chats', function (Blueprint $table) {

            $table->dropForeign(['customer_id']);
            $table->dropForeign(['admin_id']);

            $table->dropColumn([

                'customer_id',
                'admin_id',
                'message',
                'sender',
                'is_read'

            ]);

        });
    }
};
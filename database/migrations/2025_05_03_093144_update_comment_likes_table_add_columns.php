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
        Schema::table('comment_likes', function (Blueprint $table) {
            if (!Schema::hasColumn('comment_likes', 'user_id')) {
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('comment_likes', 'comment_id')) {
                $table->foreignId('comment_id')->constrained()->onDelete('cascade');
            }
            
            // Добавляем уникальный индекс, если его нет
            if (!Schema::hasIndex('comment_likes', ['user_id', 'comment_id'])) {
                $table->unique(['user_id', 'comment_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comment_likes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['comment_id']);
            $table->dropColumn(['user_id', 'comment_id']);
            $table->dropUnique(['user_id', 'comment_id']);
        });
    }
};

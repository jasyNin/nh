<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('likes')) {
            // Проверяем и удаляем старые колонки, если они существуют
            if (Schema::hasColumn('likes', 'likeable_type')) {
                Schema::table('likes', function (Blueprint $table) {
                    $table->dropColumn('likeable_type');
                });
            }
            
            if (Schema::hasColumn('likes', 'likeable_id')) {
                Schema::table('likes', function (Blueprint $table) {
                    $table->dropColumn('likeable_id');
                });
            }
            
            // Добавляем новую колонку post_id, если её нет
            if (!Schema::hasColumn('likes', 'post_id')) {
                Schema::table('likes', function (Blueprint $table) {
                    $table->foreignId('post_id')->after('user_id')->constrained()->onDelete('cascade');
                });
            }
            
            // Добавляем уникальный индекс, если его нет
            if (!Schema::hasIndex('likes', ['user_id', 'post_id'])) {
                Schema::table('likes', function (Blueprint $table) {
                    $table->unique(['user_id', 'post_id']);
                });
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('likes')) {
            // Удаляем новую колонку и индекс, если они существуют
            if (Schema::hasColumn('likes', 'post_id')) {
                Schema::table('likes', function (Blueprint $table) {
                    $table->dropForeign(['post_id']);
                    $table->dropColumn('post_id');
                });
            }
            
            if (Schema::hasIndex('likes', ['user_id', 'post_id'])) {
                Schema::table('likes', function (Blueprint $table) {
                    $table->dropUnique(['user_id', 'post_id']);
                });
            }
            
            // Возвращаем старые колонки, если их нет
            if (!Schema::hasColumn('likes', 'likeable_type')) {
                Schema::table('likes', function (Blueprint $table) {
                    $table->string('likeable_type');
                });
            }
            
            if (!Schema::hasColumn('likes', 'likeable_id')) {
                Schema::table('likes', function (Blueprint $table) {
                    $table->unsignedBigInteger('likeable_id');
                });
            }
        }
    }
}; 
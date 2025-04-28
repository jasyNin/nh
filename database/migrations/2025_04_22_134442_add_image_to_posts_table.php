<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('posts')) {
            // Добавляем колонку image, если её нет
            if (!Schema::hasColumn('posts', 'image')) {
                Schema::table('posts', function (Blueprint $table) {
                    $table->string('image')->nullable()->after('content');
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('posts')) {
            // Удаляем колонку image, если она существует
            if (Schema::hasColumn('posts', 'image')) {
                Schema::table('posts', function (Blueprint $table) {
                    $table->dropColumn('image');
                });
            }
        }
    }
}; 
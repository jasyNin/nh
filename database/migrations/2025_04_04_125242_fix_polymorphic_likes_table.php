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
        // Проверяем существование таблицы
        if (!Schema::hasTable('polymorphic_likes')) {
            Schema::create('polymorphic_likes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->morphs('likeable');
                $table->timestamps();
                
                // Убедимся, что пользователь может лайкнуть объект только один раз
                $table->unique(['user_id', 'likeable_id', 'likeable_type']);
            });
            
            // Если существует старая таблица likes, мигрируем данные
            if (Schema::hasTable('likes')) {
                // Получаем данные из существующей таблицы likes
                $oldLikes = DB::table('likes')->get();
                
                // Мигрируем данные из старой таблицы
                foreach ($oldLikes as $oldLike) {
                    // Проверяем наличие полей
                    if (property_exists($oldLike, 'likeable_id') && property_exists($oldLike, 'likeable_type')) {
                        // Уже в полиморфном формате
                        DB::table('polymorphic_likes')->insert([
                            'user_id' => $oldLike->user_id,
                            'likeable_id' => $oldLike->likeable_id,
                            'likeable_type' => $oldLike->likeable_type,
                            'created_at' => $oldLike->created_at ?? now(),
                            'updated_at' => $oldLike->updated_at ?? now(),
                        ]);
                    } elseif (property_exists($oldLike, 'post_id') && !empty($oldLike->post_id)) {
                        // Лайк поста
                        DB::table('polymorphic_likes')->insert([
                            'user_id' => $oldLike->user_id,
                            'likeable_id' => $oldLike->post_id,
                            'likeable_type' => 'App\\Models\\Post',
                            'created_at' => $oldLike->created_at ?? now(),
                            'updated_at' => $oldLike->updated_at ?? now(),
                        ]);
                    } elseif (property_exists($oldLike, 'comment_id') && !empty($oldLike->comment_id)) {
                        // Лайк комментария
                        DB::table('polymorphic_likes')->insert([
                            'user_id' => $oldLike->user_id,
                            'likeable_id' => $oldLike->comment_id,
                            'likeable_type' => 'App\\Models\\Comment',
                            'created_at' => $oldLike->created_at ?? now(),
                            'updated_at' => $oldLike->updated_at ?? now(),
                        ]);
                    }
                }
            }
        } else {
            // Таблица уже существует, проверяем колонки
            Schema::table('polymorphic_likes', function (Blueprint $table) {
                // Проверяем, существуют ли колонки перед их добавлением
                if (!Schema::hasColumn('polymorphic_likes', 'user_id')) {
                    $table->foreignId('user_id')->constrained()->onDelete('cascade');
                }
                
                if (!Schema::hasColumn('polymorphic_likes', 'likeable_id')) {
                    $table->unsignedBigInteger('likeable_id');
                }
                
                if (!Schema::hasColumn('polymorphic_likes', 'likeable_type')) {
                    $table->string('likeable_type');
                }
                
                // Создаем индекс для полиморфных отношений, если его нет
                if (!Schema::hasIndex('polymorphic_likes', 'polymorphic_likes_likeable_type_likeable_id_index')) {
                    $table->index(['likeable_type', 'likeable_id'], 'polymorphic_likes_likeable_type_likeable_id_index');
                }
                
                // Добавляем уникальный индекс для предотвращения дублирования лайков
                if (!Schema::hasIndex('polymorphic_likes', 'polymorphic_likes_user_id_likeable_id_likeable_type_unique')) {
                    $table->unique(['user_id', 'likeable_id', 'likeable_type'], 'polymorphic_likes_user_id_likeable_id_likeable_type_unique');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Нет действий при откате - мы не хотим удалять таблицу с данными
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('polymorphic_likes') && Schema::hasTable('likes')) {
            // Получаем все лайки постов из старой таблицы
            $oldLikes = DB::table('polymorphic_likes')
                ->where('likeable_type', 'App\\Models\\Post')
                ->get();

            // Переносим данные в новую таблицу
            foreach ($oldLikes as $like) {
                try {
                    DB::table('likes')->insert([
                        'user_id' => $like->user_id,
                        'post_id' => $like->likeable_id,
                        'created_at' => $like->created_at,
                        'updated_at' => $like->updated_at
                    ]);
                } catch (\Exception $e) {
                    // Пропускаем дубликаты
                    continue;
                }
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('likes') && Schema::hasTable('polymorphic_likes')) {
            // Получаем все лайки из новой таблицы
            $newLikes = DB::table('likes')->get();

            // Переносим данные обратно в старую таблицу
            foreach ($newLikes as $like) {
                try {
                    DB::table('polymorphic_likes')->insert([
                        'user_id' => $like->user_id,
                        'likeable_id' => $like->post_id,
                        'likeable_type' => 'App\\Models\\Post',
                        'created_at' => $like->created_at,
                        'updated_at' => $like->updated_at
                    ]);
                } catch (\Exception $e) {
                    // Пропускаем дубликаты
                    continue;
                }
            }
        }
    }
}; 
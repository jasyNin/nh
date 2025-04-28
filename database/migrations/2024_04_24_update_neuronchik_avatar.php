<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->change();
        });

        // Обновляем аватар для пользователя Нейрончика
        DB::table('users')
            ->where('email', 'neuronchik@example.com')
            ->update(['avatar' => 'images/neuronchik.png']);
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable(false)->change();
        });

        // Откатываем изменения аватара
        DB::table('users')
            ->where('email', 'neuronchik@example.com')
            ->update(['avatar' => null]);
    }
}; 
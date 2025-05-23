<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->string('rank')->default('Новичок');
            $table->integer('rating')->default(0);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('restricted_until')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}; 
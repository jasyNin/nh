<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('polymorphic_likes')) {
            Schema::dropIfExists('polymorphic_likes');
        }
    }

    public function down()
    {
        if (!Schema::hasTable('polymorphic_likes')) {
            Schema::create('polymorphic_likes', function (Blueprint $table) {
                $table->id();
                $table->morphs('likeable');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->timestamps();

                $table->unique(['user_id', 'likeable_id', 'likeable_type']);
            });
        }
    }
}; 
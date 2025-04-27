<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->where('email', 'neuronchik@example.com')
            ->update([
                'password' => Hash::make('123123123')
            ]);
    }

    public function down(): void
    {
        DB::table('users')
            ->where('email', 'neuronchik@example.com')
            ->update([
                'password' => Hash::make('password')
            ]);
    }
}; 
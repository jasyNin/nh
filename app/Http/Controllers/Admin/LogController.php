<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;

class LogController extends Controller
{
    public function index()
    {
        $logPath = storage_path('logs/laravel.log');
        $logs = File::exists($logPath) ? array_reverse(explode(PHP_EOL, File::get($logPath))) : [];
        return view('admin.logs.index', compact('logs'));
    }
} 
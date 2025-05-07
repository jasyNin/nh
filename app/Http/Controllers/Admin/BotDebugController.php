<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BotDebugController extends Controller
{
    public function index()
    {
        $bot = QuestionBot::first();
        $responses = session('bot_responses', []);
        
        return view('admin.bot-debug.index', compact('bot', 'responses'));
    }

    public function test(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $bot = QuestionBot::first();
        $response = $bot->generateAnswer($request->message);

        // Сохраняем историю ответов в сессии
        $responses = session('bot_responses', []);
        array_unshift($responses, [
            'message' => $request->message,
            'response' => $response,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);

        // Ограничиваем историю последними 10 ответами
        $responses = array_slice($responses, 0, 10);
        session(['bot_responses' => $responses]);

        // Логируем для отладки
        Log::info('Bot Debug', [
            'message' => $request->message,
            'response' => $response
        ]);

        return redirect()->back()->with('success', 'Ответ получен');
    }
} 
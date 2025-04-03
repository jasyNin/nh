<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('pages.contact');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Здесь можно добавить логику отправки email
        // Mail::to('your@email.com')->send(new ContactFormMail($validated));

        return back()->with('success', 'Спасибо за ваше сообщение! Мы свяжемся с вами в ближайшее время.');
    }
} 
<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function rules()
    {
        return view('pages.rules');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function help()
    {
        return view('pages.help');
    }
} 
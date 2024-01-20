<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

class LegalController extends Controller
{
    public function imprint(Request $request)
    {
        $text = Str::of(__("# Imprint\n\nEdit this text to define the Imprint for your application.\n\nUse Markdown syntax."))->markdown();
        return view('legal', ['text' => $text]);
    }

    public function privacy(Request $request)
    {
        $text = Str::of(__("# Privacy Policy\n\nEdit this text to define the Privacy Policy for your application.\n\nUse Markdown syntax."))->markdown();
        return view('legal', ['text' => $text]);
    }

    public function terms(Request $request)
    {
        $text = Str::of(__("# Terms of Use\n\nEdit this text to define the Terms of Use for your application.\n\nUse Markdown syntax."))->markdown();
        return view('legal', ['text' => $text]);
    }

}

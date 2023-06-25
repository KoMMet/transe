<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DmChatController extends Controller
{
    public function index($friendId)
    {
        return view('dmchat', compact('friendId'));
    }
}

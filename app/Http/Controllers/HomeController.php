<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Friend;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    private $f;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
	$this->f = new Friend;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
	
	    $friends = $this->f->getFriends(Auth::user()->id);
	    $selfname = Auth::user()->name;
        return view('home', compact('friends', 'selfname'));
    }
}

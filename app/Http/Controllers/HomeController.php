<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if(!Auth::check()){
            return view('auth.login');
        }

        $events = Event::where('active', true)->get();
        view()->share('events', $events);

        return view('home.index');
    }
}

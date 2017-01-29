<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use App\Models\Storage;
use App\Http\Requests;
use Carbon\Carbon;

class EventController extends Controller
{

    public function index()
    {
        $events = Event::orderBy('created_at', 'DESC')->paginate(10);

        view()->share('events', $events);

        return view('event.index');
    }

    public function create(Requests\CreateEventRequest $request)
    {
        $event = new Event();
        $event->name        = $request->input('event_name');
        $event->createdBy        = Auth::user()->name;
        $event->date        = Carbon::today("Europe/copenhagen");
        $event->active      = true;
        $event->save();

        return redirect()->route('home');
    }

    public function overview()
    {
        $event = Auth::user()->Event();

        if (is_null($event)) {
            return redirect()->route('events.logout');
        }

        view()->share('event', $event);

        return view('event.overview');

    }

    public function login(Request $request, $id)
    {
        if (!is_numeric($id)) {
            return redirect()->route('home');
        }

        $user = User::find(Auth::user()->id);
        $user->FK_eventID = $id;
        $user->save();

        return redirect()->route('event.overview');
    }

    public function logout(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->FK_eventID = null;
        $user->save();

        return redirect()->route('home');
    }

    public function close(Requests\CloseEventRequest $request, $id)
    {
        $event = Event::find($id);

        if (is_null($event))
            return redirect()->route('event.single', ['id' => $id]);

        $event->active      = false;
        $event->save();

        return redirect()->route('event.single', ['id' => $id]);
    }
}

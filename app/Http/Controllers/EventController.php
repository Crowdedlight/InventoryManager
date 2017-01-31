<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use App\Models\Storage;
use Illuminate\Http\Request;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\CloseEventRequest;
use Carbon\Carbon;

class EventController extends Controller
{

    public function index()
    {
        $events = Event::orderBy('created_at', 'DESC')->paginate(10);

        view()->share('events', $events);

        return view('event.index');
    }

    public function create(CreateEventRequest $request)
    {
        $event = new Event();
        $event->name        = $request->input('event_name');
        $event->createdBy        = Auth::user()->name;
        $event->date        = Carbon::today("Europe/copenhagen");
        $event->active      = true;
        $event->save();

        return redirect()->route('home');
    }

    public function overview(Request $request)
    {
        $event = Auth::user()->Event();

        if (is_null($event)) {
            return redirect()->route('events.logout');
        }

        view()->share('event', $event);

        //reflash for success messages
        $request->session()->reflash();

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

    public function close(CloseEventRequest $request, $id)
    {
        $event = Event::find($id);

        if (is_null($event))
            return redirect()->route('event.single', ['id' => $id]);

        $event->active      = false;
        $event->save();

        return redirect()->route('event.single', ['id' => $id]);
    }
}

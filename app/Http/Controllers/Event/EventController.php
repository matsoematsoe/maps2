<?php

namespace App\Http\Controllers\Event;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Event\Event;
use Carbon\Carbon;

class EventController extends Controller
{

    public function index()
    {
        $upcomingEvents = Event::where('end_date', '>', Carbon::today()->format('Y-m-d'))
            ->orderBy('start_date', 'asc')
            ->get();
        $pastEvents = Event::where('end_date', '<', Carbon::today()->format('Y-m-d'))
            ->orderBy('start_date', 'asc')
            ->limit(3)
            ->get();
        return view('events/event-list')
            ->with('upcomingEvents', $upcomingEvents)
            ->with('pastEvents', $pastEvents);
    }

    public function view(Event $event)
    {

        return view('events.event-detail')->with('event',$event);

    }

    public function add()
    {
        return view('events.event-add');    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'address' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        Event::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'address' => $request->input('address'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'lat' => $request->input('lat'),
            'long' => $request->input('lng'),
            'user_id' => $request->user()->id,
        ]);
        return redirect()->back();
    }




}

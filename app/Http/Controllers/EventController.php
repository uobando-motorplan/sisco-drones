<?php

namespace App\Http\Controllers;

use App\Event;
use App\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use LaravelFullCalendar\Facades\Calendar;

class EventController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $events_array = [];

        $quotations = Quotation::whereDroneId(auth()->user()->id)
            ->pluck('id');

        if ($request->category) {
            $events = Event::whereIn('related_id', $quotations)
                ->whereDate('start_date', '>=', Carbon::now()->subMonth())
                ->whereEventCategoryId($request->category)
                ->with('category')
                ->get();
        } else {
            $events = Event::whereIn('related_id', $quotations)
                ->whereDate('start_date', '>=', Carbon::now()->subMonth())
                ->with('category')
                ->get();
        }

        foreach ($events as $event) {
            $events_array[] = Calendar::event(
                $event->category->name.': '.$event->title,
                $event->is_all_day,
                Carbon::parse($event->start_date),
                Carbon::parse($event->end_date),
                null,
                [
                    'color' => $event->category->bg_color,
                    'textColor' => $event->category->text_color,
                    'url' => route('quotations.show', $event->related_id),
                ]
            );
        }

        $calendar = Calendar::addEvents($events_array);

        // return $events_array;

        return view('events.index', compact('calendar'));
    }
}

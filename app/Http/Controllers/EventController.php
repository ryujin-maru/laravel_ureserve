<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\Reservation;
use App\Services\EventService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $today = Carbon::today();

        $reservedPeople = Reservation::select('event_id',DB::raw('SUM(number_of_people) as number_of_people'))
        ->whereNull('canceled_date')
        ->groupBy('event_id');

        $events = Event::leftJoinSub($reservedPeople,'reservedPeople',function($join) {
            $join->on('events.id','=','reservedPeople.event_id');
        })
        ->whereDate('start_date','>=',$today)
        ->orderBy('start_date','asc')
        ->paginate(10);

        return view('manager.events.index',compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('manager.events.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEventRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEventRequest $request)
    {
        $check = EventService::checkEventDuplication($request->event_date,$request->start_time,$request->end_time);

        if($check) {
            return to_route('events.create')->with('status','この時間帯はすでに他の予約が存在します。');
        }

        $start_date = EventService::joinDateAndTime($request->event_date,$request->start_time);
        $end_date = EventService::joinDateAndTime($request->event_date,$request->end_time);

        Event::create([
            'name' => $request->event_name,
            'information' => $request->information,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'max_people' => $request->max_people,
            'is_visible' => $request->is_visible,
        ]);

        return to_route('events.index')->with('status','登録しました。');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        $event = Event::findOrFail($event->id);
        $users = $event->users;

        $reservations = [];
        foreach($users as $user)
        {
            $reservationInfo = [
                'name' => $user->name,
                'number_of_people' => $user->pivot->number_of_people,
                'canceled_date' => $user->pivot->canceled_date
            ];
            array_push($reservations,$reservationInfo);
        }

        $eventDate = $event->eventDate;
        $startTime = $event->startTime;
        $endTime = $event->endTime;
        // ,'eventDate','startTime','endTime'

        return view('manager.events.show',compact('event','users','reservations'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        $event = Event::findOrFail();
        $today = Carbon::today()->format('Y年m月d日');
        if($event->eventDate < $today) {
            abort(404);
        }

        $event = Event::findOrFail($event->id);
        $eventDate = $event->editEventDate;
        $startTime = $event->startTime;
        $endTime = $event->endTime;
        // ,'eventDate','startTime','endTime'

        return view('manager.events.edit',compact('event'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEventRequest  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        $check = EventService::countCheckEventDuplication($request->event_date,$request->start_time,$request->end_time);

        if($check > 1) {
            $event = Event::findOrFail($event->id);
            $eventDate = $event->editEventDate;
            $startTime = $event->startTime;
            $endTime = $event->endTime;
            return to_route('events.edit',compact('event'))->with('status','この時間帯はすでに他の予約が存在します。');
        }

        $start_date = EventService::joinDateAndTime($request->event_date,$request->start_time);
        $end_date = EventService::joinDateAndTime($request->event_date,$request->end_time);

        $event = Event::findOrFail($event->id);

        $event->name = $request->event_name;
        $event->information = $request->information;
        $event->start_date = $start_date;
        $event->end_date = $end_date;
        $event->max_people = $request->max_people;
        $event->is_visible = $request->is_visible;
        $event->save();

        return to_route('events.index')->with('status','更新しました。');
    }

    public function past()
    {
        $today = Carbon::today();

        $reservedPeople = Reservation::select('event_id',DB::raw('SUM(number_of_people) as number_of_people'))
        ->whereNull('canceled_date')
        ->groupBy('event_id');

        $events = Event::leftJoinSub($reservedPeople,'reservedPeople',function($join) {
            $join->on('events.id','=','reservedPeople.event_id');
        })
        ->whereDate('start_date','<',$today)
        ->orderBy('start_date','desc')
        ->paginate(10);

        return view('manager.events.past',compact('events'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
    }
}

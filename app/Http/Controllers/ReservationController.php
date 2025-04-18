<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function dashboard()
    {
        return view('dashboard');
    }

    public function detail($id)
    {
        $event = Event::findOrFail($id);

        $reservedPeople = Reservation::select('event_id',DB::raw('SUM(number_of_people) as number_of_people'))
        ->whereNull('canceled_date')
        ->groupBy('event_id')
        ->having('event_id',$event->id)
        ->first();

        if(!is_null($reservedPeople)) {
            $reservablePeople = $event->max_people - $reservedPeople->number_of_people;
        }else{
            $reservablePeople = $event->max_people;
        }

        $isReserved = Reservation::where('user_id','=',Auth::id())
        ->where('event_id','=',$id)
        ->where('canceled_date','=',null)
        ->latest()
        ->first();

        return view('event-detail',compact('event','reservablePeople','isReserved'));
    }

    public function reserve(Request $request)
    {
        $event = Event::findOrFail($request->id);

        $reservedPeople = Reservation::select('event_id',DB::raw('SUM(number_of_people) as number_of_people'))
        ->whereNull('canceled_date')
        ->groupBy('event_id')
        ->having('event_id',$event->id)
        ->first();

        if(is_null($reservedPeople) || $event->max_people >= $reservedPeople->number_of_people + $request->reserved_people) {
            Reservation::create([
                'user_id' => Auth::id(),
                'event_id' => $request->id,
                'number_of_people' => $request->reserved_people,
            ]);

            return to_route('dashboard')->with('status','登録OKです');
        }else{
            return to_route('dashboard')->with('status','この人数はすでに他の予約が存在します。');
        }
        
    }
}

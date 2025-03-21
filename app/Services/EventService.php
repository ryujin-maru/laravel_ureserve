<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EventService
{
    public static function checkEventDuplication($event_date,$start_time,$end_time)
    {
        dd($start_time);
        return Event::whereDate('start_date',$event_date)
        ->whereTime('end_date','>',$start_time)
        ->whereTime('start_date','<',$end_time)
        ->exists();
    }

    public static function joinDateAndTime($date,$time)
    {
        $join = $date ." ". $time;
        return Carbon::createFromFormat('Y-m-d H:i',$join);
    }

    public static function countCheckEventDuplication($event_date,$start_time,$end_time)
    {
        return Event::whereDate('start_date',$event_date)
        ->whereTime('end_date','>',$start_time)
        ->whereTime('start_date','<',$end_time)
        ->count();
    }

    public static function getWeekEvents($startDate,$endDate)
    {
        $reservedPeople = Reservation::select('event_id',DB::raw('SUM(number_of_people) as number_of_people'))
        ->whereNull('canceled_date')
        ->groupBy('event_id');

        return Event::leftJoinSub($reservedPeople,'reservedPeople',function($join) {
            $join->on('events.id','=','reservedPeople.event_id');
        })
        ->whereBetween('start_date',[$startDate,$endDate])
        ->orderBy('start_date','asc')
        ->get();
    }
}
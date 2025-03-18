<?php

namespace App\Services;

use App\Models\Event;
use Carbon\Carbon;

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
}
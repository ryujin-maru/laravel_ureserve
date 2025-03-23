<?php

namespace App\Http\Livewire;

use App\Services\EventService;
use Carbon\Carbon;
use Livewire\Component;

class Calendar extends Component
{
    public $currentDate;
    public $currentWeek;
    public $day;
    public $checkDay;
    public $dayOfWeek;
    public $sevenDaysLater;
    public $events;

    public function mount()
    {
        $this->currentDate = Carbon::today();
        $this->sevenDaysLater = $this->currentDate->copy()->addDays(7);
        $this->currentWeek = [];

        $this->events = EventService::getWeekEvents(
            $this->currentDate->format('Y-m-d'),
            $this->sevenDaysLater->format('Y-m-d'),
        );

        for($i=0;$i<7;$i++){
            $this->day = Carbon::today()->addDays($i)->format('m月d日');
            $this->checkDay = Carbon::today()->addDays($i)->format('Y-m-d');
            $this->dayOfWeek = Carbon::today()->addDays($i)->dayName;
            array_push($this->currentWeek,[
                'day' => $this->day,
                'checkDay' => $this->checkDay,
                'dayOfWeek' => $this->dayOfWeek,
            ]);
        }
    }

    public function getDate($date)
    {
        $this->currentDate = $date;
        $this->currentWeek = [];
        $this->sevenDaysLater = Carbon::parse($this->currentDate)->addDays(7);

        $this->events = EventService::getWeekEvents(
            $this->currentDate,
            $this->sevenDaysLater->format('Y-m-d'),
        );

        for($i=0;$i<7;$i++){
            $this->day = Carbon::parse($this->currentDate)->addDays($i)->format('m月d日');
            $this->checkDay = Carbon::parse($this->currentDate)->addDays($i)->format('Y-m-d');
            $this->dayOfWeek = Carbon::parse($this->currentDate)->addDays($i)->dayName;
            array_push($this->currentWeek,[
                'day' => $this->day,
                'checkDay' => $this->checkDay,
                'dayOfWeek' => $this->dayOfWeek,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.calendar');
    }
}

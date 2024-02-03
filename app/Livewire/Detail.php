<?php

namespace App\Livewire;

use App\Models\Holiday;
use App\Models\Package;
use App\Models\Slot;
use Carbon\Carbon;
use Livewire\Component;

class Detail extends Component
{
    public $package;
    public $monthString ;
    public $month ;
    public $year ;
    public $startWeekDay ;
    public $endWeekDay ;
    public $mounthDays ;
    public $today ;
    public $listHoliday ;
    public $dayOfWork ;
    public $selectedDay ;
    public $selectedMonth ;
    public $selectedYear ;
    public function mount(Package $package){
        $now = now();
        $this->package = $package;
        $this->setCalendar( $now );
        $this->dayOfWork = Slot::query()
            ->get()->pluck('day')->toArray();
    }



    public function lastMount(){
        $this->setCalendar( Carbon::create($this->year , $this->month)->subMonth() );
    }

    public function nextMount(){
        $this->setCalendar( Carbon::create($this->year , $this->month)->addMonth() );
    }


    private function setCalendar(Carbon $carbon){
        $this->monthString = $carbon->monthName;
        $this->month = $carbon->month;
        $this->year = $carbon->year;
        $now = now()->startOfDay();
        if ( $now->year == $this->year and $now->month == $this->month ){
            $this->today = $now->day;
        } elseif ( $now->year > $this->year ) {
            $this->today = 99;
        } elseif ( $now->year < $this->year ) {
            $this->today = 0;
        } elseif ( $now->year == $this->year and $now->month > $this->month ) {
            $this->today = 99;
        } elseif ( $now->year == $this->year and $now->month < $this->month ) {
            $this->today = 0;
        }
        $this->startWeekDay = $carbon->firstOfMonth()->dayOfWeek;
        $this->endWeekDay = $carbon->endOfMonth()->dayOfWeek;
        $this->mounthDays = $carbon->firstOfMonth()->daysInMonth;
        $listHolidayOnce = Holiday::query()
            ->where('year' , $this->year )
            ->where('month' , $this->month )
            ->where('type' , 'once' )
            ->get()->pluck('day')->toArray();
        $listHolidayYearly = Holiday::query()
            ->where('month' , $this->month )
            ->where('type' , 'yearly' )
            ->get()->pluck('day')->toArray();
        $this->listHoliday = array_unique(array_merge($listHolidayOnce , $listHolidayYearly));
    }

    public function selectDay($day){
        $this->selectedDay = $day;
        $this->selectedMonth = $this->month;
        $this->selectedYear = $this->year;
    }

    public function render()
    {
        return view('livewire.detail')->layout('layouts.guest' , [
            'title' => $this->package->title,
            'style' => '
                <!-- Date Picker -->
                <link rel="stylesheet" href="'.asset('assets/datepicker/style.css') .'">
            ',
        ]);
    }
}

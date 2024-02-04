<?php

namespace App\Http\Controllers;


use App\Jobs\sendRegisterEmailJob;
use App\Models\Application;
use App\Models\Gallery;
use App\Models\Holiday;
use App\Models\Package;
use App\Models\Slider;
use App\Models\Slot;
use Barryvdh\DomPDF\Facade\Pdf;
use HackerESQ\Settings\Facades\Settings;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use MyFatoorah\Library\PaymentMyfatoorahApiV2;

class Controller extends BaseController
{

    public function index(){
        $sliders = Slider::query()->where('is_active' , '1')->orderBy('ordering')->get();
        $packages = Package::query()->where('is_active' , '1')->orderBy('ordering')->get();
        return view('home' , compact('sliders' , 'packages'));
    }

    public function gallery(){
        $galleries = Gallery::query()->where('is_active' , '1')->orderBy('ordering')->get();
        return view('gallery' , compact('galleries'));
    }

    public function reserve(Request $request , Package $package){
        $request->validate([
            'date' => 'required|date',
        ]);
        $selectedDate = $this->checkDate($request->get('date'));
        if ( $selectedDate === false )
            return redirect()->route('package.details' , $package);

        $slots = Slot::query()->where('day' , $selectedDate->dayOfWeek)
            ->get();
        $times = [];
        foreach ( $slots as $slot){
            foreach(\Carbon\CarbonPeriod::since($slot->from)->minutes($slot->rest + $slot->slot )->until($slot->to)->toArray() as $oneSlot  ) {
                $times[] = [
                    'start' => $oneSlot,
                    'slot' => $slot->slot,
                ];
            }
        }
        return view('reserve' , compact('package' , 'selectedDate' , 'times' ));
    }

    public function pay(Request $request , Package $package){
        $request->validate([
            'date' => 'required|date',
            'name' => 'required|string|max:256',
            'phone' => 'required|string|max:15',
            'time' => 'required',
        ]);
        try {
            /** @var Carbon $selectedDate */
            $selectedDate = $this->checkDate($request->get('date'));
            if ($selectedDate === false)
                return redirect()->route('package.details', $package);
            $timeSlot = explode('-', $request->get('time'));
            list($hour, $minute) = explode(':', $timeSlot[0]);
            $selectedDate->setTime($hour,$minute);
            $endDate = clone $selectedDate;
            $endDate = $endDate->addMinutes($timeSlot[1]);
        } catch (\Exception $exception){
            return redirect()->route('package.details', $package);
        }
    }

    /**
     * @param $date
     * @return bool|Carbon
     */
    private function checkDate($date)
    {
        $selectedDate = Carbon::createFromFormat('Y-m-d' , $date );
        $listHolidayOnce = Holiday::query()
            ->where('year' , $selectedDate->year )
            ->where('month' , $selectedDate->month )
            ->where('day' , $selectedDate->day )
            ->where('type' , 'once' )
            ->exists();
        $listHolidayYearly = Holiday::query()
            ->where('month' , $selectedDate->month )
            ->where('day' , $selectedDate->day )
            ->where('type' , 'yearly' )
            ->exists();
        $dayOfWork = Slot::query()
            ->get()->pluck('day')->toArray();
        if ( $listHolidayYearly or $listHolidayOnce or ! in_array($selectedDate->dayOfWeek , $dayOfWork ) )
            return false;
        return  $selectedDate;
    }
}

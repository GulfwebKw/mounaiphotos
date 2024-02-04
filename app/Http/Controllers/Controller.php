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
        $selectedDate = Carbon::createFromFormat('Y-m-d' , $request->get('date') );
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

}

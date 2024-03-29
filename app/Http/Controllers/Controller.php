<?php

namespace App\Http\Controllers;


use App\Jobs\sendRegisterEmailJob;
use App\Models\Application;
use App\Models\Gallery;
use App\Models\Holiday;
use App\Models\Package;
use App\Models\Reservation;
use App\Models\ReserveOption;
use App\Models\Slider;
use App\Models\Slot;
use Barryvdh\DomPDF\Facade\Pdf;
use HackerESQ\Settings\Facades\Settings;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use MyFatoorah\Library\PaymentMyfatoorahApiV2;

class Controller extends BaseController
{

    public static $reserveTime = 30;

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
        try{
        $request->validate([
            'number_of_persons' => 'required|numeric|min:1|max:'.$package->number_of_persons,
        ]);
        } catch (\Exception $exception) {
            return redirect()->route('package.details' , $package)->withErrors($exception->getMessage());
        }
        $selectedDate = $this->checkDate($request->get('date'));
        if ( $selectedDate === false )
            return redirect()->route('package.details' , $package);

        $times = cache()->remember('time_of_'.$selectedDate->format('Y_m_d'), 60 , function () use ($selectedDate) {
            $times = [];
            $slots = Slot::query()->where('day' , $selectedDate->dayOfWeek)
                ->get();
            foreach ( $slots as $slot){
                foreach(\Carbon\CarbonPeriod::since($selectedDate->format('Y-m-d') .' '.$slot->from)->minutes($slot->rest + $slot->slot )->until($selectedDate->format('Y-m-d') .' '.$slot->to)->toArray() as $oneSlot  ) {
                    $exist = Reservation::query()
                        ->where(function ($query) use ($oneSlot , $slot){
                            $query->whereBetween('from' , [$oneSlot->format('Y-m-d H:i:00') , (clone $oneSlot)->addMinutes($slot->rest + $slot->slot - 1 )->format('Y-m-d H:i:00')])
                                ->orWhereBetween('to' , [$oneSlot->format('Y-m-d H:i:00') , (clone $oneSlot)->addMinutes($slot->rest + $slot->slot - 1)->format('Y-m-d H:i:00')]);
                        })->where(function ($query) {
                            $query->where('is_paid' , 1)
                                ->orWhere('created_at' , '>=' , now()->subMinutes(self::$reserveTime));
                        })->exists();
                    if ( ! $exist ) {
                        $times[] = [
                            'start' => $oneSlot,
                            'slot' => $slot->slot,
                        ];
                    }
                }
            }
            return $times;
        });
        if ( count($times) == 0 ) {
            Holiday::query()
                ->where('day' , '<' , now()->day)
                ->where('year' , '<' , now()->year)
                ->where('month' , '<' , now()->month)
                ->where('title' , 'Day Full Reserve. (automatic)')
                ->delete();
            $holiday = new Holiday();
            $holiday->day = $selectedDate->day;
            $holiday->year = $selectedDate->year;
            $holiday->month = $selectedDate->month;
            $holiday->type = 'once' ;
            $holiday->title = 'Day Full Reserve. (automatic)';
            $holiday->save();
            return redirect()->route('package.details', $package);
        }
        return view('reserve' , compact('package' , 'selectedDate' , 'times' ));
    }

    public function storeReserve(Request $request , Package $package){
        $request->validate([
            'date' => 'required|date',
            'number_of_persons' => 'required|numeric|min:1|max:'.$package->number_of_persons,
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

        do {
            $uuid = strtolower(Str::random(6));
        } while(Reservation::query()->where('uuid' , $uuid )->withTrashed()->exists());
        $reserve = new Reservation();
        $reserve->name = $request->get('name');
        $reserve->package_id = $package->id;
        $reserve->uuid = $uuid;
        $reserve->phone = $request->get('phone');
        $reserve->message = $request->get('message');
        $reserve->number_of_persons = $request->get('number_of_persons');
        $reserve->from = $selectedDate;
        $reserve->to = $endDate;
        $reserve->price = $package->price * $reserve->number_of_persons;
        cache()->forget('time_of_'.$selectedDate->format('Y_m_d'));
        $reserve->save();
        foreach ($request->get('options' , []) as $option_id){
            $reserveOption = new ReserveOption();
            $reserveOption->reservation_id = $reserve->id;
            $reserveOption->option_id = $option_id;
            $reserveOption->save();
        }
        return redirect()->route('reservation.pay' , $uuid);
    }

    public function reserveDetail(Reservation $reservation){
        if (
            ! $reservation->is_paid and
            $reservation->created_at->lt( now()->subMinutes(Controller::$reserveTime) )
        )
            abort(404);
        return view('reserveDetail' , compact('reservation'));
    }

    public function searchReserve(Request $request){
        /** @var Reservation $reservation */
        $reservation = Reservation::query()->where('uuid' , $request->get('track'))->first();
        if (
            $reservation == null or (
            ! $reservation->is_paid and
            $reservation->created_at->lt( now()->subMinutes(Controller::$reserveTime) ))
        )
            return redirect()->back()->withErrors('لا يمكن العثور على أي حجز!');
        return redirect()->route('reservation.detail' , $reservation );
    }

    /**
     * @param $date
     * @return bool|Carbon
     */
    private function checkDate($date)
    {
        $selectedDate = Carbon::createFromFormat('Y-m-d' , $date );
        if ( $selectedDate->gte( now()->addDays(\HackerESQ\Settings\Facades\Settings::get('calender_show_for', 30))->startOfDay() ) )
            return false;

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

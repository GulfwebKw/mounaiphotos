<?php

namespace App\Http\Controllers;


use App\Jobs\sendRegisterEmailJob;
use App\Models\Application;
use App\Models\Gallery;
use App\Models\Package;
use App\Models\Slider;
use Barryvdh\DomPDF\Facade\Pdf;
use HackerESQ\Settings\Facades\Settings;
use Illuminate\Routing\Controller as BaseController;
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

    public function details(Package $package){
        $galleries = Gallery::query()->where('is_active' , '1')->orderBy('ordering')->get();
        return view('details' , compact('package','galleries'));
    }
}

@extends('layouts.guest')
@section('title' , 'الشروط والأحكام')
@section('content')
    <section class="section-gap">
        <div class="container">
            <div class="row">

                <div class="col-12 col-lg-12">
                    <h2>الشروط والأحكام </h2>
                </div>

                <div class="col-12 col-lg-12" style="color: #999; margin-top: 35px;">
                    {!! \HackerESQ\Settings\Facades\Settings::get('terms_and_condition', 'Site title') !!}
                </div>

                @if(request()->has(['date' , 'package_id']))
                <div class="clear20x"></div>

                <form action="{{ route('package.reserve' , request()->get('package_id')) }}" method="GET">
                    <input type="hidden" name="date"
                           value="{{ request()->get('date') }}">
                    <p class="text-center">
                        <button type="submit" class="btn-lg">تابع الدفع</button>
                    </p>
                </form>
                @endif

            </div>
        </div>
    </section>
@endsection

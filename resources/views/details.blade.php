@extends('layouts.guest')
@section('title' , $package->title)
@section('script')
    <script src="{{ asset('assets/datepicker/popper.js') }}"></script>
    <script src="{{ asset('assets/datepicker/main.js') }}"></script>
@endsection
@section('style')
    <!-- Date Picker -->
    <link rel="stylesheet" href="{{ asset('assets/datepicker/style.css') }}">
@endsection
@section('content')
    <section class="section-gap">
        <div class="container">
            <div class="row">

                <div class="col-12 col-lg-6">
                    <h1>{{ $package->title }}</h1>
                    <div class=" mt-5">
                        <img src="{{ asset('/storage/'.$package->picture) }}" alt="{{ $package->title }}"
                             class="d-block d-lg-none"/>
                    </div>
                    <h3>المدرجة في هذه الحزمة:</h3>
                    <ul class="pack mt-3">
                        {!! '<li>' . implode('</li><li>', explode("\n", $package->included)) . '</li>' !!}
                    </ul>

                    <h3>&nbsp;</h3>
                    <p>&nbsp;</p>
                </div>

                <div class="col-12 col-lg-6 d-none d-lg-block">
                    <img src="{{ asset('/storage/'.$package->picture) }}" alt="{{ $package->title }}"/>
                </div>

            </div>
        </div>
    </section>

    <section class="section-gap">
        <div class="container">
            <form action="{{ route('package.details' , $package) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <h3>حجز الجلسة</h3>

                        <div class="calendar calendar-first" id="calendar_first">
                            <div class="calendar_header">
                                <button class="switch-month switch-left"><i class="fa fa-chevron-left"></i></button>
                                <h2></h2>
                                <button class="switch-month switch-right"><i class="fa fa-chevron-right"></i></button>
                            </div>
                            <div class="calendar_weekdays"></div>
                            <div class="calendar_content"></div>
                        </div>

                    </div>
                    <div class="col-12 col-lg-6">
                        <ul class="pack">
                            <li><h5>متوفر:</h5> يمكنك العثور على الجلسات المتاحة في هذه الأيام.:</li>
                            <li><h5>محجوز:</h5> محجوزة بالكامل</li>
                        </ul>
                        <div class="clear20x"></div>

                        <div class="slot"><label for="checkbox" style="display: flex;width: 100%;column-gap: 10px;">
                            <input type="checkbox" required name="approve_terms" value="1" id="checkbox" style="width: 30px;">
                            <span>اوافق علي </span> <a href="{{ route('terms') }}" target="_blank">الشروط و الاحكام</a>  </label>
                        </div>
                        <p class="text-center">
                            <button type="submit" class="btn-lg">احجز الآن</button>
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@extends('layouts.guest')
@section('title' , $reservation->package->title .' - '. $reservation->name)

@section('content')

    <section class="section-gap">
        <div class="container">
            <div class="row">

                <div class="offset-lg-3 col-lg col-lg-6 text-center">
                    <h2>المدفوعات</h2>
                    <p class="text-center"><img src="{{ asset('assets/images/check.png') }}" alt="" class="check"> </p>
                    <p class="sucess">تم الدفع بنجاح</p>

                    <ul class="payment_details">
                        <li>التاريخ و الوقت : <span class="ltr">27-01-2024 :15:17:12</span></li>
                        <li>معرف الدفع : #1324577890</li>
                        <li>السعر الكلي : 50 د.ك</li>
                    </ul>

                    <button onclick="window.location.href='index.html'" class="btn-lg">العودة إلى الرئيسية</button>
                </div>

            </div>
        </div>
    </section>

@endsection

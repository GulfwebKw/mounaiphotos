@extends('layouts.guest')
@section('title' , $reservation->package->title .' - '. $reservation->name)

@section('content')

    <section class="section-gap">
        <div class="container">
            <div class="row">

                <div class="offset-lg-3 col-lg col-lg-6 text-center">
                    <h2>المدفوعات</h2>
                    @if( $reservation->is_paid )
                        <p class="text-center"><img src="{{ asset('assets/images/check.png') }}" alt="" class="check"> </p>
                        <p class="sucess">تم الدفع بنجاح</p>

                        <ul class="payment_details">
                            <li>التاريخ و الوقت : <span class="ltr">{{ $reservation->from }}</span></li>
                            <li>معرف الدفع : #{{ $reservation->invoice_id }}</li>
                            <li>السعر الكلي : {{ number_format($reservation->price) }} د.ك</li>
                        </ul>
                        <button onclick="window.location.href='{{ route('home') }}'" class="btn-lg">العودة إلى الرئيسية</button>
                    @else
                        <p class="sucess" style="color: #d11c1c;">هناك مشكلة في الدفع</p>
                        <button onclick="window.location.href='{{ route('reservation.pay' , $reservation) }}'" class="btn-lg">احجز الآن</button>
                    @endif

                </div>

            </div>
        </div>
    </section>

@endsection

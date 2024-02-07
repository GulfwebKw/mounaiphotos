@extends('layouts.guest')
@section('title' , 'الشروط والأحكام')
@section('content')
    <section class="section-gap">
        <div class="container">
            <div class="row">

                <div class="col-12 col-lg-12">
                    <h2>متابعة الحجوزات</h2>
                    <div class="clear30x"></div>
                </div>

                <div class="col-12 col-lg-12">
                    <form method="POST" action="{{ route('reserve.searching') }}">
                        @csrf
                        <p>الرجاء ادخال رقم الحجز لمعرفة تفاصيل حجزك</p>
                        <input type="text" name="track" class="track " >
                        <button class="btn-arrow"><i class="fa fa-arrow-left fa-lg"></i></button>
                    </form>

                    <div class="clear30x"></div>
                    @if(Session::has('errors'))
                        <p class="sucess" style="color: #d11c1c;">{{Session::get('errors')->first()}}</p>
                    @endif
                    <div class="sucess2">يمكنك ان تجد رقم الحجز الخاص بك عن طريق الرسالة النصيه المرسله اليك</div>
                </div>

            </div>
        </div>
    </section>

@endsection

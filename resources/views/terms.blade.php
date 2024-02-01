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


            </div>
        </div>
    </section>
@endsection

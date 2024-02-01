@extends('layouts.guest')
@section('title' ,'معرض الصور')
@section('style')
    <!--facnybox source start here -->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/fancy/jquery.easing-1.3.pack.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/fancy/jquery.fancybox-1.3.4.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/fancy/jquery.fancybox-1.3.4.css') }}" media="screen" />
    <script type="text/javascript" src="{{ asset('assets/fancy/web.js?m=20100203') }}"></script>
@endsection
@section('content')
    <section class="section-gap">
        <div class="container">
            <div class="row">

                <div class="col-12 col-lg-12">
                    <h2>معرض الصور</h2>
                    <div class="clear30x"></div>
                </div>

                @forelse($galleries as $gallery)
                <div class="col-12 col-lg-3 mb-15">
                    <a rel="example_group" title="{{ $gallery->title }}" href="{{ asset('/storage/'. $gallery->picture) }}"><img src="{{ asset('/storage/'. $gallery->picture) }}" alt="{{ $gallery->title }}"/></a>
                </div>
                @empty
                @endforelse

            </div>
        </div>
    </section>
@endsection

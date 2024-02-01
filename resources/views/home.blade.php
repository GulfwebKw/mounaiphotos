@extends('layouts.guest')
@section('header')
    <div id="carouselExampleControls" class="carousel slide mtb-30" data-ride="carousel">
        <div class="carousel-inner">
            @forelse($sliders as $slider)
            <div class="carousel-item @if ($loop->first)  active @endif">
                <img class="d-block w-100" src="{{ asset('/storage/'. $slider->picture) }}" alt="{{ $slider->title }}">
            </div>
            @empty
            @endforelse

        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
@endsection
@section('content')

@endsection

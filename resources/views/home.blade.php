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

    @forelse($packages as $package)
        <section class="section-gap">
            <div class="container">
                <div class="row">
                    @if($loop->even)
                        <div class="col-12 col-lg-6 d-none d-lg-block">
                            <img src="{{ asset('/storage/'.$package->picture) }}" alt="{{ $package->title }}"/>
                        </div>
                    @endif
                    <div class="col-12 col-lg-6">
                        <h1>{{ $package->title }}</h1>
                        <div class=" mt-5">
                            <img src="{{ asset('/storage/'.$package->picture) }}" alt="{{ $package->title }}" class="d-block d-lg-none"/>
                        </div>
                        <ul class="pack mt-3">
                            {!! '<li>' . implode('</li><li>', explode("\n", $package->description)) . '</li>' !!}
                        </ul>
                        <!-- <div class="bg-text">1</div> -->
                        <p class="price">د.ك <span class="f-50">{{ number_format($package->price) }}</span></p>
                        <form action="{{ route('package.details' , $package) }}">
                        <p class="text-center"><button type="submit" class="btn-lg">احجز الآن</button></p>
                        </form>
                    </div>

                        @if($loop->odd)
                            <div class="col-12 col-lg-6 d-none d-lg-block">
                                <img src="{{ asset('/storage/'.$package->picture) }}" alt="{{ $package->title }}"/>
                            </div>
                        @endif

                </div>
            </div>
        </section>
    @empty
    @endforelse
@endsection

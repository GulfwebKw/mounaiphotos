@extends('layouts.guest')
@section('title' , $package->title)
@section('script')
    <script type="text/javascript">
        $('#recipeCarousel').carousel({
            interval: 10000
        })

        $('.carousel .carousel-item').each(function () {
            var minPerSlide = 3;
            var next = $(this).next();
            if (!next.length) {
                next = $(this).siblings(':first');
            }
            next.children(':first-child').clone().appendTo($(this));

            for (var i = 0; i < minPerSlide; i++) {
                next = next.next();
                if (!next.length) {
                    next = $(this).siblings(':first');
                }

                next.children(':first-child').clone().appendTo($(this));
            }
        });

    </script>
@endsection
@section('content')
    <section class="section-gap">
        <div class="container">

            <form action="{{ route('reserve.store' , $package) }}" method="POST">
                @csrf
                <input type="hidden" name="date" value="{{ $selectedDate->format('Y-m-d') }}">

                <div class="row">

                    <div class="col-12 col-lg-12">
                        <h2>البيانات الشخصية</h2>
                    </div>

                    <div class="col-12 col-lg-12 mtb-40">
                        <div class="container text-center my-3">
                            <div class="row mx-auto my-auto">
                                <div id="recipeCarousel" class="carousel slide w-100" data-ride="carousel">
                                    <div class="carousel-inner w-100" role="listbox">
                                        @foreach($package->activeOptions as $option)
                                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                                <div class="col-md-4">
                                                    <label class="containers">
                                                        <input type="checkbox" name="options[]"
                                                               value="{{ $option->id }}"><span class="checkmark"></span>
                                                        {{ $option->title }}
                                                    </label>
                                                    <div class="card card-body">
                                                        <img class="img-fluid"
                                                             src="{{ asset('/storage/'.$option->picture) }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <a class="carousel-control-prev w-auto" href="#recipeCarousel" role="button"
                                       data-slide="prev">
                                        <span
                                            class="carousel-control-prev-icon bg-dark border border-dark rounded-circle"
                                            aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next w-auto" href="#recipeCarousel" role="button"
                                       data-slide="next">
                                        <span
                                            class="carousel-control-next-icon bg-dark border border-dark rounded-circle"
                                            aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-12">

                        <div class="title3"> الباقة المختارة: {{ $package->title }} </div>
                        <div class="clear20x"></div>

                        <div class="slot"><span>تاريخ:  </span> <span
                                dir="ltr">{{ $selectedDate->format('Y-m-d') }}</span></div>
                        <div class="clear30x"></div>

                        <div class="slot"><span>الوقت المفضل: </span>

                            <select name="time" class="@error('time') is-invalid @enderror" dir="ltr">
                                <option>حدد الوقت</option>
                                @foreach($times as $time)
                                    <option @selected(old('time') == $time['start']->format('H:i') .'-'.$time['slot'] )
                                            value="{{ $time['start']->format('H:i') .'-'.$time['slot']  }}">{{ $time['start']->format('H:i') }}
                                        - {{ $time['start']->addMinute($time['slot'])->format('H:i') }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="clear30x"></div>


                        <div class="title4">معلومات:</div>
                        <div class="clear30x"></div>

                        <label for="name">الإسم</label>
                        <input type="text" dir="auto" id="name" class="@error('name') is-invalid @enderror" name="name"
                               value="{{ old('name') }}">
                        <div class="clear20x"></div>

                        <label for="name">رقم الهاتف</label>
                        <input type="text" dir="auto" id="phone" class="@error('phone') is-invalid @enderror" name="phone"
                               placeholder="الرجاء ادخال رقم الهاتف باللغة الانجليزية"
                               value="{{ old('phone') }}"
                               onblur="this.placeholder='الرجاء ادخال رقم الهاتف باللغة الانجليزية'"
                               onclick="this.placeholder=''">
                        <div class="clear20x"></div>


                        <label for="name">الملاحضات</label>
                        <textarea type="text" dir="auto" id="message" class="@error('message') is-invalid @enderror" name="message"
                                  rows="3">{{ old('message') }}</textarea>
                        <div class="clear20x"></div>

                        <div class="slot"><span>عدد الأشخاص: </span>

                            <select name="number_of_persons" onchange="document.getElementById('price').textContent = ( parseInt(this.value) * {{ $package->price }});" class="@error('time') is-invalid @enderror" dir="ltr">
                                @for($i = 1 ; $i <= $package->number_of_persons ; $i++ )
                                    <option @selected(old('number_of_persons' , request()->get('number_of_persons' , 0)) == $i ) value="{{ $i }}">{{$i}}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="clear20x"></div>

                        <div class="total_price">د.ك <span id="price">{{ number_format($package->price * intval(old('number_of_persons' , request()->get('number_of_persons' , 0))) ) }}</span></div>
                        <div class="clear20x"></div>

                    </div>

                    <div class="col-lg-6">
                        <button type="submit" class="btn-lg">احجز الآن</button>
                    </div>


                </div>
            </form>
        </div>
    </section>

@endsection

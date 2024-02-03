<div>
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
            <div class="row">
                <div class="col-12 col-lg-6">
                    <h3>حجز الجلسة</h3>

                    <div class="calendar calendar-first" id="calendar_first">
                        <div class="calendar_header">
                            <button   wire:loading.attr="disabled"
                                      wire:click.prevent="lastMount" type="button" class="switch-month switch-left"><i class="fa fa-chevron-left"></i></button>
                            <h2>{{ $monthString }} {{ $year }}</h2>
                            <button   wire:loading.attr="disabled"
                                      wire:click.prevent="nextMount" type="button" class="switch-month switch-right"><i class="fa fa-chevron-right"></i></button>
                        </div>
                        <div class="calendar_weekdays" dir="ltr"><div style="color: rgb(68, 68, 68);">Sun</div><div style="color: rgb(68, 68, 68);">Mon</div><div style="color: rgb(68, 68, 68);">Tue</div><div style="color: rgb(68, 68, 68);">Wed</div><div style="color: rgb(68, 68, 68);">Thu</div><div style="color: rgb(68, 68, 68);">Fri</div><div style="color: rgb(68, 68, 68);">Sat</div></div>
                        <div class="calendar_content" dir="ltr">
                            @for($i = 0 ; $i < $startWeekDay ; $i++)
                            <div class="blank"></div>
                            @endfor
                            @for($i = 1 ; $i <= $mounthDays ; $i++)
                                @if ( $today == $i )
                                        <div class="today @if($month == $selectedMonth and $year == $selectedYear and $i == $selectedDay ) selected @endif  @if(in_array($i , $listHoliday) or ! in_array( ( ($i + $startWeekDay) % 7) + 1  , $dayOfWork)) past-date @endif " @if(in_array($i , $listHoliday) or ! in_array( ( ($i + $startWeekDay) % 7) + 1  , $dayOfWork))  @else wire:loading.attr="disabled"
                                              wire:click.prevent="selectDay({{ $i }})" @endif style="color: rgb(0, 189, 170);">{{ $i }}</div>
                                    @elseif ( $today > $i )
                                        <div class="past-date">{{ $i }}</div>
                                    @elseif ( $today < $i )
                                        <div @if(in_array($i , $listHoliday)  or ! in_array( ( ($i + $startWeekDay - 1) % 7)  , $dayOfWork))  class="past-date" @else wire:loading.attr="disabled"
                                             wire:click.prevent="selectDay({{ $i }})"  @if($month == $selectedMonth and $year == $selectedYear and $i == $selectedDay) class="selected" @endif @endif>{{ $i }}</div>
                                @endif
                            @endfor
                            @for($i = $endWeekDay ; $i < 7 ; $i++)
                                <div class="blank"></div>
                            @endfor
                        </div>
                    </div>

                </div>
                <div class="col-12 col-lg-6">
                    <ul class="pack">
                        <li><h5>متوفر:</h5> يمكنك العثور على الجلسات المتاحة في هذه الأيام.:</li>
                        <li><h5>محجوز:</h5> محجوزة بالكامل</li>
                    </ul>
                    <div class="clear20x"></div>

                    <form action="{{ route('package.details' , $package) }}" method="POST">
                        @csrf
                        <input type="hidden" name="date" value="{{ $selectedYear.'-'.$selectedMonth.'-'.$selectedDay }}">
                        <div class="slot"><label for="checkbox" style="display: flex;width: 100%;column-gap: 10px;">
                                <input type="checkbox" required name="approve_terms" value="1" id="checkbox" style="width: 30px;">
                                <span>اوافق علي </span> <a href="{{ route('terms') }}" target="_blank">الشروط و الاحكام</a>  </label>
                        </div>
                        <p class="text-center">
                            <button type="submit" class="btn-lg">احجز الآن</button>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

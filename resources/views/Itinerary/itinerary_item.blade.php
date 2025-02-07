@extends('Layouts.app')
@section($Slug, 'active')

@section('tLink')

@endsection
@section('blink')
    {{--    強壓TS套件的樣式--}}
    <style>
        .ts-control > * {
            display: flex !important;
        }
    </style>
@endsection
@section('content')
   {{session('trip_time')}}
    <div class=" flex lg:flex-row flex-col gap-5 w-full justify-center ">
        <div class="flex flex-col  gap-6 w-full lg:w-1/2">
            <div class="swiper top_trip w-full  mx-auto aspect-square flex items-center">
                <div class="swiper-wrapper">

                    @foreach( $items->carousel as $carouselItem)
                        <div class="swiper-slide">
                            <img class="absolute top-0 left-0 w-full h-full object-cover object-center"
                                 src="{{Storage::url($Media[$carouselItem])}}" loading="lazy"/>
                        </div>
                    @endforeach
                    <!-- Repeat for other slides -->
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>

            <div thumbsSlider="" class="swiper btm_trip w-full ">
                <div class="swiper-wrapper w-full">
                    @foreach($items->carousel  as $carouselItems)
                        <div class="swiper-slide">
                            <img src="{{Storage::url($Media[$carouselItems])}}" loading="lazy"/>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
        <div class="flex flex-col gap-10 justify-between">
            <div>
                <h1 class="text-2xl">{{$items->title.'-'. $items->subtitle}}</h1>
                <p>
                    {{$items->description}}
                </p>
            </div>
            <div class="w-full flex flex-col gap-4 ">

                <div class="flex flex-col gap-2">
                    <h1 class="xxx:text-xl md:text-2xl">報名日期</h1>
                    <div class="w-auto">
                        @include('Layouts.select', ['li'=>true,'select' => $selectedTripTime->toArray(),'default'=>$uuid_default,'name'=>'trip_times','mltArray_index'=>'date'])
                    </div>
                </div>
                <div class="flex flex-row justify-between w-full">
                    <div class="flex flex-row gap-2 items-center w-2/3">
                        <h1 class="xxx:text-xl md:text-2xl">行程名額</h1>
                        <div class="w-auto">
                            {{ $trip_times['quota']}}
                        </div>
                    </div>
                    <div class="flex flex-row gap-2 items-center w-1/3">
                        <h1 class="xxx:text-xl md:text-2xl">已報名</h1>
                        <div class="w-auto">
                            {{ $trip_times['quota']}}
                        </div>
                    </div>
                </div>
                <div class="flex justify-between">
                    <div class="flex flex-row gap-2 items-center w-2/3">
                        <h1 class="xxx:text-xl md:text-2xl">報名費用</h1>
                        <div class="w-auto">
                            {{ $trip_times['amount']}}
                        </div>
                    </div>
                    <button
                        name="signupBtn"
                        class=" w-1/3 text-center xxx:text-xs  ss:text-base sm:text-lg lg:text-base xs:px-0.5 py-2 border border-gray-800 text-gray-800 rounded hover:bg-[#DA8A51] bg-neutral-50  hover:text-white">
                        <span class="w-auto ">我要報名</span>
                        <i class="fas fa-chevron-right w-1/12"> </i>
                    </button>
                </div>


            </div>
        </div>
    </div>
    <div class="my-2">
        {!!  $items->content !!}
    </div>
    {{--    同意書--}}
    <div class="my-8">
        <h1>同意書相關規範</h1>
        <div class="h-56 overflow-y-scroll border border-gray-300 p-4 agree-ctn">

            <p>【活動切結書】

            {!! nl2br($trip_times['agreement_content']?? $items->agreement_content)!!}
        </div>
    </div>

    <button class="flex flex-initial" type="button" name="agree_btn">
        <input type="checkbox" id="agreeCheckbox" disabled />
        <div class="checkbox"></div>
        <label class="cursor-pointer hover:text-[#ff9a63]" name="CheckLabel" for="agreeCheckbox">我同意條款<span
                class="text-neutral-500 opacity-80">(請滑至同意書底部)</span></label>
    </button>

    <div class="hidden" id="trip_from">
        @include('Itinerary.tripFrom')
    </div>

@endsection

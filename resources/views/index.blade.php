@extends('Layouts.app')
@section($Slug,'active')
{{--{{dd($Carousels)}}--}}
@section('tlink')
    {{--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>--}}

@endsection

@section('blink')
    {{--    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>--}}
@endsection
@section('content')
    <section class="">
        <div class="swiper carousel ">
            <div class="swiper-wrapper relative">
                <div class="bg-white z-10 absolute w-full h-full opacity-0"></div>
                @foreach($Carousels as $Carousel)
                    <img class="swiper-slide"
                         src="{{ Storage::url($Carousel['image_path']) }}"
                         alt="{{$Carousel['alt']??''}}">
                @endforeach
            </div>
            <div
                class="swiper-button-prev carousel_arrow "></div>

            <div
                class="swiper-button-next carousel_arrow "></div>
        </div>
    </section>
    <section class="py-5 xxx:px-4 xxs:px-6 ss:px-12 sm:px-10 md:px-12 lg:px-2 min-w-[302px]">
        <div class="md:mx-16 sm:mx-10 lg:mx-4 {{--xl:mx-40 -2xl:mx-60 --}}">
            <div
                class="flex flex-col items-end lg:flex-row justify-center gap-6 lg:gap-8 xl:gap-16  ">
                <div id="targetElement" class="xxx:w-full xl:w-[550px] lg:w-[450px] lg:h-full">
                    <section class="">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="fw-bold fs-3">行程訊息</h3>
                            <a type="button"
                               class="w-[18.8%] text-center ms-auto xxx:text-xs  ss:text-base sm:text-lg lg:text-base xs:px-0.5 py-2 border border-gray-800 text-gray-800 rounded hover:bg-[#DA8A51] bg-neutral-50  hover:text-white focus:outline-none">
                                <span class="w-11/12 "></span> 看更多
                                <i class="fas fa-chevron-right w-1/12"> </i>
                            </a>
                        </div>

                        <nav
                            class="flex flex-wrap  justify-between mt-2 w-full  items-end relative overflow-visible group"
                            role="tablist" id="itinerary_tab">


                            @foreach($itinerary as $index => $v)
                                <button
                                    class="text-black tabutton h-full  ss:py-1 xxx:w-[19.5%] xxs:w-[18.8%] {{--us-420:w-[18%]  lg:w-[18%]--}}  bg-[#b8cac9] rounded-t-lg  {{ $index == 0 ? 'active' : '' }} group element  hover:shadow-md transition-all transform duration-300 ease-out relative"
                                    data-bs-toggle="tab"
                                    data-bs-target="#{{$v['id']}}"
                                    role="tab"
                                    aria-controls="{{$v['id']}}"
                                    aria-selected="{{ $index == 0 ? 'true' : 'false' }}">

                                    <!-- Tab content -->
                                    <span
                                        class=" {{--px-2--}}  xxx:text-xs  ss:text-sm ">
                {{$v['tab']}}
            </span>

                                </button>
                            @endforeach
                        </nav>

                        <div class="tab-content " id="itinerary_tab_content">
                            @foreach($itinerary as $index => $v)
                                <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }} " id="{{$v['id']}}"
                                     role="tabpanel" aria-labelledby="{{$v['id']}}">
                                    <div class="card border-0 ">
                                        <ul class="list-group list-group-flush fs-6 ">
                                            @foreach($v['data'] as $a)
                                                <li class="xxx:pt-2 xs:pt-3 pl-2 sm:pt-4 sm:pl-3  xxx:py-2  lg:py-3 hover:scale-105 hover:z-50 hover:shadow-[0_-8px_12px_-2px_rgba(0,0,0,0.15),0_8px_12px_-2px_rgba(0,0,0,0.15)] hover:rounded-xl transition-all transform itinerary_tab bg-neutral-50 border-b border-b-[#a8b6ad]"
                                                    data-tab="{{$a['uuid']}}">
                                                    <div class="flex items-center ">
                                                        <img class="mr-1 xxx:w-4 ss:w-5 sm:w-5 "
                                                             src="{{ Storage::url('Itinerary/icon1.png') }}"
                                                             loading="lazy" data-lock="1" alt="icon1"/>
                                                        <span
                                                            class="block fw-bold  text-lg ss:text-xl sm:text-xl  text-left ">{{$a['name']}}</span>
                                                    </div>
                                                    <div
                                                        class="flex flex-row w-full items-end justify-between gap-1 ">
                                                        <div
                                                            class="flex xxs:flex-row flex-col  sm:mt-2 w-4/5 ">
                                                            <span
                                                                class="text-[#da8a51] text-base ss:text-xl xxx:w-[115px] ss:w-[140px]">{{$a['time']}}</span>

                                                            <div
                                                                class="flex flex-wrap xxx:gap-1 ss:gap-1.5 items-center">
                                                                <span class="span_tag">C級</span>
                                                                <span class="span_tag">郊山</span>
                                                                <span class="span_tag">進階健行</span>
                                                            </div>

                                                        </div>
                                                        <div class="w-1/5 flex flex-col align-end h-full">
                                                            <button type="button "
                                                                    class="w-11/12 xxx:px-1 xxx:py-2 sm:p-2   rounded-full bg-[#55958d] text-[#f7dbab] hover:text-[#467771] hover:bg-[#f8b551] xxx:text-xs xxs:text-sm xs:text-base sm:text-lg lg:text-base ">
                                                                報名中

                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="lg:hidden xxx:mr-2 sm:mr-3 flex justify-center  items-center ">
                                                        <i class="fa-solid fa-sort-down text-[#5B9894FF]"> </i>

                                                    </div>
                                                </li>
                                                <img class="itinerary_img lg:hidden hidden" src="{{$a['img']}}"
                                                     loading="lazy" data-lock="{{$a['uuid']}}"/>

                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                @break;
                            @endforeach
                        </div>

                    </section>

                    {{--                                                    <button type="button"--}}
                    {{--                                                            class="btn btn-outline-warning btn-cus ms-auto">--}}
                    {{--                                                        再3人成團--}}
                    {{--                                                    </button>--}}
                </div>
                <div class="mt-5  lg:w-[418px]   hidden lg:block">

                    <div class="swiper Itinerary relative">
                        <div class="swiper-wrapper tab-lock">
                            {{--hide--}}
                            <div class="bg-white z-10 absolute w-full h-full opacity-0"></div>
                            <img class="swiper-slide" src="{{Storage::url('poc1.jpg')}}" loading="lazy"
                                 data-lock="1"/>
                            <img class="swiper-slide" src="{{Storage::url('poc2.jpg')}}" loading="lazy"
                                 data-lock="2"/>
                            <img class="swiper-slide" src="{{Storage::url('poc3.jpg')}}" loading="lazy"
                                 data-lock="3"/>
                            <img class="swiper-slide" src="{{Storage::url('poc4.jpg')}}" loading="lazy"
                                 data-lock="4"/>
                            <img class="swiper-slide" src="{{Storage::url('poc5.jpg')}}" loading="lazy"
                                 data-lock="5"/>

                        </div>
                        <div
                            class="swiper-button-next Itinerary "></div>
                        <div
                            class="swiper-button-prev Itinerary "></div>
                        <div class="swiper-pagination Itinerary z-21 s-cus bg-black w-full bg-opacity-10  "></div>


                    </div>

                </div>
            </div>
        </div>
    </section>
    @include('blog.blog_hot', ['BlogItems' => $BlogItems])

@endsection

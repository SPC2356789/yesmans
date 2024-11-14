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
                <div
                    class="swiper-button-prev carousel_arrow s-cus bg-black bg-opacity-50 rounded-full p-3 scale-50 font-extrabold"></div>

                <div
                    class="swiper-button-next carousel_arrow s-cus bg-black bg-opacity-50 rounded-full p-3 scale-50 font-extrabold"></div>
            </div>
        </div>
    </section>
    <section class="py-5 xxx:px-4 xs:px-6 ss:px-8 md:px-12 lg:px-2 min-w-[302px]">
        <div class="md:mx-16 sm:mx-10 lg:mx-4 {{--xl:mx-40 -2xl:mx-60 --}}">
            <div
                class="flex flex-col items-end lg:flex-row justify-center gap-6 lg:gap-8 xl:gap-16  ">
                <div id="targetElement" class="xxx:w-full xl:w-[550px] lg:w-[450px] lg:h-full">
                    <section class="">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="fw-bold fs-3">行程訊息</h3>
                            <a type="button"
                               class="w-[18.8%] text-center ms-auto xxx:text-xs xxs:text-base ss:text-base sm:text-lg lg:text-base xs:px-0.5 py-2 border border-gray-800 text-gray-800 rounded hover:bg-black hover:text-white focus:outline-none">
                                <span class="w-11/12 "></span> 看更多
                                <i class="fas fa-chevron-right w-1/12"> </i>
                            </a>
                        </div>

                        <nav
                            class="flex flex-wrap  justify-between mt-2 w-full  items-end relative overflow-visible group"
                            role="tablist" id="itinerary_tab">
                            <?php
                            $itinerary = [
                                [
                                    'id' => 'all-tab',
                                    'tab' => '近期活動',
                                    'data' => [
                                        ["uuid" => "1", "time" => '11/04~11/06', 'name' => "桃山", "img" => Storage::url('poc1.jpg')],
                                        ["uuid" => "2", "time" => '11/07~11/09', 'name' => "塔塔加", "img" => Storage::url('poc2.jpg')],
                                        ["uuid" => "3", "time" => '11/10~11/12', 'name' => "玉山", "img" => Storage::url('poc3.jpg')],
                                        ["uuid" => "4", "time" => '11/13~11/15', 'name' => "雪山", "img" => Storage::url('poc4.jpg')],
                                        ["uuid" => "5", "time" => '11/16~11/18', 'name' => "合歡山", "img" => Storage::url('poc5.jpg')],
                                        ["uuid" => "6", "time" => '11/19~11/21', 'name' => "阿里山", "img" => Storage::url('poc1.jpg')]
                                    ]
                                ],
                                [
                                    'id' => 'soon-tab',
                                    'tab' => '即將成團',
                                    'data' => [
                                        ["time" => '12/01~12/03', 'name' => "太平山"],
                                        ["time" => '12/05~12/07', 'name' => "南湖大山"],
                                        ["time" => '12/10~12/12', 'name' => "大霸尖山"],
                                        ["time" => '12/15~12/17', 'name' => "雪山"],
                                        ["time" => '12/15~12/17', 'name' => "雪山"],
                                        ["time" => '12/20~12/22', 'name' => "大鬼湖"]
                                    ]
                                ],
                                [
                                    'id' => 'high',
                                    'tab' => '高山百岳',
                                    'data' => [
                                        ["time" => '12/25~12/27', 'name' => "玉山"],
                                        ["time" => '12/28~12/30', 'name' => "合歡山"],
                                        ["time" => '01/05~01/07', 'name' => "雪山"],
                                        ["time" => '12/15~12/17', 'name' => "雪山"],
                                        ["time" => '01/10~01/12', 'name' => "中央山脈"],
                                        ["time" => '01/15~01/17', 'name' => "大霸尖山"]
                                    ]
                                ],
                                [
                                    'id' => 'low',
                                    'tab' => '簡單郊山',
                                    'data' => [
                                        ["time" => '11/22~11/24', 'name' => "鶯歌山"],
                                        ["time" => '11/25~11/27', 'name' => "台北陽明山"],
                                        ["time" => '12/01~12/03', 'name' => "象山"],
                                        ["time" => '12/05~12/07', 'name' => "大溪山"],
                                        ["time" => '12/15~12/17', 'name' => "雪山"],
                                        ["time" => '12/10~12/12', 'name' => "九份山"]
                                    ]
                                ],
                                [
                                    'id' => 'more',
                                    'tab' => '更多',
                                    'data' => [
                                        ["time" => '12/10~12/12', 'name' => "大霸尖山"],
                                        ["time" => '01/10~01/12', 'name' => "南湖大山"],
                                        ["time" => '01/15~01/17', 'name' => "玉山"],
                                        ["time" => '12/15~12/17', 'name' => "雪山"],
                                        ["time" => '01/20~01/22', 'name' => "雪山"],
                                        ["time" => '02/01~02/03', 'name' => "合歡山"]
                                    ]
                                ]
                            ];
                            ?>

                            @foreach($itinerary as $index => $v)
                                <button
                                    class="text-[#4d4c4c] tabutton h-full  ss:py-1 xxx:w-[19.5%] xxs:w-[18.8%] {{--us-420:w-[18%]  lg:w-[18%]--}} active:bg-[#5B9894FF] bg-[#b8cac9] rounded-t-lg text-center {{ $index == 0 ? 'active' : '' }} group element  hover:shadow-md transition-all transform duration-300 ease-out relative"
                                    data-bs-toggle="tab"
                                    data-bs-target="#{{$v['id']}}"
                                    role="tab"
                                    aria-controls="{{$v['id']}}"
                                    aria-selected="{{ $index == 0 ? 'true' : 'false' }}">

                                    <!-- Tab content -->
                                    <span
                                        class=" {{--px-2--}}  xxx:text-xs  ss:text-sm  group-hover:translate-y-[-5px]  group-active:translate-y-[-2px]">
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
                                                <li class="xxx:pt-2 xs:pt-3 pl-2 sm:pt-4 sm:pl-3  ss:py-2  lg:py-3 hover:scale-105 hover:z-50 hover:shadow-[0_-8px_12px_-2px_rgba(0,0,0,0.15),0_8px_12px_-2px_rgba(0,0,0,0.15)] hover:rounded-xl transition-all transform itinerary_tab {{--hover:bg-[#dbdada]--}} bg-[#efefef] border-b border-b-[#a8b6ad]"
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
                                                                class="flex flex-wrap xxx:gap-1 ss:gap-1.5  items-center">
                                                                <span
                                                                    class="ss:badge xxx:p-0.5 xxs:px-1 ss:px-1.5 rounded-pill text-black bg-[#bccbc8] text-[#212121] text-xs sm:text-sm">C級</span>
                                                                <span
                                                                    class="ss:badge xxx:p-0.5 xxs:px-1 ss:px-1.5 rounded-pill  text-black bg-[#bccbc8] text-[#212121] text-xs sm:text-sm">郊山</span>
                                                                <span
                                                                    class="ss:badge xxx:p-0.5 xxs:px-1 ss:px-1.5 rounded-pill text-black bg-[#bccbc8] text-[#212121] text-xs sm:text-sm">進階健行</span>
                                                            </div>
                                                        </div>
                                                        <div class="w-1/5 flex flex-col align-end h-full">
                                                            <button type="button "
                                                                    class="w-11/12 xxx:p-1  sm:p-2  rounded-full bg-[#55958d] text-[#f7dbab] hover:text-[#467771] hover:bg-[#f8b551] xxx:text-xs xxs:text-sm xs:text-base sm:text-lg lg:text-base ">
                                                                報名中

                                                            </button>
                                                        </div>

                                                        {{--                                                        <button type="button"--}}
                                                        {{--                                                                class="py-1 sm:px-4 px-2 rounded-full btn btn-cus btn-outline-dark ms-auto ">--}}
                                                        {{--                                                            報名中--}}
                                                        {{--                                                        </button>--}}
                                                    </div>
                                                    <div
                                                        class="lg:hidden xxx:mr-2 sm:mr-3 flex justify-center  items-center ">
                                                        <i class="fas fa-angle-down "> </i>

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
                <div class="mt-5  lg:w-[418px]   ">

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

                            <div
                                class="swiper-button-next Itinerary z-21 s-cus bg-black bg-opacity-50 rounded-full p-3 scale-50 font-extrabold "></div>
                            <div
                                class="swiper-button-prev Itinerary z-21 s-cus bg-black bg-opacity-50 rounded-full p-3 scale-50 font-extrabold"></div>
                            <div class="swiper-pagination Itinerary z-21 s-cus bg-black w-full bg-opacity-10  "></div>


                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <section class="flex items-center flex-col ">

        <div
            class="flex flex-wrap justify-center  xl:w-[1040px] lg:w-[900px] md:mx-24 sm:mx-16 lg:mx-4 xs:mx-6 xxx:mx-2">


            @for ($i = 0; $i < 4; $i++)
                <a href="#"
                   class="w-full xxx:w-1/2 lg:w-1/4 p-1 transform transition-transform duration-300 hover:scale-105 hover:z-50">
                    <div class="bg-white bg-opacity-50 shadow-lg rounded-lg overflow-hidden">
                        <img src="https://via.placeholder.com/1024x1024" class="w-full h-auto">
                        <div
                            class="xxx:p-2 xs:p-3 ss:p-4 lg:p-3 xxx:text-base md:text-lg lg:text-base xl:text-lg  text-center font-bold">
                            登山實用
                        </div>
                    </div>
                </a>
            @endfor


        </div>

        <div class="my-5 text-center">
            <a href="#"
               class="inline-flex items-center px-4 py-2 text-white bg-primary rounded-lg hover:bg-blue-600 transition duration-300 transform hover:translate-x-1">
                看更多文章
                <svg class="ml-2 w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5-5 5m6-5H6"/>
                </svg>
            </a>
        </div>


    </section>

@endsection

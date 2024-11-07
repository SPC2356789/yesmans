@extends('Layouts.app')
@section($Slug,'active')
{{--{{dd($Carousels)}}--}}
@section('tlink')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

@endsection

@section('blink')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endsection
@section('content')
    <section class="homepage-carousel ">
        <div class="md:h-screen h-[75vh] ">
            <!-- 外层容器设置为屏幕高度 -->
            <div class="h-full w-full flex items-center justify-center ">
                <!-- Swiper 容器 -->
                <div class="swiper carousel w-full h-full fade-on-scroll opacity-100">
                    <!-- Swiper 的内容 -->
                    <div class="swiper-wrapper ">
                        @foreach($Carousels as $Carousel)
                            <div class="swiper-slide">
                                <img class="w-full h-full object-cover "
                                     src="{{ Storage::url($Carousel['image_path']) }}" alt="{{$Carousel['alt']??''}}">
                            </div>
                        @endforeach
                    </div>


                </div>
            </div>
        </div>
    </section>

    <section class="py-5 px-2">
        <div class="lg:mx-14 md:mx-16 mx-1 sm:mx-8 xl:mx-40 2xl:mx-60">
            <div class="flex flex-col md:flex-col lg:flex-row justify-center gap-6 lg:gap-8 xl:gap-16 2xl:gap-40 ">
                <div id="targetElement" class="w-full xl:w-[600px] lg:w-1/2">
                    <section>
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="fw-bold">行程訊息</h3>
                            <a type="button" class="btn btn-outline-dark btn-cus ms-auto">看更多
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>

                        <nav class="flex flex-wrap sm:gap-2 justify-between mt-2 w-full" role="tablist"
                             id="itinerary_tab">
                            <?php
                            $itinerary = [
                                [
                                    'id' => 'all-tab',
                                    'tab' => '近期活動',
                                    'data' => [
                                        ["time" => '11/04~11/06', 'name' => "桃山"],
                                        ["time" => '11/07~11/09', 'name' => "塔塔加"],
                                        ["time" => '11/10~11/12', 'name' => "玉山"],
                                        ["time" => '11/13~11/15', 'name' => "雪山"],
                                        ["time" => '11/16~11/18', 'name' => "合歡山"],
                                        ["time" => '11/19~11/21', 'name' => "阿里山"]
                                    ]
                                ],
                                [
                                    'id' => 'soon-tab',
                                    'tab' => '即將成團',
                                    'data' => [
                                        ["time" => '12/01~12/03', 'name' => "太平山"],
                                        ["time" => '12/05~12/07', 'name' => "南湖大山"],
                                        ["time" => '12/10~12/12', 'name' => "大霸尖山"],
                                        ["time" => '12/15~12/17', 'name' => "雪山"], ["time" => '12/15~12/17', 'name' => "雪山"],
                                        ["time" => '12/20~12/22', 'name' => "大鬼湖"]
                                    ]
                                ],
                                [
                                    'id' => 'high',
                                    'tab' => '百岳',
                                    'data' => [
                                        ["time" => '12/25~12/27', 'name' => "玉山"],
                                        ["time" => '12/28~12/30', 'name' => "合歡山"],
                                        ["time" => '01/05~01/07', 'name' => "雪山"], ["time" => '12/15~12/17', 'name' => "雪山"],
                                        ["time" => '01/10~01/12', 'name' => "中央山脈"],
                                        ["time" => '01/15~01/17', 'name' => "大霸尖山"]
                                    ]
                                ],
                                [
                                    'id' => 'low',
                                    'tab' => '郊山',
                                    'data' => [
                                        ["time" => '11/22~11/24', 'name' => "鶯歌山"],
                                        ["time" => '11/25~11/27', 'name' => "台北陽明山"],
                                        ["time" => '12/01~12/03', 'name' => "象山"],
                                        ["time" => '12/05~12/07', 'name' => "大溪山"], ["time" => '12/15~12/17', 'name' => "雪山"],
                                        ["time" => '12/10~12/12', 'name' => "九份山"]
                                    ]
                                ],
                                [
                                    'id' => 'more',
                                    'tab' => '更多',
                                    'data' => [
                                        ["time" => '12/10~12/12', 'name' => "大霸尖山"],
                                        ["time" => '01/10~01/12', 'name' => "南湖大山"],
                                        ["time" => '01/15~01/17', 'name' => "玉山"], ["time" => '12/15~12/17', 'name' => "雪山"],
                                        ["time" => '01/20~01/22', 'name' => "雪山"],
                                        ["time" => '02/01~02/03', 'name' => "合歡山"]
                                    ]
                                ]
                            ];
                            ?>

                            @foreach($itinerary as $index => $v)
                                <button
                                    class="tabutton w-[18%]  hover:bg-[#5B9894FF] bg-[#b8cac9] rounded-t-xl text-center p-0.5 sm:p-2 {{ $index == 0 ? 'active' : '' }}"
                                    type="button"
                                    data-bs-toggle="tab"
                                    data-bs-target="#{{$v['id']}}"
                                    role="tab"
                                    aria-controls="{{$v['id']}}"
                                    aria-selected="{{ $index == 0 ? 'true' : 'false' }}">
                <span class="text-white text-[12px] sm:text-[calc(100%+1px)]">
                    {{$v['tab']}}
                </span>
                                </button>
                            @endforeach
                        </nav>

                        <div class="tab-content" id="itinerary_tab_content">
                            @foreach($itinerary as $index => $v)
                                <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="{{$v['id']}}"
                                     role="tabpanel" aria-labelledby="{{$v['id']}}">
                                    <div class="card border-0">
                                        <ul class="list-group list-group-flush fs-6">
                                            @foreach($v['data'] as $a)
                                                <li class="list-group-item py-6 lg:py-2 hover:bg-[#e6efefff] hover:scale-105 hover:z-10 hover:shadow-xl hover:rounded-xl transition-all transform ">
                                                    <span
                                                        class="block fw-bold my-lg-2 fs-5 text-left lg:order-1">{{$a['name']}}</span>
                                                    <div class="flex align-center justify-center gap-2 items-center">
                                                        <div class="flex sm:flex-row flex-col sm:gap-2">
                                                            <span class="text-amber-700">{{$a['time']}}</span>
                                                            <div class="">
                                                                <span
                                                                    class="badge rounded-pill bg-secondary text-black">C級</span>
                                                                <span
                                                                    class="badge rounded-pill bg-secondary text-black">郊山</span>
                                                                <span
                                                                    class="badge rounded-pill bg-secondary text-black">進階健行</span>
                                                            </div>
                                                        </div>
                                                        <button type="button"
                                                                class="py-1 sm:px-2 px-1 rounded-full bg-[#f9d9a8] text-[#284341] hover:text-[#ffffff] hover:bg-[#e26d1d] ms-auto">
                                                            報名中
                                                        </button>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    {{--                                                    <button type="button"--}}
                    {{--                                                            class="btn btn-outline-warning btn-cus ms-auto">--}}
                    {{--                                                        再3人成團--}}
                    {{--                                                    </button>--}}
                </div>
                <div class="mt-5 w-full xl:max-w-[484px] lg:min-w-[455px] lg:max-w-[466px] lg:w-1/2">
                    <div class="swiper Itinerary">
                        <div class="swiper-wrapper">
                            <img class="swiper-slide" src="{{storage::url('poc1.jpg')}}" loading="lazy"/>
                            <img class="swiper-slide" src="{{storage::url('poc2.jpg')}}" loading="lazy"/>
                            <img class="swiper-slide" src="{{storage::url('poc3.jpg')}}" loading="lazy"/>
                            <img class="swiper-slide" src="{{storage::url('poc4.jpg')}}" loading="lazy"/>
                            <img class="swiper-slide" src="{{storage::url('poc5.jpg')}}" loading="lazy"/>
                            <div
                                class="swiper-button-next Itinerary s-cus bg-black bg-opacity-50 rounded-full p-3 scale-y-50 scale-x-75"></div>
                            <div
                                class="swiper-button-prev Itinerary s-cus bg-black bg-opacity-50 rounded-full p-3 scale-y-50 scale-x-75"></div>
                            <div class="swiper-pagination Itinerary s-cus bg-black w-full bg-opacity-25  "></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container mx-auto">
            <div class="flex flex-wrap">
                <a href="#"
                   class="w-full sm:w-1/2 md:w-1/2 lg:w-1/4 p-2 transform transition-transform duration-300 hover:scale-105">
                    <div class="bg-white bg-opacity-50 shadow-lg rounded-lg overflow-hidden">
                        <img src="https://via.placeholder.com/1024x1024" class="w-full h-auto">
                        <div class="p-4 text-center font-bold">登山實用</div>
                    </div>
                </a>
                <a href="#"
                   class="w-full sm:w-1/2 md:w-1/2 lg:w-1/4 p-2 transform transition-transform duration-300 hover:scale-105">
                    <div class="bg-white bg-opacity-50 shadow-lg rounded-lg overflow-hidden">
                        <img src="https://via.placeholder.com/1024x1024" class="w-full h-auto">
                        <div class="p-4 text-center font-bold">帶團契機</div>
                    </div>
                </a>
                <a href="#"
                   class="w-full sm:w-1/2 md:w-1/2 lg:w-1/4 p-2 transform transition-transform duration-300 hover:scale-105">
                    <div class="bg-white bg-opacity-50 shadow-lg rounded-lg overflow-hidden">
                        <img src="https://via.placeholder.com/1024x1024" class="w-full h-auto">
                        <div class="p-4 text-center font-bold">山上救護分享</div>
                    </div>
                </a>
                <a href="#"
                   class="w-full sm:w-1/2 md:w-1/2 lg:w-1/4 p-2 transform transition-transform duration-300 hover:scale-105">
                    <div class="bg-white bg-opacity-50 shadow-lg rounded-lg overflow-hidden">
                        <img src="https://via.placeholder.com/1024x1024" class="w-full h-auto">
                        <div class="p-4 text-center font-bold">惡劣天氣判斷</div>
                    </div>
                </a>
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

        </div>

        </div>
    </section>
    <!--post-->
    {{--    <section>--}}
    {{--        <div class="container">--}}
    {{--            <div class="row">--}}
    {{--                <div class="post-area-index font-weight-bold ">--}}

    {{--                    <div class="img_box">--}}
    {{--                        <img src="https://via.placeholder.com/1024x1024">--}}
    {{--                        <div class="post-title">登山實用</div>--}}
    {{--                    </div>--}}
    {{--                    <div class="img_box">--}}
    {{--                        <img src="https://via.placeholder.com/1024x1024">--}}
    {{--                        <div class="post-title">帶團契機</div>--}}
    {{--                    </div>--}}
    {{--                    <div class="img_box">--}}
    {{--                        <img src="https://via.placeholder.com/1024x1024">--}}
    {{--                        <div class="post-title">山上救護分享</div>--}}

    {{--                    </div>--}}
    {{--                    <div class="img_box">--}}
    {{--                        <img src="https://via.placeholder.com/1024x1024">--}}
    {{--                        <div class="post-title">惡劣天氣判斷</div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--                <div class="mt-5"><a class="text-decoration-none read_more" href="#">看更多文章....</a></div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </section>--}}

@endsection

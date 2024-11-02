@extends('Layouts.app')
@section($Slug,'active')
{{--{{dd($Carousels)}}--}}
@section('tlink')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    @vite(['resources/css/home.css'])

@endsection

@section('blink')
    {{--    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>--}}
    {{--    @viteNgrok([--}}
    {{--    'resources/js/home.js'])--}}
    {{--    @vite(['resources/js/home.js'])--}}
    <!-- Swiper JS -->
    <!-- Initialize Swiper -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper(".creative", {
            grabCursor: true,
            effect: "creative",
            // autoplay: {
            //     delay: 3000,
            //     disableOnInteraction: false,
            // },
            creativeEffect: {
                prev: {
                    shadow: true,
                    translate: [0, 0, -400],
                },
                next: {
                    translate: ["100%", 0, 0],
                },
            },
        });
    </script>
@endsection
@section('content')
    <section>
        <div id="carouselExampleInterval" class="carousel slide " data-bs-ride="carousel">
            <div class="carousel-inner col-12">
                @foreach($Carousels as $Carousel)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}" data-bs-interval="10000">
                        <img src="{{ Storage::url($Carousel['image_path']) }}" class="d-block w-100"
                             alt="{{$Carousel['alt']??''}}">
                    </div>
                @endforeach

            </div>

        </div>
    </section>
    <section class="py-5">
        <div class="lg:mx-14 md:mx-16 mx-8 xl:mx-48 2xl:mx-60">
            <div class="flex flex-col md:flex-col lg:flex-row justify-center gap-6 lg:gap-12 xl:gap-16 2xl:gap-40">
                <div class="w-full xl:w-[600px] lg:w-1/2">
                    <section>
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="fw-bold">行程訊息</h3>
                            <a type="button" class="btn btn-outline-dark btn-cus ms-auto">看更多
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>
                        <nav class="nav d-flex justify-content-between" id="" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#home"
                                   type="button" role="tab" aria-controls="all" aria-selected="true">近期活動</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                   type="button" role="tab" aria-controls="profile" aria-selected="false">百岳</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact"
                                   type="button" role="tab" aria-controls="contact" aria-selected="false">郊山</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="soon-tab" data-bs-toggle="tab" data-bs-target="#soon"
                                   type="button" role="tab" aria-controls="soon" aria-selected="false">即將成團</a>
                            </li>
                        </nav>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="all-tab">
                                <div class="card border-0">
                                    <ul class="list-group list-group-flush fs-6">
                                        <li class="list-group-item py-6 lg:py-2">
                                            <div class="flex flex-col lg:flex-row justify-between items-start">
            <span class="flex items-center text-cus mb-2 lg:mb-0 lg:order-2">
                2024 <strong class="ml-2">11/04</strong>
            </span>
                                                <span
                                                    class="block fw-bold my-lg-2 fs-5 text-left lg:order-1">松羅湖</span>
                                            </div>
                                            <div class="flex gap-1 items-center">
                                                <span class="badge rounded-pill bg-secondary text-black">B級</span>
                                                <span class="badge rounded-pill bg-secondary text-black">郊山</span>
                                                <span class="badge rounded-pill bg-secondary text-black">進階健行</span>
                                                <button type="button" class="btn btn-outline-primary btn-cus ms-auto">
                                                    報名中
                                                </button>
                                            </div>
                                        </li>
                                        <li class="list-group-item py-6 lg:py-2">
                                            <div class="flex flex-col lg:flex-row justify-between items-start">
            <span class="flex items-center text-cus mb-2 lg:mb-0 lg:order-2">
                2024 <strong class="ml-2">11/07</strong>
            </span>
                                                <span
                                                    class="block fw-bold my-lg-2 fs-5 text-left lg:order-1">大鬼湖</span>
                                            </div>
                                            <div class="flex gap-1 items-center">
                                                <span class="badge rounded-pill bg-secondary text-black">C級</span>
                                                <span class="badge rounded-pill bg-secondary text-black">郊山</span>
                                                <span class="badge rounded-pill bg-secondary text-black">進階健行</span>
                                                <button type="button" class="btn btn-outline-primary btn-cus ms-auto">
                                                    報名中
                                                </button>
                                            </div>
                                        </li>
                                        <li class="list-group-item py-6 lg:py-2">
                                            <div class="flex flex-col lg:flex-row justify-between items-start">
            <span class="flex items-center text-cus mb-2 lg:mb-0 lg:order-2">
                2024 <strong class="ml-2">11/11</strong>
            </span>
                                                <span
                                                    class="block fw-bold my-lg-2 fs-5 text-left lg:order-1">五寮尖</span>
                                            </div>
                                            <div class="flex gap-1 items-center">
                                                <span class="badge rounded-pill bg-secondary text-black">A級</span>
                                                <span class="badge rounded-pill bg-secondary text-black">高山</span>
                                                <span class="badge rounded-pill bg-secondary text-black">挑戰健行</span>
                                                <button type="button" class="btn btn-outline-primary btn-cus ms-auto">
                                                    報名中
                                                </button>
                                            </div>
                                        </li>
                                        <li class="list-group-item py-6 lg:py-2">
                                            <div class="flex flex-col lg:flex-row justify-between items-start">
            <span class="flex items-center text-cus mb-2 lg:mb-0 lg:order-2">
                2024 <strong class="ml-2">11/18</strong>
            </span>
                                                <span
                                                    class="block fw-bold my-lg-2 fs-5 text-left lg:order-1">水漾森林</span>
                                            </div>
                                            <div class="flex gap-1 items-center">
                                                <span class="badge rounded-pill bg-secondary text-black">C級</span>
                                                <span class="badge rounded-pill bg-secondary text-black">林道</span>
                                                <span class="badge rounded-pill bg-secondary text-black">休閒健行</span>
                                                <button type="button" class="btn btn-outline-primary btn-cus ms-auto">
                                                    報名中
                                                </button>
                                            </div>
                                        </li>
                                        <li class="list-group-item py-6 lg:py-2">
                                            <div class="flex flex-col lg:flex-row justify-between items-start">
            <span class="flex items-center text-cus mb-2 lg:mb-0 lg:order-2">
                2024 <strong class="ml-2">11/25</strong>
            </span>
                                                <span
                                                    class="block fw-bold my-lg-2 fs-5 text-left lg:order-1">合歡山</span>
                                            </div>
                                            <div class="flex gap-1 items-center">
                                                <span class="badge rounded-pill bg-secondary text-black">A級</span>
                                                <span class="badge rounded-pill bg-secondary text-black">高山</span>
                                                <span class="badge rounded-pill bg-secondary text-black">挑戰健行</span>
                                                <button type="button" class="btn btn-outline-primary btn-cus ms-auto">
                                                    報名中
                                                </button>
                                            </div>
                                        </li>
                                        <li class="list-group-item py-6 lg:py-2">
                                            <div class="flex flex-col lg:flex-row justify-between items-start">
            <span class="flex items-center text-cus mb-2 lg:mb-0 lg:order-2">
                2024 <strong class="ml-2">12/02</strong>
            </span>
                                                <span
                                                    class="block fw-bold my-lg-2 fs-5 text-left lg:order-1">加羅湖</span>
                                            </div>
                                            <div class="flex gap-1 items-center">
                                                <span class="badge rounded-pill bg-secondary text-black">B級</span>
                                                <span class="badge rounded-pill bg-secondary text-black">湖泊</span>
                                                <span class="badge rounded-pill bg-secondary text-black">休閒健行</span>
                                                <button type="button" class="btn btn-outline-primary btn-cus ms-auto">
                                                    報名中
                                                </button>
                                            </div>
                                        </li>
                                    </ul>

                                </div>

                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="card border-0">
                                    <ul class="list-group list-group-flush fs-6">
                                        <li class="list-group-item py-6">
                        <span class="d-flex align-items-center text-cus">2024
                            <h4 class="mb-0 ms-2">11/04</h4>
                        </span>
                                            <span class="d-block fw-bold  my-1 my-lg-2 fs-5">松羅湖</span>
                                            <div class="d-flex gap-1 align-items-center">
                                                <span class="badge rounded-pill bg-secondary text-black">B級</span>
                                                <span class="badge rounded-pill bg-secondary text-black">郊山</span>
                                                <span class="badge rounded-pill bg-secondary text-black">進階健行</span>
                                                <button type="button" class="btn btn-outline-primary btn-cus ms-auto">
                                                    報名中
                                                </button>
                                            </div>
                                        </li>
                                        <li class="list-group-item py-6">
                        <span class="d-flex align-items-center text-cus">2024
                            <h4 class="mb-0 ms-2">11/11</h4>
                        </span>
                                            <span class="d-block fw-bold  my-1 my-lg-2 fs-5">五寮尖</span>
                                            <div class="d-flex gap-1 align-items-center">
                                                <span class="badge rounded-pill bg-secondary text-black">A級</span>
                                                <span class="badge rounded-pill bg-secondary text-black">高山</span>
                                                <span class="badge rounded-pill bg-secondary text-black">挑戰健行</span>
                                                <button type="button" class="btn btn-outline-primary btn-cus ms-auto">
                                                    報名中
                                                </button>
                                            </div>
                                        </li>
                                        <li class="list-group-item py-6">
                        <span class="d-flex align-items-center text-cus">2024
                            <h4 class="mb-0 ms-2">11/18</h4>
                        </span>
                                            <span class="d-block fw-bold  my-1 my-lg-2 fs-5">水漾森林</span>
                                            <div class="d-flex gap-1 align-items-center">
                                                <span class="badge rounded-pill bg-secondary text-black">C級</span>
                                                <span class="badge rounded-pill bg-secondary text-black">林道</span>
                                                <span class="badge rounded-pill bg-secondary text-black">休閒健行</span>
                                                <button type="button" class="btn btn-outline-primary btn-cus ms-auto">
                                                    報名中
                                                </button>
                                            </div>
                                        </li>
                                        <li class="list-group-item py-6">
                        <span class="d-flex align-items-center text-cus">2024
                            <h4 class="mb-0 ms-2">11/25</h4>
                        </span>
                                            <span class="d-block fw-bold  my-1 my-lg-2 fs-5">合歡山</span>
                                            <div class="d-flex gap-1 align-items-center">
                                                <span class="badge rounded-pill bg-secondary text-black">A級</span>
                                                <span class="badge rounded-pill bg-secondary text-black">高山</span>
                                                <span class="badge rounded-pill bg-secondary text-black">挑戰健行</span>
                                                <button type="button" class="btn btn-outline-primary btn-cus ms-auto">
                                                    報名中
                                                </button>
                                            </div>
                                        </li>
                                        <li class="list-group-item py-6">
                        <span class="d-flex align-items-center text-cus">2024
                            <h4 class="mb-0 ms-2">12/02</h4>
                        </span>
                                            <span class="d-block fw-bold  my-1 my-lg-2 fs-5">加羅湖</span>
                                            <div class="d-flex gap-1 align-items-center">
                                                <span class="badge rounded-pill bg-secondary text-black">B級</span>
                                                <span class="badge rounded-pill bg-secondary text-black">湖泊</span>
                                                <span class="badge rounded-pill bg-secondary text-black">休閒健行</span>
                                                <button type="button" class="btn btn-outline-primary btn-cus ms-auto">
                                                    報名中
                                                </button>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                <div class="card border-0">
                                    <ul class="list-group list-group-flush fs-6">
                                        <li class="list-group-item py-6">
                        <span class="d-flex align-items-center text-cus">2024
                            <h4 class="mb-0 ms-2">11/04</h4>
                        </span>
                                            <span class="d-block fw-bold  my-1 my-lg-2 fs-5 ">松羅湖</span>
                                            <div class="d-flex gap-1 align-items-center">
                                                <span class="badge rounded-pill bg-secondary text-black">B級</span>
                                                <span class="badge rounded-pill bg-secondary text-black">郊山</span>
                                                <span class="badge rounded-pill bg-secondary text-black">進階健行</span>
                                                <button type="button" class="btn btn-outline-primary btn-cus ms-auto">
                                                    報名中
                                                </button>
                                            </div>
                                        </li>
                                        <li class="list-group-item py-6">
                        <span class="d-flex align-items-center text-cus">2024
                            <h4 class="mb-0 ms-2">11/11</h4>
                        </span>
                                            <span class="d-block fw-bold  my-1 my-lg-2 fs-5">五寮尖</span>
                                            <div class="d-flex gap-1 align-items-center">
                                                <span class="badge rounded-pill bg-secondary text-black">A級</span>
                                                <span class="badge rounded-pill bg-secondary text-black">高山</span>
                                                <span class="badge rounded-pill bg-secondary text-black">挑戰健行</span>
                                                <button type="button" class="btn btn-outline-primary btn-cus ms-auto">
                                                    報名中
                                                </button>
                                            </div>
                                        </li>
                                        <li class="list-group-item py-6">
                        <span class="d-flex align-items-center text-cus">2024
                            <h4 class="mb-0 ms-2">11/18</h4>
                        </span>
                                            <span class="d-block fw-bold  my-1 my-lg-2 fs-5">水漾森林</span>
                                            <div class="d-flex gap-1 align-items-center">
                                                <span class="badge rounded-pill bg-secondary text-black">C級</span>
                                                <span class="badge rounded-pill bg-secondary text-black">林道</span>
                                                <span class="badge rounded-pill bg-secondary text-black">休閒健行</span>
                                                <button type="button" class="btn btn-outline-primary btn-cus ms-auto">
                                                    報名中
                                                </button>
                                            </div>
                                        </li>
                                        <li class="list-group-item py-6">
                        <span class="d-flex align-items-center text-cus">2024
                            <h4 class="mb-0 ms-2">11/25</h4>
                        </span>
                                            <span class="d-block fw-bold  my-1 my-lg-2 fs-5">合歡山</span>
                                            <div class="d-flex gap-1 align-items-center">
                                                <span class="badge rounded-pill bg-secondary text-black">A級</span>
                                                <span class="badge rounded-pill bg-secondary text-black">高山</span>
                                                <span class="badge rounded-pill bg-secondary text-black">挑戰健行</span>
                                                <button type="button" class="btn btn-outline-primary btn-cus ms-auto">
                                                    報名中
                                                </button>
                                            </div>
                                        </li>
                                        <li class="list-group-item py-6">
                        <span class="d-flex align-items-center text-cus">2024
                            <h4 class="mb-0 ms-2">12/02</h4>
                        </span>
                                            <span class="d-block fw-bold  my-1 my-lg-2 fs-5">加羅湖</span>
                                            <div class="d-flex gap-1 align-items-center">
                                                <span class="badge rounded-pill bg-secondary text-black">B級</span>
                                                <span class="badge rounded-pill bg-secondary text-black">湖泊</span>
                                                <span class="badge rounded-pill bg-secondary text-black">休閒健行</span>
                                                <button type="button" class="btn btn-outline-primary btn-cus ms-auto">
                                                    報名中
                                                </button>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                            <div class="tab-pane fade" id="soon" role="tabpanel" aria-labelledby="soon-tab">
                                <div class="card border-0">
                                    <ul class="list-group list-group-flush fs-6">
                                        <li class="list-group-item py-6">
                        <span class="d-flex align-items-center text-cus">2024
                            <h4 class="mb-0 ms-2">11/04</h4>
                        </span>
                                            <span class="d-block fw-bold  my-1 my-lg-2 fs-5">松羅湖-即將</span>
                                            <div class="d-flex gap-1 align-items-center">
                                                <span class="badge rounded-pill bg-secondary text-black">B級</span>
                                                <span class="badge rounded-pill bg-secondary text-black">郊山</span>
                                                <span class="badge rounded-pill bg-secondary text-black">進階健行</span>
                                                <button type="button" class="btn btn-outline-warning btn-cus ms-auto">
                                                    再3人成團
                                                </button>
                                            </div>
                                        </li>


                                        <li class="list-group-item py-6">
                        <span class="d-flex align-items-center text-cus">2024
                            <h4 class="mb-0 ms-2">11/11</h4>
                        </span>
                                            <span class="d-block fw-bold  my-1 my-lg-2 fs-5">五寮尖</span>
                                            <div class="d-flex gap-1 align-items-center">
                                                <span class="badge rounded-pill bg-secondary text-black">A級</span>
                                                <span class="badge rounded-pill bg-secondary text-black">高山</span>
                                                <span class="badge rounded-pill bg-secondary text-black">挑戰健行</span>
                                                <button type="button" class="btn btn-outline-primary btn-cus ms-auto">
                                                    報名中
                                                </button>
                                            </div>
                                        </li>
                                        <li class="list-group-item py-6">
                        <span class="d-flex align-items-center text-cus">2024
                            <h4 class="mb-0 ms-2">11/18</h4>
                        </span>
                                            <span class="d-block fw-bold  my-1 my-lg-2 fs-5">水漾森林</span>
                                            <div class="d-flex gap-1 align-items-center">
                                                <span class="badge rounded-pill bg-secondary text-black">C級</span>
                                                <span class="badge rounded-pill bg-secondary text-black">林道</span>
                                                <span class="badge rounded-pill bg-secondary text-black">休閒健行</span>
                                                <button type="button" class="btn btn-outline-primary btn-cus ms-auto">
                                                    報名中
                                                </button>
                                            </div>
                                        </li>
                                        <li class="list-group-item py-6">
                        <span class="d-flex align-items-center text-cus">2024
                            <h4 class="mb-0 ms-2">11/25</h4>
                        </span>
                                            <span class="d-block fw-bold  my-1 my-lg-2 fs-5">合歡山</span>
                                            <div class="d-flex gap-1 align-items-center">
                                                <span class="badge rounded-pill bg-secondary text-black">A級</span>
                                                <span class="badge rounded-pill bg-secondary text-black">高山</span>
                                                <span class="badge rounded-pill bg-secondary text-black">挑戰健行</span>
                                                <button type="button" class="btn btn-outline-primary btn-cus ms-auto">
                                                    報名中
                                                </button>
                                            </div>
                                        </li>
                                        <li class="list-group-item py-6">
                        <span class="d-flex align-items-center text-cus">2024
                            <h4 class="mb-0 ms-2">12/02</h4>
                        </span>
                                            <span class="d-block fw-bold  my-1 my-lg-2 fs-5">加羅湖</span>
                                            <div class="d-flex gap-1 align-items-center">
                                                <span class="badge rounded-pill bg-secondary text-black">B級</span>
                                                <span class="badge rounded-pill bg-secondary text-black">湖泊</span>
                                                <span class="badge rounded-pill bg-secondary text-black">休閒健行</span>
                                                <button type="button" class="btn btn-outline-warning btn-cus ms-auto">
                                                    再3人成團
                                                </button>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </section>
                </div>
                <div class="mt-5 w-full xl:max-w-[484px] lg:min-w-[455px] lg:max-w-[466px] lg:w-1/2">
                    <div class="swiper creative">
                        <div class="swiper-wrapper">
                            <img class="swiper-slide  " src="{{storage::url('poc1.jpg')}}" loading="lazy"/>
                            <img class="swiper-slide" src="{{storage::url('poc2.jpg')}}" loading="lazy"/>
                            <img class="swiper-slide" src="{{storage::url('poc3.jpg')}}" loading="lazy"/>
                            <img class="swiper-slide" src="{{storage::url('poc4.jpg')}}" loading="lazy"/>
                            <img class="swiper-slide" src="{{storage::url('poc5.jpg')}}" loading="lazy"/>
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

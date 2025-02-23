<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('metaInfo')
    @yield('tLink')
    @vite(['resources/js/app.js','resources/css/app.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="{{asset("vendor/cookie-consent/css/cookie-consent.css")}}">
</head>

<body id="page-top" class="@yield('bg') flex flex-col justify-between h-screen overflow-hidden" data-page="{{($jsPage??($Slug??''))}}">
@if (config('services.GTM.enabled'))
    <!-- Google Tag Manager (noscript) -->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id={{ config('services.GTM.id') }}"
                height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->
@endif
<div class="overlay"></div>
<div class="yesman-container" id="yesmanContainer">
    <span class="letter">Y</span>
    <span class="letter">E</span>
    <span class="letter">S</span>
    <span class="letter">&nbsp;</span>
    <span class="letter">M</span>
    <span class="letter">A</span>
    <span class="letter">N</span>
</div>
<div class="progress-container mt-3" id="progressContainer">
    <div class="progress">
        <div class="progress-bar" id="progressBar"></div> <!-- 進度條內的文字 -->
    </div>
</div>

<header class="">
    <nav class="navbar navbar-expand-lg navbar-light navbar-shrink h-auto flex flex-col" id="mainNav">
        <div
            class="flex flex-col items-center gap-4 xl:w-[1040px] lg:w-[900px] w-[500px]  xxx:w-full md:px-16 sm:px-20 lg:px-0 ss:px-12 xxx:px-4 xxs:px-6">
            {{--            <a class="navbar-brand" href="\">{{$generals['brand_name']??''}}</a>--}}
            <div class="flex flex-row-reverse  justify-between w-full items-center gap-0.5">
                <a class="flex items-end justify-start max-h-12 mr-2 order-4" href="/" id="brand-link">
                    @if($generals)
                        <div
                            class="relative sm:min-w-16 sm:h-12 min-h-9 aspect-w-4 aspect-h-3 min-w-12 overflow-hidden">
                            @foreach($generals['brand'] as $general)
                                @if($loop->iteration !== 3)
                                    <img
                                        class="absolute left-0 w-auto h-full object-cover {{ $loop->first ? 'opacity-100 top-0 z-10' : 'z-0 bottom-0 opacity-0 translate-y-5 transition-all duration-[3000ms] ease-in-out' }}"
                                        src="{{ Storage::url($general['data']['image']) }}"
                                        alt="YESMAN_LOGO"
                                        id="{{ $loop->first ?'':'hover-image'}}"
                                    >
                                @endif
                            @endforeach
                        </div>
                        @if(isset($generals['brand'][2]))
                            <div class="aspect-w-16 aspect-h-5 min-h-8 sm:h-12 h-10 pt-2.5 sm:pt-2 flex">
                                <img class="h-full  object-contain"
                                     src="{{ Storage::url($generals['brand'][2]['data']['image']) }}" alt="YESMAN首頁">
                            </div>
                        @endif

                    @endif
                </a>
                <div class="flex flex-row xsm:gap-2 xxx:gap-1 items-center">
                    <div class="max-h-8 min-h-6 flex items-center order-3 lg:hidden">
                        <button
                            class=" navbar-toggler xxx:px-1 xsm:px-2 sm:px-6 navbar-toggler-right xxx:text-xs xsm:text-sm hover:bg-yes-major "
                            type="button"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#Menu" aria-controls="Menu">
                            選單
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>

                    <div class="offcanvas offcanvas-start w-auto order-1" tabindex="-1" id="Menu"
                         aria-labelledby="MenuLabel">
                        <div class="offcanvas-header">
                            <button type="button" class="btn-close ms-auto text-reset" data-bs-dismiss="offcanvas"
                                    aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body lg992:ms-auto ">
                            <ul class="navbar-nav   md:gap-3 lg:gap-4 lg:items-center">
                                <li class="nav-item "><a class="px-0 nav-link @yield('index')" href="/"><i
                                            class="fa-solid fa-house"></i><span class="ml-1">首頁</span></a></li>
                                <li class="nav-item"><a class="px-0 nav-link @yield('about')" href="/about">
                                        <i class="fa-solid fa-mountain-sun"></i><span class="ml-1">關於我們</span></a>
                                </li>
                                <li class="nav-item"><a class="px-0 nav-link @yield('itinerary')" href="/itinerary">
                                        <i class="fa-solid fa-person-hiking"></i><span class="ml-1">行程資訊</span></a>
                                </li>
                                <li class="nav-item"><a class="px-0 nav-link @yield('blog')" href="/blog">
                                        <i cflex justify-centerlass="fa-solid fa-newspaper"></i><span
                                            class="ml-1">文章區</span></a></li>
                                <li class="nav-item">
                                    <button class="px-0 nav-link " name="getOrder">
                                        <i class="fa-solid fa-newspaper"></i><span class="ml-1">訂單查詢</span></button>
                                </li>
                            </ul>

                        </div>
                    </div>
                    <div class="order-2 xxx:pl-0.5 xsm:pl-3">
                        <div class="flex items-center rounded-md bg-white outline outline-1 outline-gray-300
                focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-neutral-800
                max-w-sm mx-auto max-h-12">
                            <i class="fa-regular fa-compass pl-2 pr-1 cursor-pointer" id="itinerary_compass"></i>
                            <input type="text" id="tripTerm"
                                   class="xsm:w-[90px] xxx:w-[70px] grow my-1 mx-1 xxx:text-xs xsm:text-sm text-gray-900 placeholder:text-gray-400
                      focus:outline-none "
                                   placeholder="探索行程">
                        </div>
                    </div>


                </div>
            </div>
        </div>

    </nav>
{{--    <nav class="" id="B">--}}
{{--    </nav>--}}

</header>
<div class="h-screen overflow-auto" id="scrollable-content">

    @yield('head_content')
    <section class="flex justify-center w-full h-auto">
        <div
            class="flex flex-col w-full xl:w-[1040px] lg:w-[900px] xxs:px-6 md:px-16 sm:px-20 lg:px-0 xs:px-6 ss:px-12 xxx:px-4 py-5 lg:gap-5 md:gap-3 xxx:gap-2">
            @yield('content')
        </div>
    </section>
    <section class="bg-white">
        <div class="pt-16 lg:px-5">
            <div class="flex items-center justify-center gap-4">
                @if ($foots && isset($foots['media']))
                    @foreach ($foots['media'] as $foot)
                        @if ($foot['data']['status'])
                            <a href="{{ $foot['data']['url'] ?? '#' }}" class="w-12 h-12 transition-transform hover:scale-110">
                                <img src="{{ Storage::url($foot['data']['image']) }}" class="w-full h-full object-cover" alt="Social Media Icon">
                            </a>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
        <footer class="bg-white py-6 lg:px-5">
            <div class="flex flex-col gap-4 items-center sm:grid sm:grid-cols-12">
                <!-- 左側假元素（占 4 格，隱藏在 sm 以上） -->
                <div class="col-span-4 hidden sm:block"></div>
                <!-- 版權文字（占 4 格，居中；在 sm 以上居中） -->
                <div class="col-span-4 text-center sm:w-full sm:text-center">
                    <div class="text-gray-700 text-sm">
                        {{ $foots['copyright'] ?? '' }}
                    </div>
                </div>
                <!-- 隱私權政策按鈕（占 4 格，靠右；在 sm 以上居中） -->
                <div class="col-span-4 text-right sm:text-center sm:w-full">
                    <a href="{{ route('privacy') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-yes-minor bg-yes-minor bg-opacity-10 rounded-full hover:bg-opacity-20 transition-colors duration-200">
                        隱私權政策
                    </a>
                </div>
            </div>
        </footer>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

@yield('blink')
</body>
</html>

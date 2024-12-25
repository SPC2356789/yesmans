<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('metaInfo')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

</head>

<body id="page-top" class="bg-light flex flex-col justify-between">
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
    <nav class="navbar navbar-expand-lg navbar-light navbar-shrink fixed-top h-auto flex flex-col" id="mainNav">
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

                <div class="max-h-8 min-h-6 flex items-center">
                    <button class=" btn-outline-primary navbar-toggler px-2 sm:px-6 navbar-toggler-right" type="button"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                        MENU
                        <i class="fas fa-bars"></i>
                    </button>
                </div>

                <div class="offcanvas offcanvas-start w-auto order-2" tabindex="-1" id="offcanvasExample"
                     aria-labelledby="offcanvasExampleLabel">
                    <div class="offcanvas-header">
                        <button type="button" class="btn-close ms-auto text-reset" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body lg992:ms-auto ">
                        <ul class="navbar-nav   md:gap-3 lg:gap-4 lg:items-center">
                            <li class="nav-item "><a class="px-0 nav-link @yield('index')" href="/"><i
                                        class="fa-solid fa-house"></i><span class="ml-1">首頁</span></a></li>
                            <li class="nav-item"><a class="px-0 nav-link @yield('about')" href="/about">
                                    <i class="fa-solid fa-mountain-sun"></i><span class="ml-1">關於我們</span></a></li>
                            <li class="nav-item"><a class="px-0 nav-link @yield('itinerary')" href="/itinerary_">
                                    <i class="fa-solid fa-person-hiking"></i><span class="ml-1">行程資訊</span></a></li>
                            <li class="nav-item"><a class="px-0 nav-link @yield('blog')" href="/blog">
                                    <i class="fa-solid fa-newspaper"></i><span class="ml-1">文章區</span></a></li>
                            <li class="nav-item"><a class="px-0 nav-link @yield('search')" href="">
                                    <i class="fa-solid fa-newspaper"></i><span class="ml-1">訂單查詢</span></a></li>
                        </ul>

                    </div>
                </div>
                <div class="flex items-ceenter hidden xsm:block order-1  xsm:ms-auto">

                    <div
                        class="flex items-center rounded-md bg-white  outline outline-1 -outline-offset-1 outline-gray-300 has-[input:focus-within]:outline has-[input:focus-within]:outline-2 has-[input:focus-within]:-outline-offset-2 has-[input:focus-within]:outline-neutral-800 max-w-sm mx-auto max-h-12">


{{--                        <div class="grid shrink-0 grid-cols-1 items-end focus-within:relative ">--}}
{{--                            <div class="relative col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-2 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-neutral-800 sm:text-sm/6" name="select">--}}

{{--                                <button type="button" data-select="none"--}}
{{--                                        class="inline-flex w-full justify-center gap-x-1   text-sm  text-gray-500 "--}}
{{--                                        id="menu-button" aria-expanded="true" aria-haspopup="true">--}}
{{--                                    <span>活動分類</span>--}}
{{--                                    <svg class="-mr-1 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"--}}
{{--                                         aria-hidden="true" data-slot="icon">--}}
{{--                                        <path fill-rule="evenodd"--}}
{{--                                              d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"--}}
{{--                                              clip-rule="evenodd"/>--}}
{{--                                    </svg>--}}
{{--                                </button>--}}
{{--                                <div--}}
{{--                                    class="absolute right-0 z-10 mt-2   origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none hidden"--}}
{{--                                    role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">--}}
{{--                                    <button href="#"--}}
{{--                                            class="global_sort"--}}
{{--                                            role="menuitem" tabindex="-1"--}}
{{--                                            data-value="Edit">近期活動--}}
{{--                                    </button>--}}
{{--                                    <button href="#"--}}
{{--                                            class="global_sort"--}}
{{--                                            role="menuitem" tabindex="-1"--}}
{{--                                            data-value="Duplicate">即將成團--}}
{{--                                    </button>--}}
{{--                                    <button href="#"--}}
{{--                                            class="global_sort"--}}
{{--                                            role="menuitem" tabindex="-1"--}}
{{--                                            data-value="Duplicate">高山百岳--}}
{{--                                    </button>--}}
{{--                                    <button href="#"--}}
{{--                                            class="global_sort"--}}
{{--                                            role="menuitem" tabindex="-1"--}}
{{--                                            data-value="Duplicate">簡單郊山--}}
{{--                                    </button>--}}

{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <input type="text" name="price" id="price"
                               class="block w-[88px] grow py-1.5 pl-1 pr-0.5 text-sm text-gray-900 placeholder:text-gray-400 focus:outline focus:outline-0 sm:text-sm/6"
                               placeholder="探索行程">
                    </div>
                </div>
            </div>
            <div class="flex items-center  w-full xsm:hidden">

                <div
                    class="flex items-center rounded-md bg-white  outline outline-1 -outline-offset-1 outline-gray-300 has-[input:focus-within]:outline has-[input:focus-within]:outline-2 has-[input:focus-within]:-outline-offset-2 has-[input:focus-within]:outline-neutral-800 max-w-sm mx-auto max-h-12">


{{--                    <div class="relative col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-2 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-neutral-800 sm:text-sm/6" name="select">--}}

{{--                        <button type="button" data-select="none"--}}
{{--                                class="inline-flex w-full justify-center gap-x-1   text-sm  text-gray-500 "--}}
{{--                                id="menu-button" aria-expanded="true" aria-haspopup="true">--}}
{{--                            <span>活動分類</span>--}}
{{--                            <svg class="-mr-1 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"--}}
{{--                                 aria-hidden="true" data-slot="icon">--}}
{{--                                <path fill-rule="evenodd"--}}
{{--                                      d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"--}}
{{--                                      clip-rule="evenodd"/>--}}
{{--                            </svg>--}}
{{--                        </button>--}}
{{--                        <div--}}
{{--                            class="absolute right-0 z-10 mt-2   origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none hidden"--}}
{{--                            role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">--}}
{{--                            <button href="#"--}}
{{--                                    class="global_sort"--}}
{{--                                    role="menuitem" tabindex="-1"--}}
{{--                                    data-value="Edit">近期活動--}}
{{--                            </button>--}}
{{--                            <button href="#"--}}
{{--                                    class="global_sort"--}}
{{--                                    role="menuitem" tabindex="-1"--}}
{{--                                    data-value="Duplicate">即將成團--}}
{{--                            </button>--}}
{{--                            <button href="#"--}}
{{--                                    class="global_sort"--}}
{{--                                    role="menuitem" tabindex="-1"--}}
{{--                                    data-value="Duplicate">高山百岳--}}
{{--                            </button>--}}
{{--                            <button href="#"--}}
{{--                                    class="global_sort"--}}
{{--                                    role="menuitem" tabindex="-1"--}}
{{--                                    data-value="Duplicate">簡單郊山--}}
{{--                            </button>--}}

{{--                        </div>--}}
{{--                    </div>--}}
                    <input type="text" name="price" id="price"
                           class="block w-[88px] grow py-1.5 pl-1 pr-0.5 text-sm text-gray-900 placeholder:text-gray-400 focus:outline focus:outline-0 sm:text-sm/6"
                           placeholder="探索行程">
                </div>
            </div>
        </div>

    </nav>
    <nav class="" id="B">
    </nav>

</header>
@yield('content')

<section class="bg-white">
    <div class="pt-16 lg:px-5">
        <div class="flex gap-3 justify-center">
            @if($foots)
                @foreach($foots['media'] as $foot)
                    @if($foot['data']['status'])
                        <a href="{{ $foot['data']['url'] ?? '#' }}" class="w-12 h-12">
                            <img src="{{ Storage::url($foot['data']['image']) }}" class="w-full h-full object-cover" alt="Social Media Icon">
                        </a>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
    <footer class="bg-white text-center py-6 lg:px-5">
        <div class="text-black">{{ $foots['copyright'] ?? '' }}</div>
    </footer>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

@yield('blink')
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    {{--統一字體--}}
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    @yield('metaInfo')
    @vite(['resources/css/app.css','resources/js/app.js'])


</head>

<body id="page-top" class="bg-light ">
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
    <nav class="navbar navbar-expand-lg navbar-light navbar-shrink fixed-top h-auto" id="mainNav">
        <div class="flex items-center justify-around sm:mx-6 md:mx-24 lg:mx-32 xl:mx-48 2xl:mx-60 w-full ">
            {{--            <a class="navbar-brand" href="\">{{$generals['brand_name']??''}}</a>--}}
            <a class="flex items-end justify-start max-h-12 mr-2" href="/" id="brand-link">
                @if($generals)
                    <div class="relative sm:min-w-16 sm:h-12 min-h-9 aspect-w-4 aspect-h-3 min-w-12 overflow-hidden">
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
            <div class="max-h-8 min-h-6">
                <button class="btn btn-outline-primary navbar-toggler px-1 sm:px-6 navbar-toggler-right" type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                    Menu
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <div class="offcanvas offcanvas-start w-50" tabindex="-1" id="offcanvasExample"
                 aria-labelledby="offcanvasExampleLabel">
                <div class="offcanvas-header">
                    <button type="button" class="btn-close ms-auto text-reset" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav  md:ms-auto">
                        <li class="nav-item"><a class="nav-link @yield('index')" href="/"><i
                                    class="fa-solid fa-house"></i><span class="mx-1">首頁</span></a></li>
                        <li class="nav-item"><a class="nav-link @yield('about')" href="/about">
                                <i class="fa-solid fa-mountain-sun"></i><span class="mx-1">關於我們</span></a></li>
                        <li class="nav-item"><a class="nav-link @yield('projects')" href="/projects">
                                <i class="fa-solid fa-person-hiking"></i><span class="mx-1">行程資訊</span></a></li>
                        <li class="nav-item"><a class="nav-link @yield('post')" href="/post">
                                <i class="fa-solid fa-newspaper"></i><span class="mx-1">文章區</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <nav class="navbar navbar-expand-lg navbar-light navbar-shrink h-auto" id="mainNav">
    </nav>

</header>
@yield('content')

<section class="bg-white ">
    <div class=" pt-16 lg:px-5">
        <div class="flex  gap-3 justify-content-center ">
            @if($foots)
                @foreach($foots['media'] as $foot )

                    @if($foot['data']['status'])

                        <a class="w-12 h-12 text-black " href="{{$foot['data']['url']??''}}"><img
                                src=" {{ Storage::url($foot['data']['image']) }} "
                                class="w-full h-full object-cover"></a>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
    <footer class=" bg-white text-center text-white-50   py-6 lg:px-5 ">
        <div class="container text-black">{{$foots['copyright']??''}}</div>
    </footer>
</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

@yield('blink')
</body>
</html>

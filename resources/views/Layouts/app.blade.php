<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<head>

    @if (config('services.GTM.enabled'))
        <!-- Google Tag Manager -->
        <script>(function (w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start':
                        new Date().getTime(), event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', '{{ config('services.GTM.id') }}');</script>
        <!-- End Google Tag Manager -->
    @endif
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    {!! seo($SEOData)??null !!}

    {{--        @vite('resources/js/app.js')--}}
    {{--    <link rel="icon" type="image/x-icon" href="{{ asset('storage/favicon.ico') }}"/>--}}
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;700&display=swap" rel="stylesheet">

        {{--    <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet"/>--}}
{{--    <link--}}
{{--        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"--}}
{{--        rel="stylesheet"/>--}}
{{--    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">--}}
    {{--統一字體--}}
{{--    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">--}}
    {{--        @viteNgrok( ['resources/css/app.css',--}}
    {{--        'resources/newLayout/js/scripts.js',--}}
    {{--        'resources/newLayout/css/styles.css',--}}
    {{--        'resources/newLayout/css/refer.css',--}}
    {{--        'resources/js/app.js',])--}}

    @yield('tlink')
    @vite(['resources/css/app.css','resources/js/app.js'])

</head>
<style>

</style>

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
                    <div class="relative sm:min-w-16 sm:h-12 min-h-9 aspect-w-4 aspect-h-3 min-w-12 ">
                        @foreach($generals['brand'] as $general)
                            @if($loop->iteration !== 3)
                                <img
                                    class="absolute left-0 w-auto h-full object-cover {{ $loop->first ? 'opacity-100 top-0 z-10' : 'z-0 bottom-0 lg:opacity-0 lg:transition-opacity' }}"
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
    <nav class="navbar navbar-expand-lg navbar-light navbar-shrink mb-1" id="mainNav">
    </nav>

</header>
@yield('navbar')
<!-- Masthead-->
@yield('content')

<section class="contact-section bg-white py-4 fs-3">
    <div class="container px-4 px-lg-5">
        <div class="social d-flex justify-content-center ">
            @if($foots)
                @foreach($foots['media'] as $foot )

                    @if($foot['data']['status'])

                        <a class="mx-2 text-black " href="{{$foot['data']['url']??''}}"><img
                                src=" {{ Storage::url($foot['data']['image']) }}"></a>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
    <footer class="footer bg-white small text-center text-white-50 ">
        <div class="container px-4 px-lg-5 text-black">{{$foots['copyright']??''}}</div>
    </footer>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('blink')

</body>
</html>

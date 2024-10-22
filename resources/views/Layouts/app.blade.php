<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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
    {!! seo($SEOData) !!}

    {{--        @vite('resources/js/app.js')--}}
    {{--    <link rel="icon" type="image/x-icon" href="{{ asset('storage/favicon.ico') }}"/>--}}
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet"/>
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet"/>

    <!-- Core theme CSS (includes Bootstrap)-->
    {{--    <link href="{{ asset('newLayout/css/styles.css') }}" rel="stylesheet"/>--}}
    <!--    自訂樣式-->
    {{--    <link href="{{ asset('newLayout/css/refer.css') }}" rel="stylesheet"/>--}}
    <!--    font-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
{{--        @viteNgrok( ['resources/css/app.css',--}}
{{--        'resources/newLayout/js/scripts.js',--}}
{{--        'resources/newLayout/css/styles.css',--}}
{{--        'resources/newLayout/css/refer.css',--}}
{{--        'resources/js/app.js',])--}}

        @yield('tlink')
                    @vite([
    'resources/css/app.css',
    'resources/js/app.js',
    'resources/newLayout/js/scripts.js',
    'resources/newLayout/css/styles.css',
    'resources/newLayout/css/refer.css',
 ])

</head>

</div>

<div class="overlay"></div>
<div class="yesman-container " id="yesmanContainer">
    <span class="letter">Y</span>
    <span class="letter">E</span>
    <span class="letter">S</span>
    <span class="letter">M</span>
    <span class="letter">A</span>
    <span class="letter">N</span>
</div>

<div class="progress-container mt-3" id="progressContainer">
    <div class="progress">
        <div class="progress-bar" id="progressBar"></div> <!-- 進度條內的文字 -->
    </div>
</div>

<body id="page-top" class="bg-light">

@if (config('services.GTM.enabled'))
    <!-- Google Tag Manager (noscript) -->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id={{ config('services.GTM.id') }}"
                height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->
@endif
<nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="\">{{$generals['brand_name']}}</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
            Menu
            <i class="fas fa-bars"></i>
        </button>
        <!--        <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse"-->
        <!--                data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false"-->
        <!--                aria-label="Toggle navigation">-->
        <!--            Menu-->
        <!--            <i class="fas fa-bars"></i>-->
        <!--        </button>-->
        <div class="offcanvas offcanvas-start w-25" tabindex="-1" id="offcanvasExample"
             aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header">
                <button type="button" class="btn-close ms-auto text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link @yield('index')" href="/">首頁</a></li>
                    <li class="nav-item"><a class="nav-link @yield('about')" href="/about">關於我們</a></li>
                    <li class="nav-item"><a class="nav-link @yield('projects')" href="/projects">行程資訊</a></li>
                    <li class="nav-item"><a class="nav-link @yield('post')" href="/post">文章區</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
@yield('navbar')
<!-- Masthead-->
@yield('content')
<section class="contact-section bg-white py-4 fs-3">
    <div class="container px-4 px-lg-5">
        <div class="social d-flex justify-content-center ">
            {{--            {{dd($foots)}}--}}
            @foreach($foots['media'] as $foot )

                @if($foot['data']['status'])

                    <a class="mx-2 text-black " href="{{$foot['data']['url']}}"><img
                            src=" {{ Storage::url($foot['data']['image']) }}"></a>
                @endif
            @endforeach
        </div>
    </div>
</section>
<!-- Footer-->
<footer class="footer bg-white small text-center text-white-50">
    <div class="container px-4 px-lg-5 text-black">{{$foots['copyright']}}</div>
</footer>
<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Core theme JS-->

{{--<script src="{{ asset('newLayout/js/scripts.js') }}"></script>--}}


@yield('blink')
</body>
</html>

@extends('layouts.app')
@section('meta')
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
{!! seo($SEOData)??null !!}

{{--        @vite('resources/js/app.js')--}}
{{--    <link rel="icon" type="image/x-icon" href="{{ asset('storage/favicon.ico') }}"/>--}}
<!-- Font Awesome icons (free version)-->
<script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
<!-- Google fonts-->
{{--        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;700&display=swap" rel="stylesheet">--}}
<link rel="stylesheet" href="https://rsms.me/inter/inter.css">
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

@endsection
@section('GTM')
    @if (config('services.GTM.enabled'))
        <!-- Google Tag Manager (noscript) -->
        <noscript>
            <iframe src="https://www.googletagmanager.com/ns.html?id={{ config('services.GTM.id') }}"
                    height="0" width="0" style="display:none;visibility:hidden"></iframe>
        </noscript>
        <!-- End Google Tag Manager (noscript) -->
    @endif
@endsection

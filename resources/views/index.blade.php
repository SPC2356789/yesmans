@extends('Layouts.app')
@section($Slug,'active')
{{--{{dd($Carousels)}}--}}
@section('tlink')

@endsection

@section('blink')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    {{--    @viteNgrok([--}}
    {{--    'resources/js/home.js'])--}}
    @vite([
      'resources/js/home.js'])
@endsection
@section('content')
    <header class="">
        <!--    <div class="container px-4 px-lg-5 d-flex h-100 align-items-center justify-content-center">-->
        <!--        <div class="d-flex justify-content-center">-->
        <!--            <div class="text-center">-->
        <!--                <h1 class="mx-auto my-0 text-uppercase">Grayscale</h1>-->
        <!--                <h2 class="text-white-50 mx-auto mt-2 mb-5">A free, responsive, one page Bootstrap theme created by-->
        <!--                    Start Bootstrap.</h2>-->
        <!--                <a class="btn btn-primary" href="#about">Get Started</a>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->

        <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($Carousels as $Carousel)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}" data-bs-interval="10000">
                        <img src="{{ Storage::url($Carousel['image_path']) }}" class="d-block w-100"
                             alt="{{$Carousel['alt']}}">
                    </div>
                @endforeach

            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval"
                    data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval"
                    data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </header>

    {{--    <div id='calendar' class="pb-5 pt-5 mx-md-5 w-50">--}}
{{--    <div class="">--}}
    <div class="row pb-5 pt-5 mx-5">
        <div id='calendar' class="w-50">
        </div>
        <div class="w-50">
                    <img  src="https://via.placeholder.com/1024x1024" style="max-width: 100%; height: auto;">
        </div>
    </div>

    <!--post-->
    <section>
        <div class="container">
            <div class="row">
                <div class="post-area-index font-weight-bold ">

                    <div class="img_box">
                        <img src="https://via.placeholder.com/1024x1024">
                        <div class="post-title">登山實用</div>
                    </div>
                    <div class="img_box">
                        <img src="https://via.placeholder.com/1024x1024">
                        <div class="post-title">帶團契機</div>
                    </div>
                    <div class="img_box">
                        <img src="https://via.placeholder.com/1024x1024">
                        <div class="post-title">山上救護分享</div>

                    </div>
                    <div class="img_box">
                        <img src="https://via.placeholder.com/1024x1024">
                        <div class="post-title">惡劣天氣判斷</div>
                    </div>
                </div>
                <div class="mt-5"><a class="text-decoration-none read_more" href="#">看更多文章....</a></div>
            </div>
        </div>
    </section>

@endsection

@extends('Layouts.app')
@section($Slug,'active')
{{--{{dd($Carousels)}}--}}
@section('tLink')
    {{--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>--}}

@endsection

@section('blink')
    {{--    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>--}}
@endsection
@section('head_content')
    <section class="">
        <div class="swiper carousel">
            <div class="swiper-wrapper relative">
                <div class="bg-white z-10 absolute w-full h-full opacity-0"></div>
                @foreach($Carousels as $Carousel)
                    <img class="swiper-slide"
                         src="{{ Storage::url($Carousel['image_path']) }}"
                         alt="{{$Carousel['alt']??''}}">
                @endforeach
            </div>
            <div
                class="swiper-button-prev carousel_arrow "></div>

            <div
                class="swiper-button-next carousel_arrow "></div>
        </div>
    </section>
@endsection

@section('content')

    <div class="flex flex-col justify-center w-full">
            <div class="lg:h-full " id="tab">

            </div>
        {{--                                                    <button type="button"--}}
        {{--                                                            class="btn btn-outline-warning btn-cus ms-auto">--}}
        {{--                                                        再3人成團--}}
        {{--                                                    </button>--}}
        {{--        </div>--}}
        @include('Blog.blog_hot', ['BlogItems' => $BlogItems])
    </div>


@endsection

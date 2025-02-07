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

    {{--    <section class="py-5 xxx:px-4 xxs:px-6 ss:px-12 sm:px-10 md:px-12 lg:px-2 min-w-[302px]">--}}
    {{--        <div class="md:mx-16 sm:mx-10 lg:mx-4 --}}{{--xl:mx-40 -2xl:mx-60 --}}{{--">--}}
    <div
        class="flex flex-col justify-center  ">
        <div id="targetElement" class="lg:h-full">
            <section class="xxx:w-full xl:w-[550px] lg992:w-[420px] md965:w-[370px] ">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="fw-bold fs-3">行程訊息</h3>
                    <a href="/itinerary"
                       class="w-[18.8%] text-center ms-auto xxx:text-xs  ss:text-base sm:text-lg md965:text-base xs:px-0.5 py-2 border border-gray-800 text-gray-800 rounded hover:bg-[#DA8A51] bg-neutral-50  hover:text-white focus:outline-none">
                        <span class="w-11/12 "></span> 看更多
                        <i class="fas fa-chevron-right w-1/12"> </i>
                    </a>
                </div>

                <nav
                    class="flex flex-wrap  justify-between mt-2 w-full  items-end relative overflow-visible group"
                    role="tablist" id="itinerary_tab">


                    @foreach($tripTab as $tabK => $tabV)
                        <button
                            class="text-black tabutton h-full  ss:py-1 xxx:w-[19.5%] xxs:w-[18.8%] {{--us-420:w-[18%]  lg:w-[18%]--}}  bg-[#b8cac9] rounded-t-lg  {{ $tabK == 'recent' ? 'active' : '' }} group element  hover:shadow-md transition-all transform duration-300 ease-out relative"
                            data-bs-toggle="tab"
                            data-bs-target="#{{$tabK}}"
                            role="tab"
                            aria-controls="{{$tabK}}"
                            aria-selected="{{ $tabK == 'recent' ? 'true' : 'false' }}">

                            <!-- Tab content -->
                            <span
                                class=" {{--px-2--}}  xxx:text-xs  ss:text-sm ">
                {{$tabV}}
            </span>

                        </button>
                    @endforeach
                </nav>
            </section>

                <div class="tab-content" id="itinerary_tab_content">
                    @foreach($tripData as $tabK => $data)
                        <div class="flex flex-row justify-between   tab-pane fade {{ $tabK == 'recent' ? 'show active' : '' }} " id="{{$tabK}}"
                             role="tabpanel" aria-labelledby="{{$tabK}}">
                            <div class="card border-0 xxx:w-full xl:w-[550px] lg992:w-[420px] md965:w-[370px]">
                                <ul class="list-group list-group-flush fs-6 ">
                                    @foreach($data as $dataUUid => $tripTime)
                                        <li class="xxx:pt-2 xs:pt-3 pl-2  sm:pl-3  xxx:py-2  lg:py-3 hover:scale-105 hover:z-50 hover:shadow-[0_-8px_12px_-2px_rgba(0,0,0,0.15),0_8px_12px_-2px_rgba(0,0,0,0.15)] hover:rounded-xl transition-all transform itinerary_tab bg-neutral-50 border-b border-b-[#a8b6ad]"
                                            data-tab="{{$dataUUid}}">
                                            <div class="flex items-center ">
                                                <img class="mr-1 xxx:w-4 ss:w-5 sm:w-5 "
                                                     src="{{Storage::url($Media[$tripTime['trip']['icon']]) }}"
                                                     loading="lazy" data-lock="{{$dataUUid}}" alt="icon1"/>
                                                <span
                                                    class="block fw-bold  text-lg ss:text-xl sm:text-xl  text-left ">{{$tripTime['trip']['title']}}-{{$tripTime['trip']['subtitle']}}</span>

                                            </div>
                                            <div
                                                class="flex flex-row w-full items-end justify-between gap-1 ">
                                                <div
                                                    class="flex  flex-col  gap-1 w-4/5 ">

                                                <span
                                                    class="text-[#da8a51] text-xs ss:text-sm {{-- xxx:w-[115px] ss:w-[140px]--}}">{{$tripTime['date']}}</span>
                                                    <div
                                                        class="flex flex-wrap xxx:gap-1 ss:gap-1.5 items-center">
                                                        @foreach($tripTime['trip']['tags'] as  $tripTag)
                                                            <span class="span_tag">{{$Tags[$tripTag]['name']}}</span>
                                                        @endforeach
                                                    </div>

                                                </div>
                                                <div class="w-1/5 flex flex-col align-end h-full">
                                                    <a href="/itinerary/{{$tabK}}/trip/{{$tripTime['trip']['slug']}}?trip_time={{$dataUUid}}"
                                                            class="w-11/12 xxx:px-1 xxx:py-2 sm:p-2 md965:p-1 text-center  rounded-full bg-[#55958d] text-[#f7dbab] hover:text-[#467771] hover:bg-[#f8b551] xxx:text-xs xxs:text-sm xs:text-base sm:text-lg md965:text-base lg:text-base ">
                                                        報名中

                                                    </a>
                                                </div>
                                            </div>
                                            <div
                                                class="md965:hidden xxx:mr-2 sm:mr-3 flex justify-center  items-center ">
                                                <i class="fa-solid fa-sort-down text-[#5B9894FF]"> </i>

                                            </div>
                                        </li>
                                        <img class="itinerary_img md965:hidden hidden" src="{{Storage::url($Media[$tripTime['trip']['carousel'][0]]) }}"
                                             loading="lazy" data-lock="{{$dataUUid}}"/>

                                    @endforeach
                                </ul>
                            </div>
                            <div class=" md965:w-[435px] lg:min-w-[450px] lg:max-w-[450px] hidden md965:block ">

                                <div class="swiper Itinerary relative">
                                    <div class="swiper-wrapper tab-lock">

                                        <div class="bg-white z-10 absolute w-full h-full opacity-0"></div>
                                        @foreach($data as $dataUUid => $tripTime)
                                        <img class="swiper-slide" src="{{Storage::url($Media[$tripTime['trip']['carousel'][0]]) }}" loading="lazy"
                                             data-lock="{{$dataUUid}}"/>
                                        @endforeach
                                    </div>
                                    <div
                                        class="swiper-button-next Itinerary "></div>
                                    <div
                                        class="swiper-button-prev Itinerary "></div>
                                    <div class="swiper-pagination Itinerary z-21 s-cus bg-black w-full bg-opacity-10  "></div>


                                </div>

                            </div>
                        </div>

                    @endforeach
                </div>


            {{--                                                    <button type="button"--}}
            {{--                                                            class="btn btn-outline-warning btn-cus ms-auto">--}}
            {{--                                                        再3人成團--}}
            {{--                                                    </button>--}}
        </div>

    </div>
    @include('Blog.blog_hot', ['BlogItems' => $BlogItems])
@endsection

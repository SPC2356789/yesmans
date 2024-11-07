@section('header')
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
                                        class="absolute left-0 w-auto h-full object-cover {{ $loop->first ? 'opacity-100 top-0 z-10' : 'z-0 bottom-0 opacity-0 transition-opacity' }}"
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

                    <button class="btn btn-outline-primary navbar-toggler px-1 sm:px-6 navbar-toggler-right"
                            type="button"
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
@endsection
@section('foot')
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


@endsection

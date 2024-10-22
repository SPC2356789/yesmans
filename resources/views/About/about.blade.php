@extends('Layouts.app')
@section($Slug,'active')
{{--{{dd($Carousels)}}--}}

@section('tlink')
{{--    @vite(['resources/css/about.css',])--}}

@endsection

@section('blink')
{{--    @vite(['resources/js/about.js',])--}}
    {{--    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-d4f8zC+Kd8kL9VRZgR8sW7o5J5Q14Dg5ZL5OV5g1QkE5Zy4VZbsV7fboH2j7VZc4" crossorigin="anonymous"></script>--}}
@endsection
@section('content')
{{--    <section>--}}
{{--        <div>--}}
{{--            <img src="/1.jpg" />--}}
{{--            <h2>#001</h2>--}}
{{--        </div>--}}
{{--    </section>--}}
{{--    <section>--}}
{{--        <div>--}}
{{--            <img src="/2.jpg" />--}}
{{--            <h2>#002</h2>--}}
{{--        </div>--}}
{{--    </section>--}}
{{--    <section>--}}
{{--        <div>--}}
{{--            <img src="/3.jpg" />--}}
{{--            <h2>#003</h2>--}}
{{--        </div>--}}
{{--    </section>--}}
{{--    <section>--}}
{{--        <div>--}}
{{--            <img src="/4.jpg" />--}}
{{--            <h2>#004</h2>--}}
{{--        </div>--}}
{{--    </section>--}}
{{--    <section>--}}
{{--        <div>--}}
{{--            <img src="/5.jpg" />--}}
{{--            <h2>#005</h2>--}}
{{--        </div>--}}
{{--    </section>--}}
{{--    <div class="progress"></div>--}}

    <!-- Projects-->
    <section class="projects-section" id="projects">
        <div class="container px-4 px-lg-5">
            <!-- Featured Project Row-->
            <div class="row gx-0 mb-4 mb-lg-5 align-items-center">
                <div class="col-xl-8 col-lg-7">
                    <img class="img-fluid mb-3 mb-lg-0" src="{{ Storage::url($story['image']) }}" alt="..."/>
                </div>
                <div class="col-xl-4 col-lg-5">
                    <div class="featured-text text-center text-lg-left">
                        <h4>{{$story['title']}}</h4>
                        <p class="text-black-50 mb-0">{!! $story['introduce'] !!}</p>
                    </div>
                </div>
            </div>

            <!-- Project One Row-->
            <div class="row gx-0 mb-5 mb-lg-0 justify-content-center project-row">
                <div class="col-lg-6 position-relative">
                    <img class="img-fluid" src="assets/img/demo-image-01.jpg" alt="..."/>

                </div>
                <div class="col-lg-6">
                    <div class="bg-black text-center h-100 project">
                        <div class="d-flex h-100">
                            <div class="project-text w-100 my-auto text-center text-lg-left">
                                <h4 class="text-white">Misty</h4>
                                <p class="mb-0 text-white-50">An example of where you can put an image of a project, or
                                    anything else, along with a description.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Project Two Row-->
            <div class="row gx-0 justify-content-center project-row">
                <div class="col-lg-6 position-relative">
                    <img class="img-fluid" src="assets/img/demo-image-02.jpg" alt="..."/>
                </div>
                <div class="col-lg-6 order-lg-first">
                    <div class="bg-black text-center h-100 project">
                        <div class="d-flex h-100">
                            <div class="project-text w-100 my-auto text-center text-lg-right">
                                <h4 class="text-white">Mountains</h4>
                                <p class="mb-0 text-white-50">Another example of a project with its respective
                                    description. These sections work well responsively as well!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container my-5 bg-light">
        <!-- 品牌故事 -->
        <div class="bg-white p-4 rounded shadow-sm mb-5">
            <h1>{{$story['title']}}</h1>
            <div class="mb-3">
                <img src="{{ Storage::url($story['image']) }}" class="img-fluid rounded" title="{{$story['imgAlt']}}"
                     alt="{{$story['imgAlt']}}">
            </div>
            {!! $story['introduce'] !!}
        </div>

        <!-- 成員介紹 -->
        <div class="text-center">
            <h2 class="mb-4">成員介紹</h2>
            <div class="row align-content-center flex-column">
                <div class="col-6 mb-4">
                        <img src="https://fakeimg.pl/300/" class="rounded-circle me-3 mb-1" alt="成員頭像">
                    <div class="d-flex justify-content-center align-content-center mt-1">
                        <div>
                            <h4 class="mb-1 text-start ">李明</h4>
                            <p class="mb-0">創辦人兼首席執行官。擁有十年的行業經驗，致力於推動品牌的創新和發展。創辦人兼首席執行官。擁有十年的行業經驗，致力於推動品牌的創新和發展。
                                創辦人兼首席執行官。擁有十年的行業經驗，致力於推動品牌的創新和發展。
                                創辦人兼首席執行官。擁有十年的行業經驗，致力於推動品牌的創新和發展。</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 mb-4">
                    <img src="https://fakeimg.pl/300/" class="rounded-circle me-3" alt="成員頭像">
                    <div class="d-flex justify-content-center align-content-center ">
                        <div>
                            <h4 class="mb-1 text-start">李明</h4>
                            <p class="mb-0">創辦人兼首席執行官。擁有十年的行業經驗，致力於推動品牌的創新和發展。創辦人兼首席執行官。擁有十年的行業經驗，致力於推動品牌的創新和發展。
                                創辦人兼首席執行官。擁有十年的行業經驗，致力於推動品牌的創新和發展。
                                創辦人兼首席執行官。擁有十年的行業經驗，致力於推動品牌的創新和發展。</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 mb-4">
                    <img src="https://fakeimg.pl/300/" class="rounded-circle me-3" alt="成員頭像">
                    <div class="d-flex justify-content-center align-content-center ">
                        <div>
                            <h4 class="mb-1 text-start">李明</h4>
                            <p class="mb-0">創辦人兼首席執行官。擁有十年的行業經驗，致力於推動品牌的創新和發展。創辦人兼首席執行官。擁有十年的行業經驗，致力於推動品牌的創新和發展。
                                創辦人兼首席執行官。擁有十年的行業經驗，致力於推動品牌的創新和發展。
                                創辦人兼首席執行官。擁有十年的行業經驗，致力於推動品牌的創新和發展。</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    </div>
@endsection

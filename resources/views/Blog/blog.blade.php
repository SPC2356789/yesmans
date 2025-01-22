@extends('Layouts.app')
@section($Slug, 'active')


@section('tLink')
    {{-- @vite(['resources/css/about.css']) --}}

@endsection

@section('blink')
    {{-- @vite(['resources/js/about.js']) --}}
@endsection

@section('content')
    {{--    <section class="flex flex-col  my-4 w-full justify-start items-center" id="">--}}
    <div
        class=" flex md:flex-row flex-col gap-8  my-4 ">
        <!-- 左側篩選區 -->
        <div class="flex flex-col md:w-1/5 w-full justify-start space-y-6 hidden md:block" name="navbar-l">
            <!-- 導航藍 -->
            <div name="navbar-l_theme">
                <h1 class="text-xl font-bold text-gray-800 mb-2">文章分類</h1>
                @foreach($Categories as $Ck=>$Cv)
                    <a href="/blog/{{$Ck}}" class="{{($urlSlug===$Ck ? 'active' :'')}}">{{$Cv}}</a>
                @endforeach
            </div>
        </div>
        <!-- 右側內容區 -->
        <div class="md:w-4/5 w-full flex-col items-center justify-between " name="info_data">
            <div class="flex flex-row justify-between items-center" name="data_h">
                <div class=" md:hidden">
                    @include('Layouts.select', ['Categories' => $Categories,'urlSlug'=>$urlSlug])
                </div>
                <nav aria-label="Breadcrumb" class="hidden md:block">
                    <ol class="flex space-x-2 text-sm items-center">
                        <li aria-current="page" class="text-2xl">
                            <div>{{$Categories[$urlSlug]}}</div>
                        </li>
                    </ol>
                </nav>
                <div class="flex items-ceenter  order-1  xsm:ms-auto">
                    <div
                        class="flex items-center rounded-md bg-white  outline outline-1 -outline-offset-1 outline-gray-300 has-[input:focus-within]:outline has-[input:focus-within]:outline-2 has-[input:focus-within]:-outline-offset-2 has-[input:focus-within]:outline-neutral-800 max-w-sm mx-auto max-h-12 px-3">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" name="search" id="blogSearch" data-key="{{$urlSlug}}"
                               class="block max-w-[100px] grow py-1.5 pl-1 pr-0.5 text-sm text-gray-900 placeholder:text-gray-400 focus:outline focus:outline-0 sm:text-sm/6"
                               autocomplete="off" value="{{$term}}" placeholder="文章搜尋">
                    </div>
                </div>
            </div>
            <!-- 活動卡片區域 -->
            <div class="scale-x-105" id="search-results">
                @include('Blog.blog_items', ['items' => $items])
            </div>
        </div>

    </div>
    {{--    </section>--}}

    @include('Blog.blog_hot', ['BlogItems' => $BlogItems])
@endsection

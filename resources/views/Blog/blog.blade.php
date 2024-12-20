@extends('Layouts.app')
@section($Slug=null, 'active')


@section('tlink')
    {{-- @vite(['resources/css/about.css']) --}}
@endsection

@section('blink')
    {{-- @vite(['resources/js/about.js']) --}}
@endsection

@section('content')
    <section class="flex flex-col items-center justify-center " id="">
        <div
            class=" flex md:flex-row flex-col gap-8  xl:w-[1040px] lg:w-[900px] xxs:mx-6 md:mx-16 sm:mx-20 lg:mx-4 xs:mx-6 ss:mx-12 xxx:mx-4 sm:pt-12 md:pt-16 ">

            <!-- 左側篩選區 -->
            <div class="flex flex-col md:w-1/4 w-full justify-start space-y-6" name="navbar-l">
                <!-- 導航藍 -->
                <div class="" name="navbar-l_theme">
                    <h1 class="text-xl font-bold text-gray-800 mb-2">文章分類</h1>
                    <a href="all" class="{{($urlSlug==='all' ? 'active' :'')}}">所有文章</a>
                    @foreach($Categories as $Ck=>$Cv)
{{--{{dd($urlSlug)}}--}}
                        <a href="/blog/{{$Ck}}" class="{{($urlSlug===$Ck ? 'active' :'')}}">{{$Cv}}</a>

                    @endforeach
                    {{--                    <a href="all" class="w-full">如何選擇登山路線</a>--}}
                    {{--                    <a href="#" class="">登山基礎知識</a>--}}
                    {{--                    <a href="#" class="">如何選擇登山路線</a>--}}
                </div>
            </div>

            <!-- 右側內容區 -->
            <div class="md:w-3/4 w-full " name="info_data">
                <div class="flex flex-row justify-between items-center mb-6" name="data_h">
                    <nav aria-label="Breadcrumb">
                        <ol class="flex space-x-2 text-sm items-center">
                            {{--                            <li>--}}
                            {{--                                <a href="/" class="hover:text-[#64A19D]">首頁</a>--}}
                            {{--                            </li>--}}
                            {{--                            <li>--}}
                            {{--                                <span class="">></span>--}}
                            {{--                            </li>--}}
                            {{--                            <li aria-current="page" class="">--}}
                            {{--                                <a href="/" class="hover:text-[#64A19D]">行程資訊</a>--}}
                            {{--                            </li>--}}
                            {{--                            <li>--}}
                            {{--                                <span class="">></span>--}}
                            {{--                            </li>--}}
                            <li aria-current="page" class="text-2xl"><a href="/"
                                                                        class="hover:text-[#64A19D]">近期活動</a></li>
                        </ol>
                    </nav>

                    <div class="relative inline-block text-left" name="select">

                        <button type="button" data-select="none"
                                class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                                id="menu-button" aria-expanded="true" aria-haspopup="true">
                            <span>選擇排序</span>
                            <svg class="-mr-1 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                                 aria-hidden="true" data-slot="icon">
                                <path fill-rule="evenodd"
                                      d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div
                            class="absolute right-0 z-10 mt-2 w-40  origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none hidden"
                            role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                            <button href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 text-start w-full border-0 outline-none rounded-md "
                                    role="menuitem" tabindex="-1"
                                    data-value="Edit">日期由遠到近
                            </button>
                            <button href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 text-start w-full border-0 outline-none rounded-md"
                                    role="menuitem" tabindex="-1"
                                    data-value="Duplicate">日期由近到遠
                            </button>
                            <button href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 text-start w-full border-0 outline-none rounded-md"
                                    role="menuitem" tabindex="-1"
                                    data-value="Duplicate">難度由低至高
                            </button>
                            <button href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 text-start w-full border-0 outline-none rounded-md"
                                    role="menuitem" tabindex="-1"
                                    data-value="Duplicate">天數由少至多
                            </button>
                            {{--                            </div>--}}
                            {{--                            <div class="py-1" role="none">--}}
                            {{--                                <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-2">Archive</a>--}}
                            {{--                                <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-3">Move</a>--}}
                            {{--                            </div>--}}
                            {{--                            <div class="py-1" role="none">--}}
                            {{--                                <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-4">Share</a>--}}
                            {{--                                <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-5">Add to favorites</a>--}}
                            {{--                            </div>--}}
                            {{--                            <div class="py-1" role="none">--}}
                            {{--                                <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-6">Delete</a>--}}
                            {{--                            </div>--}}
                        </div>
                    </div>


                </div>

                <!-- 活動卡片區域 -->
                <div class="flex flex-wrap">
                    @if($items && count($items) > 0)
                        @foreach($items as $item)
                            <a href="#"
                               class="w-1/2  lg:w-1/3 2xl:w-1/5 p-2 transform transition-transform duration-300 hover:scale-105 hover:z-50">
                                <div class="bg-white bg-opacity-90 shadow-lg rounded-md overflow-hidden">
                                    <img src="https://via.placeholder.com/1024x1024" class="w-full h-auto rounded-t-md">
                                    <div class="p-4 text-center font-bold text-gray-800 text-lg">
                                                                            {{$item['title']}}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <p>No items available</p>
                    @endif

                </div>
            </div>

        </div>
    </section>

@endsection

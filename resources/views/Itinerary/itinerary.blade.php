@extends('Layouts.app')
@section($Slug=null, 'active')

@section('tLink')
    @vite(['resources/js/itinerary.js'])
@endsection

@section('blink')

@endsection

@section('content')


        <div
            class=" flex md:flex-row flex-col gap-8   justify-center">
            @include('Itinerary.itinerary_sidebar', ['xx' => 'xx'])
            <!-- 右側內容區 -->
            <div class="md:w-4/5 w-full flex-col items-center justify-between" name="info_data">
                <div class="flex flex-row justify-between items-center px-3" name="data_h">
                    {{--                    分類選擇--}}
                    <div class="relative inline-block text-left md:hidden" name="select">
                        <button type="button" data-select="All"
                                class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                                id="menu-button" aria-expanded="true" aria-haspopup="true">
                            {{--                            <span>{{'$Categories[$urlSlug]'}}</span>--}}
                            <span>行程分類</span>
                            <svg class="-mr-1 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                                 aria-hidden="true" data-slot="icon">
                                <path fill-rule="evenodd"
                                      d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div
                            class="absolute left-0 z-10 mt-2 w-40  origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none hidden"
                            role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                            {{--                            @foreach($Categories as $Ck=>$Cv)--}}
                            <a href="/blog/{{'$Ck'}}"
                               {{--                                   class="block px-4 py-2 text-sm text-gray-700 text-start w-full border-0 outline-none rounded-md {{($urlSlug === $Ck ? 'active' :'')}}"--}}
                               class="block px-4 py-2 text-sm text-gray-700 text-start w-full border-0 outline-none rounded-md"
                               role="menuitem" tabindex="-1" data-value="{{'$Ck'}}">{{'$Cv'}}
                            </a>
                            {{--                            @endforeach--}}
                        </div>
                    </div>
                    {{--                    標頭(大螢幕)--}}
                    <nav aria-label="Breadcrumb" class="hidden md:block">
                        <ol class="flex space-x-2 text-sm items-center">
                            <li aria-current="page" class="text-2xl">
                                <div>{{'$Categories[$urlSlug]'}}</div>
                            </li>
                        </ol>
                    </nav>
                    {{--                    搜尋--}}
                    <div class="flex items-ceenter  order-1  xsm:ms-auto">
                        <div
                            class="flex items-center rounded-md bg-white  outline outline-1 -outline-offset-1 outline-gray-300 has-[input:focus-within]:outline has-[input:focus-within]:outline-2 has-[input:focus-within]:-outline-offset-2 has-[input:focus-within]:outline-neutral-800 max-w-sm mx-auto max-h-12 px-3">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" name="search" id="blogSearch" data-key="{{'$urlSlug'}}"
                                   class="block max-w-[100px] grow py-1.5 pl-1 pr-0.5 text-sm text-gray-900 placeholder:text-gray-400 focus:outline focus:outline-0 sm:text-sm/6"
                                   autocomplete="off" value="{{'$term'}}" placeholder="探索行程">
                        </div>
                    </div>
                </div>
                <!-- 活動卡片區域 -->

                @include('Itinerary.itinerary_item', ['items' => '112'])

            </div>

        </div>


@endsection

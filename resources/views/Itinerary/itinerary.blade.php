@extends('Layouts.app')
@section($Slug, 'active')

@section('tLink')
    {{--    @vite(['resources/js/itinerary.js'])--}}
@endsection

@section('blink')

@endsection

@section('content')

    <div
            class=" flex md:flex-row flex-col gap-8   justify-center">
        @include('Layouts.sidebar')
        <!-- 右側內容區 -->
        <div class="md:w-4/5 w-full flex-col items-center justify-between" name="info_data">
            <div class="flex flex-row justify-between items-center" name="data_h">
                <div class=" md:hidden">
                    @include('Layouts.select',['select'=>$Categories,'default'=>$urlSlug])
                </div>
                <nav aria-label="Breadcrumb" class="hidden md:block">
                    <ol class="flex space-x-2 text-sm items-center">
                        <li aria-current="page" class="text-2xl">
                            <div>{{$Categories[$urlSlug]}} {{$urlSlug=="recent"?'(未來一個月)':''}}</div>
                        </li>
                    </ol>
                </nav>
                <div class="flex items-ceenter  order-1  xsm:ms-auto">
                    <div
                            class="flex items-center rounded-md bg-white  outline outline-1 -outline-offset-1 outline-gray-300 has-[input:focus-within]:outline has-[input:focus-within]:outline-2 has-[input:focus-within]:-outline-offset-2 has-[input:focus-within]:outline-neutral-800 max-w-sm mx-auto max-h-12 px-3">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" name="search" id="Search" data-key="{{$urlSlug}}"
                               class="block max-w-[100px] grow py-1.5 pl-1 pr-0.5 text-sm text-gray-900 placeholder:text-gray-400 focus:outline focus:outline-0 sm:text-sm/6"
                               autocomplete="off" value="{{$term}}" placeholder="行程探索">
                    </div>
                </div>

            </div>
            <!-- 活動卡片區域 -->
            <div class=" " id="search-results">
                @include('Layouts.item_card',['div'=>'button'])
                </div>
            </div>

        </div>


@endsection

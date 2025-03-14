@extends('Layouts.app')
@section($Slug, 'active')

@section('tLink')
    {{-- @vite(['resources/css/about.css']) --}}
@endsection

@section('blink')

@endsection

@section('content')

    <!-- Projects -->
    {{--    <section class="flex justify-center" id="projects">--}}
    {{--        <div class="xl:w-[1040px] lg:w-[900px] xxs:mx-6 md:mx-28 sm:mx-20 lg:mx-4 xs:mx-6 ss:mx-12 xxx:mx-4 xxx:pt-6 sm:pt-12 md:pt-16 ">--}}
    <!-- Featured Project Row -->
    <div class="flex flex-col lg:flex-row mb-4 mb-lg-5 items-center gap-3 ">
        <div class="w-full lg:w-2/3 relative">
            <div class="bg-white z-10 absolute w-full h-full opacity-0"></div>
            <img class="w-full h-auto " src="{{ Storage::url($stories['image']) }}" alt="..."/>
        </div>
        <div class="w-full lg:w-1/3">
            <div class="text-center md:text-left py-12 ">
                <h4 class="text-xl font-bold">{{ $stories['title'] }}</h4>
                <p class="text-gray-600 mb-0">{!! $stories['introduce'] !!}</p>
            </div>
        </div>
    </div>
    @if($stories)
        <div>
            @foreach($stories['story'] as $storyK=> $story)
                @if($story['data']['status'])
                    <div
                        class="flex flex-col {{ $storyK % 2 === 0 ? 'md:flex-row  ' : 'md:flex-row-reverse fade-in animation' }} justify-center items-center "
                        style="background-color: {{$story['data']['C_bg'] ?? '#000000'}}"
                    >
                        <div class="w-full md:w-1/2  mx-auto relative">
                            <div class="bg-white z-10 absolute w-full h-full opacity-0"></div>
                            <img class="w-full h-auto" src="{{ Storage::url($story['data']['image']) }}" alt="..."/>
                        </div>
                        <div
                            class="xxx:h-[200px] xxx:px-5 xxs:h-[240px] md:h-full   xxs:px-10 w-full md:w-1/2 flex flex-col justify-center  items-center text-center ">
                            <h4 class=""
                                style="color: {{$story['data']['C_title'] ?? '#ffffff'}};">{{$story['data']['title'] ?? ''}}</h4>
                            <p class="mb-0 opacity-50"
                               style="color: {{$story['data']['C_content'] ?? '#ffffff'}} "> {{$story['data']['content'] ?? ''}}</p>
                        </div>

                    </div>
                @endif
            @endforeach

        </div>
    @endif
    <div class="mt-16">
        <h2 class="text-center mb-6 text-2xl font-bold">嚮導-領隊介紹</h2>
        <div class="space-y-1 flex flex-col flex-wrap content-center">
            @if($members)
                @foreach($members as $member)
                    @if($member['status'])
                        <div class="flex flex-col md:flex-row items-center">
                            <img src="{{storage::url($member['image_path'])}}"
                                 class="rounded-full mb-4 md:mb-0 md:mr-4 w-32 h-32 object-cover"
                                 alt="YESMAN:{{$member['name']}}嚮導-領隊">
                            <div class="text-center md:text-left">
                                <h4 class="text-lg font-semibold md:text-left">{{$member['name']}}</h4>
                                <p class="text-gray-500">{!! $member['introduce']!!}</p>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>

@endsection

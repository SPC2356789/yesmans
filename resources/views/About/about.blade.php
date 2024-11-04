@extends('Layouts.app')
@section($Slug, 'active')

@section('tlink')
    {{-- @vite(['resources/css/about.css']) --}}
@endsection

@section('blink')
    {{-- @vite(['resources/js/about.js']) --}}
@endsection

@section('content')

    <!-- Projects -->
    <section class="pt-6 sm:pt-12 md:pt-24 flex justify-center" id="projects">
        <div class="px-2 sm:mx-10 md:mx-16   lg:max-w-[1426px] ">
            <!-- Featured Project Row -->
            <div class="flex flex-col lg:flex-row mb-4 mb-lg-5 items-center">
                <div class="w-full lg:w-2/3">
                    <img class="w-full h-auto mb-3 md:mb-0" src="{{ Storage::url($stories['image']) }}" alt="..."/>
                </div>
                <div class="w-full lg:w-1/3">
                    <div class="text-center md:text-left sm:p-4 p-2">
                        <h4 class="text-xl font-bold">{{ $stories['title'] }}</h4>
                        <p class="text-gray-600 mb-0">{!! $stories['introduce'] !!}</p>
                    </div>
                </div>
            </div>
            @if($stories)
                @foreach($stories['story'] as $storyK=> $story)
                    @if($story['data']['status'])
                        <div
                            class="flex flex-col {{ $storyK % 2 === 0 ? 'md:flex-row' : 'md:flex-row-reverse' }} justify-center mb-0 px-8">
                            <div class="w-full md:w-1/2 relative mx-auto">
                                <img class="w-full h-auto" src="{{ Storage::url($story['data']['image']) }}" alt="..."/>
                            </div>
                            <div class="w-full md:w-1/2">
                                <div class="bg-black text-center h-full">
                                    <div class="flex h-full p-24 sm:p-24 md:px-12 md:py-8 xl:px-24 2xl:px-32 lg:px-12 lg:pt-12">
                                        <div class="w-full my-auto text-center md:text-left">
                                            <h4 class="text-white">{{$story['data']['title'] ?? ''}}</h4>
                                            <p class="mb-0 text-gray-500 opacity-50">{{$story['data']['content'] ?? ''}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

            @endif

        </div>
    </section>
    <section class="flex justify-center">

        <div class="lg:mx-52 md:mx-32 mx-12 my-2  lg:max-w-[1378px] ">
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
    </section>

@endsection

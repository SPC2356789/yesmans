@extends('Layouts.app')
@section($Slug, 'active')


@section('tlink')
    {{-- @vite(['resources/css/about.css']) --}}

@endsection

@section('blink')
    {{-- @vite(['resources/js/about.js']) --}}
@endsection

@section('content')
    {{--        {{dd($items)}}--}}
    <section class="flex flex-col justify-center items-center ">
        <div
            class="xl:w-[1040px] lg:w-[900px] xxs:px-6 md:px-16 sm:px-20 lg:px-0 xs:px-6 ss:px-12 xxx:px-4 xxx:px-6 sm:pt-12 md:pt-16 ">

            <div class="flex flex-row justify-between">

                <div class="flex flex-row my-2">
                    <ol class="flex space-x-2 text-sm items-center">

                        <li>
                            <a href="/" class="hover:text-[#64A19D]">首頁</a>
                        </li>
                        <li>
                            <span class="">></span>
                        </li>
                        <li aria-current="page" class="">
                            <a href="/blog/{{$Categories['slug']}}"
                               class="hover:text-[#64A19D]">{{$Categories['name']}}</a>
                        </li>
                        <li>
                            <span class="">></span>
                        </li>
                        <li aria-current="page" class="">
                            <div class="text-2xl">{{$items['title']}}</div>
                        </li>
                    </ol>
                </div>
            </div>

            <div>
                <img src="{{Storage::url($items['featured_image'])}}">
                <div class="my-2 text-stone-600">發佈時間:{{$items['published_at']}}</div>
            </div>

            {{--        {{dd($items)}}--}}
            {!! $items['content'] !!}
        </div>
    </section>
    @include('Blog.blog_hot', ['BlogItems' => $BlogItems])
@endsection

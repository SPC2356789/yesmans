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
                <img src="{{Storage::url($items['featured_image'])}}" alt="{{$items['title']}}" loading="lazy">
                <div class="my-2 text-stone-600">發佈時間:{{$items['published_at']}}</div>
            </div>

            {{--        {{dd($items)}}--}}
            {!! $items['content'] !!}
    @include('Blog.blog_hot', ['BlogItems' => $BlogItems])
@endsection

@extends('Layouts.app')
@section($Slug, 'active')
@section('content')
    <div class="flex flex-row justify-between">

        <div class="flex flex-row my-2 ">
            <ol class="items-center flex space-x-2 text-sm ">

                <li>
                    <a href="/" class="hover:text-yes-major">首頁</a>
                </li>
                <li>
                    <span class="">></span>
                </li>
                <li aria-current="page" class="">
                    <a href="/blog/{{$Categories['slug']}}"
                       class="hover:text-yes-major">{{$Categories['name']}}</a>
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
        <img src="{{Storage::url($Media[$items['featured_image']])}}" alt="{{$items['title']}}" loading="lazy">
        <div class="my-2 text-stone-600 ">發佈時間:{{$items['published_at']}}</div>
    </div>

    {!! $items['content'] !!}
    @include('Blog.blog_hot', ['BlogItems' => $BlogItems])
@endsection

@extends('Layouts.superApp')
@section('bg', 'bg-[#000000]')
@section('metaInfo')
    <title>404 - 迷路了!</title>
@endsection

@section('content')
    <section class="bg-black  flex flex-col items-center justify-center w-full h-full ">
    <div class="text-center sm:m-5 xxx:m-2 my-5">
        <div class="mx-auto mb-6 max-w-xs">
        <img src="{{ Storage::url('404/Light.png') }}">
        </div>
        <h1 class="text-6xl font-bold mb-4 text-orange-400">404</h1>
        <p class=" mb-8 xxx:text-base xs:text-lg text-white">看來你迷路了！<br>別擔心，YESMAN 還有很多地方等你去探索！</p>
        <div class="flex gap-4 justify-center">
            <a href="/" class="bg-orange-400 text-gray-900 px-6 py-2 rounded-lg text-lg hover:text-orange-500 hover:bg-white transition">返回首頁</a>
            <a href="/itinerary" class="bg-orange-400 text-gray-900 px-6 py-2 rounded-lg text-lg hover:text-orange-500 hover:bg-white transition">探索更多</a>
        </div>
    </div>
    </section>
@endsection


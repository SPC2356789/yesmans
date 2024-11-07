@extends('Layouts.superApp')

@section('metaInfo')
    <title>404 - 迷路了!</title>
@endsection

@section('content')
    <section class="bg-[#C0D8D7] text-gray-800 flex items-center justify-center min-h-screen">
        <div class="container text-center max-w-md p-8 bg-white shadow-lg rounded-xl border border-gray-300">
            <!-- 標誌 -->
            <img src="網站標誌.jpg" alt="Yes Man Traveler Logo" class="w-36 mx-auto mb-6">

            <!-- 404 標題 -->
            <div class="text-6xl font-extrabold text-[#FF8A00] mb-4">
                404
            </div>

            <!-- 副標題和描述 -->
            <div class="text-2xl font-semibold text-gray-800 mb-4">
                看來你迷路了！
            </div>
            <div class="text-base text-gray-600 mb-6">
                別擔心，YESMAN 還有很多地方等你去探索！
            </div>

            <!-- 按鈕區域 -->
            <div class="flex justify-center gap-6">
                <a href="/"
                   class="px-6 py-3 text-white font-semibold bg-[#FF8A00] rounded-lg hover:bg-[#E57A00] transition-colors duration-300 transform hover:scale-105">
                    返回首頁
                </a>
                <a href="explore.html"
                   class="px-6 py-3 text-gray-800 font-semibold bg-gray-800 rounded-lg hover:bg-gray-700 transition-colors duration-300 transform hover:scale-105">
                    探索更多
                </a>
            </div>
        </div>
    </section>
@endsection


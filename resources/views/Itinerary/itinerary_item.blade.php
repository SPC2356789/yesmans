<div class="flex flex-wrap my-4 ">
    @for ($i = 0; $i < 12; $i++)
        <button name="itinerary_item" href="" id=""
                class="w-1/2 lg:w-1/3 2xl:w-1/3 p-3 transform transition-transform duration-300 hover:scale-105 hover:z-50 addHot group">
            <div class="bg-white bg-opacity-90 shadow-lg rounded-md overflow-hidden">
                <!-- 保證圖片的比例為 1:1 -->
                <div class="relative w-full" style="padding-top: 100%;">
                    @if(isset($tags))
                        <div class="flex flex-row absolute top-0 right-0 gap-1 z-10 xsm:m-2 xxx:m-1">
                            @for ($a = 0; $a < 2; $a++)
                                <span
                                    class="xxx:px-1 ss:px-1.5 xxx:rounded-xl ss:rounded-3xl bg-white text-[#212121] text-xs flex items-center justify-center">湖泊</span>
                            @endfor
                        </div>
                    @endif

                    <!-- 圖片 -->
                    <img
                        src="{{Storage::url('poc1.jpg')}}"
                        class="absolute top-0 left-0 w-full h-full object-cover object-center rounded-t-md"
                        alt="Image" loading="lazy">

                    <!-- 點擊報名文字層 -->
                    <div
                        class="absolute bottom-0 left-0 right-0 bg-[#519088cc] text-center text-white font-semibold p-2  md:hidden group-hover:block"
                        name="Itinerary_Sign_Up">
                        點擊報名
                    </div>
                </div>
                <div class="flex flex-col gap-1 p-1.5">
                    <div class="flex flex-col text-center  text-gray-800 ">
                        {{--                                                <span class="text-lg sm:text-xl lg:text-lg font-bold"> {{$item->title}}</span>--}}
                        {{--                                                <span class="text-sm sm:text-base lg:text-sm">{{$item->subtitle}}</span>--}}
                        <span class="text-lg sm:text-xl lg:text-lg font-bold">嘉明湖</span>
                        <span class="text-sm sm:text-base lg:text-sm">天使的眼淚</span>
                    </div>
                    {{--                    <div class="flex flex-row justify-between gap-0.5 text-center  text-gray-800 ">--}}
                    {{--                        --}}{{--                        <span class="text-lg sm:text-xl lg:text-lg font-bold"> {{$item->title}}</span>--}}
                    {{--                        --}}{{--                        <span class="text-sm sm:text-base lg:text-sm">{{$item->subtitle}}</span>  --}}
                    {{--                        <div class="flex flex-row gap-1">--}}
                    {{--                            <span--}}
                    {{--                                class="xxx:px-1 xxx:py-0.5 xxs:p-1 ss:px-1.5 xxx:rounded-xl ss:rounded-3xl bg-[#bccbc8] text-[#212121] text-xs  flex items-center justify-center">湖泊</span>--}}
                    {{--                            <span--}}
                    {{--                                class="xxx:px-1 xxx:py-0.5 xxs:p-1 ss:px-1.5 xxx:rounded-xl ss:rounded-3xl bg-[#bccbc8] text-[#212121] text-xs  flex items-center justify-center">步道</span>--}}
                    {{--                        </div>--}}
                    {{--                        <span class="text-sm sm:text-base lg:text-sm">天使的眼淚</span>--}}
                    {{--                    </div>--}}
                </div>
            </div>
        </button>

        {{--        @endforeach--}}
        {{--    @else--}}
        {{--        <div class="flex justify-center  w-full h-50 items-center">--}}
        {{--            <p>找不到相關文章...........</p>--}}
        {{--        </div>--}}

        {{--    @endif--}}
    @endfor
</div>
{{--{{ $items->appends(array('term' => $term))->links('pagination::tailwind') }}--}}




<div class="w-full h-8 my-2" id="loading-icon">
    <div class="flex justify-center items-center  gap-1 hidden " name="loadingIcon">
        <div class="w-6 h-6 border-4 border-gray-300 border-t-yes-major rounded-full animate-spin"></div>
        探索中
    </div>
</div>
@if(isset($items))
{{--    {{print_r($tag) }}--}}
    <div class="grid grid-cols-2 md965:grid-cols-3 gap-x-2.5 xsm:gap-x-3 md:gap-x-4 gap-y-5 xsm:gap-y-6 md:gap-y-10">
        @if($items && $items->isNotEmpty())
            @foreach($items as $item)
                <{{$div??'a'}} href="/{{$Slug}}/{{$item->categories->slug}}/{{$secondSlug}}/{{$item->slug}}"
                id="{{$Slug}}_{{$item->id}}"
                class=" group trip_btn_apply"
                data-time="{{json_encode($item['trip_times'])}}">

                <div
                    class="bg-white bg-opacity-90 shadow-lg  rounded-md overflow-hidden transform transition-transform duration-300 hover:scale-105 hover:z-50">
                    <!-- 保證圖片的比例為 1:1 -->
                    <div class="relative w-full" style="padding-top: 100%;">
                        @if(isset($tags))

                            <div class="flex flex-wrap  absolute top-0 right-0  z-10 gap-1  xsm:m-2 xxx:m-1">

                                @foreach($item->tags as  $itv)
                                    {{--                                        <button class="span_tag sm:text-sm" data-value="{{$tk}}">{{$tv}}</button>--}}
                                    <span
                                        class="xxx:px-1 ss:px-1.5 xxx:rounded-xl ss:rounded-3xl bg-white text-[#212121] text-xs flex items-center justify-center">{{$tags[$itv]['name']}}</span>
                                @endforeach
                            </div>

                        @endif
                        @if(isset($MediaMlt))
                            <img
                                src="{{Storage::url(($MediaMlt?$Media[$item->carousel[0]]:$Media[$item->featured_image]) ??'')}}"
                                class="absolute top-0 left-0 w-full h-full object-cover rounded-t-md object-contain "
                                alt="Image" loading="lazy">
                        @endif
                        @if(isset($apply) && $apply)

                            <!-- 點擊報名文字層 -->
                            <div
                                class="absolute bottom-0 left-0 right-0 bg-[#519088cc] text-xs ss:text-sm md:text-base text-center text-white font-semibold xxx:p-1 ss:p-1.5 md:p-2  md:hidden group-hover:block"
                                name="Itinerary_Sign_Up">
                                點擊報名
                            </div>
                        @endif
                    </div>
                    <div class="flex flex-col gap-0.5 py-2 text-center  text-gray-800 ">
                        <span class="text-lg sm:text-xl lg:text-lg font-bold"> {{$item->title}}</span>
                        <span class="text-sm sm:text-base lg:text-sm">{{$item->subtitle}}</span>
                    </div>
                </div>
    </{{$div??'a'}}>

    @endforeach
@else
    <div class="flex justify-center  w-full h-50 items-center">
        <p>找不到相關結果...........</p>
    </div>
@endif
<div class="px-1.5 w-full flex justify-center">
    {{ $items->appends($params)->links('pagination::tailwind') }}
</div>
</div>
@endif




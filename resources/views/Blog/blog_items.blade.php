<div class="flex flex-wrap my-4 ">
    @if($items && count($items) > 0)
        @foreach($items as $item)
            <a href="/blog/{{$item->category_slug . '/item/' . $item->slug}}" id="blog_{{$item->id}}"
               class="w-1/2 lg:w-1/3 2xl:w-1/3 p-3 transform transition-transform duration-300 hover:scale-105 hover:z-50 addHot">
                <div class="bg-white bg-opacity-90 shadow-lg rounded-md overflow-hidden">
                    <!-- 保證圖片的比例為 1:1 -->
                    <div class="relative w-full" style="padding-top: 100%;">
                        <img
                            src="{{Storage::url($item->featured_image ??'Blog/Items/1024X768.png')}}"
                            class="absolute top-0 left-0 w-full h-full object-cover rounded-t-md object-contain "
                            alt="Image">

                    </div>
                    <div class="flex flex-col gap-0.5 py-2 text-center  text-gray-800 ">
                        <span class="text-lg sm:text-xl lg:text-lg font-bold"> {{$item->title}}</span>
                        <span class="text-sm sm:text-base lg:text-sm">{{$item->subtitle}}</span>
                    </div>
                </div>
            </a>

        @endforeach
    @else
        <div class="flex justify-center  w-full h-50 items-center">
            <p>找不到相關文章...........</p>
        </div>

    @endif

</div>
{{ $items->appends(array('term' => $term))->links('pagination::tailwind') }}




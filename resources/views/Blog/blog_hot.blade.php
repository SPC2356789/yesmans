<section class="flex items-center flex-col ">
    <ol class="flex space-x-2 text-sm items-center my-12">
        <li aria-current="page" class="text-2xl">
            <div>精選文章</div>
        </li>
    </ol>
{{--    <div class="flex flex-wrap justify-center  w-full xl:w-[1090px] lg:w-[960px] md:px-24 sm:px-16 lg:px-4 xsm:px-8 xs:px-4 xxx:px-4">--}}
    <div class="flex flex-wrap justify-between w-full gap-2 ">
        @if($BlogItems && count($BlogItems) == 4)
            @foreach($BlogItems as $BlogItem)
                <a href="/blog/{{$BlogItem->category_slug . '/item/' . $BlogItem->slug}}"
                   id="blog_{{$BlogItem->id}}"
                   class="w-[47%] lg:w-[23%]  ">
                    <div class="bg-white bg-opacity-90 shadow-lg rounded-md overflow-hidden  transform transition-transform duration-300 hover:scale-105 hover:z-50">
                        <!-- 保證圖片的比例為 1:1 -->
                        <div class="relative w-full" style="padding-top: 100%;">
                            <img
                                src="{{Storage::url($Media[$BlogItem->featured_image]??'Blog/Items/1024X768.png')}}"
                                class="absolute top-0 left-0 w-full h-full object-cover rounded-t-md object-contain "
                                alt="Image">

                        </div>
                        <div class="flex flex-col gap-0.5 py-2 text-center  text-gray-800 ">
                            <span class="text-lg sm:text-xl lg:text-lg font-bold"> {{$BlogItem->title}}</span>
                            <span class="text-sm sm:text-base lg:text-sm">{{$BlogItem->subtitle}}</span>
                        </div>

                    </div>
                </a>

            @endforeach

    </div>

    <div class="my-5 text-center">
        <a href="/blog"
           class="inline-flex items-center px-4 py-2 text-white bg-primary rounded-lg hover:bg-blue-600 transition duration-300 transform hover:translate-x-1">
            看更多文章
            <svg class="ml-2 w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5-5 5m6-5H6"/>
            </svg>
        </a>
    </div>
    @endif
</section>

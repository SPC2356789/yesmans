<!-- 左側篩選區 -->
<div class="flex flex-col md:w-1/4 w-full justify-start gap-3 " name="navbar-l">
    <!-- 導航藍 -->
    {{--    月份--}}
    @if(isset($months))
        <div class="flex flex-col {{--md:min-h-46--}} gap-2">
            <button class="text-start text-xl font-bold text-gray-800 cursor-pointer" for="trip_month">選擇月份<i
                    class="fa-solid fa-circle-info mr-1 "></i></button>
            <select id="trip_month" autocomplete="off" multiple>
                @for ($si = 1; $si < 13; $si++)
                    <option value="{{$si}}">{{$si}} 月</option>
                @endfor
            </select>
        </div>
    @endif
    <!-- 篩選器 -->
    {{--                <div class="flex flex-col  px-3" name="span">--}}
    @if(isset($tags))
        <div class="flex flex-col gap-2" name="tags">
            <h1 class="text-xl font-bold text-gray-800">篩選器</h1>
            <div class="flex flex-wrap xxx:gap-1 ss:gap-1.5 items-center justify-start ">
                @foreach($tags as $tk => $tv)
                    <button class="span_tag sm:text-sm" data-value="{{$tv['slug']}}">{{$tv['name']}}</button>
                @endforeach
            </div>
        </div>
    @endif
    {{--    分類--}}
    @if(isset($Categories,$urlSlug))
        <div class="hidden md:flex md:flex-col gap-3" name="navbar-l_theme">
            <h1 class="text-xl font-bold text-gray-800 mb-2">{{ $sidebarTitle ??'' }}</h1>
            @foreach($Categories as $ck => $cv)
                <a href="/{{$Slug}}/{{ $ck }}" class="{{ ($urlSlug === $ck ? 'active' : '') }}">{{ $cv }}</a>
            @endforeach
        </div>
    @endif

</div>


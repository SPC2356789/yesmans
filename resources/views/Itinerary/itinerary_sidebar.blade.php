
            <!-- 左側篩選區 -->
            <div class="flex flex-col md:w-1/4 w-full justify-start space-y-6" name="navbar-l">
                <!-- 導航 -->
                <div class="hidden md:flex md:flex-col gap-3" name="navbar-l_theme">
                    <h1 class="text-xl font-bold text-gray-800 mb-2">文章分類</h1>
                    <a href="all" class="w-full">所有文章</a>
                    <a href="all" class="w-full">如何選擇登山路線</a>
                    <a href="#" class="">登山基礎知識</a>
                    <a href="#" class="">如何選擇登山路線</a>
                </div>

                <!-- 篩選器 -->
                <div class="flex flex-col px-3" name="tags">
                    <h1 class="text-xl font-bold text-gray-800">篩選器</h1>
                    <div class="flex flex-wrap xxx:gap-1 ss:gap-1.5 items-center justify-center md:justify-start ">
                        <button class="span_tag sm:text-sm">湖泊</button>
                        <button class="span_tag sm:text-sm">步道</button>
                        <button class="span_tag sm:text-sm">岩石</button>
                        <button class="span_tag sm:text-sm">森林</button>
                        <button class="span_tag sm:text-sm">溪流</button>
                        <button class="span_tag sm:text-sm">郊山</button>
                    </div>
                </div>
                <div class=" px-3">
                    <h1 class="text-xl font-bold text-gray-800">選擇月份</h1>
                    <select id="trip_month" autocomplete="off" multiple>
                        @for ($si = 1; $si < 13; $si++)
                            <option value="{{$si}}">{{$si}} 月</option>
                        @endfor
                    </select>
                </div>
            </div>


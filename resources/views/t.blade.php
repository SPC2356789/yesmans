@extends('Layouts.app')

@section('tLink')
    @vite(['resources/js/trip.js'])

@endsection
@section('blink')
    {{--    強壓TS套件的樣式--}}
    <style>
        .ts-control > * {
            display: flex !important;
        }
    </style>
@endsection
@section('content')

    <?php use Illuminate\Support\Facades\Storage; ?>
    <div class=" flex lg:flex-row flex-col gap-5 w-full justify-center ">
        <div class="flex flex-col  gap-6 w-full lg:w-1/2">
            <div class="swiper top_trip w-full  mx-auto aspect-square flex items-center">
                <div class="swiper-wrapper">
                    @for($tt = 1; $tt < 6; $tt++)
                        <div class="swiper-slide">
                            <img class="absolute top-0 left-0 w-full h-full object-cover object-center"
                                 src="{{storage::url('poc'.$tt.'.jpg')}}"/>
                        </div>
                    @endfor
                    <!-- Repeat for other slides -->
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>

            <div thumbsSlider="" class="swiper btm_trip w-full ">
                <div class="swiper-wrapper w-full">
                    @for($tt = 1; $tt < 6; $tt++)
                        <div class="swiper-slide">
                            <img src="{{storage::url('poc'.$tt.'.jpg')}}"/>
                        </div>
                    @endfor
                </div>
            </div>

        </div>
        <div class="flex flex-col gap-10 justify-between">
            <div>
                <h1 class="text-2xl">水樣森林</h1>
                <p>
                    水樣森林，位於台灣的杉林溪，是一個充滿詩意與神秘的自然景觀。這片靜謐的森林曾因地震與風災而形成，倒伏的樹木在水中靜靜矗立，宛如一幅天然的水墨畫。隨著水位的變化，倒影與光影交錯，讓人彷彿置身於夢幻之境。

                    走進水樣森林，你會被環繞在翠綠的山林與清澈的水域之間，感受到大自然的鬼斧神工。在這裡，除了欣賞迷人的景色外，也能觀察到豐富的生態，聆聽鳥鳴與溪流的交響，體驗到一種與自然共鳴的寧靜與和諧。水樣森林，是一處讓人遠離塵囂、感受心靈平靜的秘境。
                </p>
            </div>
            <div class="w-full flex flex-col">
                <div class="flex flex-row ">
                    <div class="flex flex-row gap-2 w-1/2">
                        <h1 class="text-2xl">報名日期</h1>
                        <div class="w-auto">
                            @include('Layouts.select', ['li'=>true,'$Categories' => '','urlSlug'=>''])
                        </div>
                    </div>
                    <div class="flex flex-row gap-2">
                        <h1 class="text-2xl">報名費用</h1>
                        <div class="w-auto">
                            $9999
                        </div>
                    </div>
                </div>
                <button
                        name="signupBtn"
                        class="w-[200px] text-center xxx:text-xs  ss:text-base sm:text-lg lg:text-base xs:px-0.5 py-2 border border-gray-800 text-gray-800 rounded hover:bg-[#DA8A51] bg-neutral-50  hover:text-white">
                    <span class="w-auto ">我要報名</span>
                    <i class="fas fa-chevron-right w-1/12"> </i>
                </button>
            </div>
        </div>
    </div>
    <div class="my-2">
        <h1>探索台灣的壯麗山岳</h1>
        <p>
            台灣，這個美麗的島嶼，擁有多樣的自然景觀，其中山岳景觀是最令人驚嘆的一部分。從北部的陽明山，到南部的阿里山，台灣的山岳為遊客提供了豐富的登山和賞景體驗。以下我們將介紹幾個台灣最著名的山岳景點，讓您對這片美麗土地有更深入的了解。</p>

        <h2 class="section-title">玉山 - 台灣的最高峰</h2>
        <img src="https://www.ysnp.gov.tw/FileDownload/ContentManagement/20200917185611616637714.jpg" alt="玉山"
             class="image">
        <p>
            玉山，又稱新高山，是台灣最高的山峰，海拔3,952公尺。位於南投縣的玉山國家公園，玉山不僅是登山愛好者的挑戰目標，也是生態旅遊的熱門地點。每年的秋冬季節，是攀登玉山的最佳時機，天氣晴朗，能見度高，讓人能夠飽覽台灣的壯麗景色。</p>

        <h2 class="section-title">陽明山 - 靜謐的火山地帶</h2>
        <img src="https://www.ysnp.gov.tw/FileDownload/ContentManagement/20200917185611616637714.jpg" alt="陽明山"
             class="image">
        <p>
            陽明山位於台北市近郊，是一座以火山地形著稱的國家公園。這裡擁有豐富的溫泉資源和壯觀的硫磺谷，吸引著大量的遊客。春天時節，陽明山上盛開的櫻花和杜鵑花，更為這片土地增添了不少迷人的色彩。</p>

        <h2 class="section-title">阿里山 - 賞日出的聖地</h2>
        <img src="https://www.ysnp.gov.tw/FileDownload/ContentManagement/20200917185611616637714.jpg" alt="阿里山"
             class="image">
        <p>
            阿里山是台灣南部最著名的旅遊景點之一，以壯麗的日出景觀和獨特的高山鐵路而聞名。清晨登上祝山，等待太陽從雲海中升起，是阿里山之旅的亮點之一。除了日出，阿里山的高山茶園和神木群也值得一遊。</p>

        <h2 class="section-title">雪山 - 探索原始的美景</h2>
        <img src="https://www.ysnp.gov.tw/FileDownload/ContentManagement/20200917185611616637714.jpg" alt="雪山"
             class="image">
        <p>
            雪山是台灣第二高峰，海拔3,886公尺，位於台中市和平區境內。雪山擁有壯麗的高山草原和豐富的動植物生態，是許多登山客心中的夢幻之地。尤其是每年的冬季，雪山的白雪覆蓋景象，讓人彷彿置身於童話世界。</p>

        <p>
            台灣的山岳不僅提供了壯麗的自然景觀，還蘊含了豐富的生態資源和文化內涵。無論是尋求冒險的登山者，還是喜歡悠閒賞景的旅客，這片美麗的土地都能夠滿足您的需求。讓我們一同探索台灣的山岳之美，享受大自然帶來的寧靜與感動。</p>

    </div>
    {{--    同意書--}}
    <div class="my-8">
        <h1>同意書相關規範</h1>
        <div class="h-56 overflow-y-scroll border border-gray-300 p-4 agree-ctn">

            <p>【活動切結書】
                1. 本人已瞭解本次活動為戶外活動，具有潛在之危險性，若發生意外會導致受傷或死亡。

                2. 本人確定身體健康狀況良好，適合從事戶外活動，並無心臟病、懼高症、高血壓、懷孕等不適合從事旅遊戶外活動等症狀，亦無年齡過小及身體狀況無法獨自完成行程之情形，如有身體不適本人應主動告知本此活動主辦人員。

                3. 本人瞭解並同意遵守活動相關規定，聽從本次活動指導員之指導，隨時注意自身安全，並不危及他人安全。若因個人因素導致意外傷亡，本人願自行負責，概與主辦單位及任何人員無涉，含因個人身體不適而未主動告知。

                4.
                本人同意從事戶外活動，在「安全」為第一考量前提下，因不可歸責於主辦單位之原因（包括但不限於因颱風、冰雪、豪雨、坍方、隊員身體狀況等）而改變本次活動行程，本人同意遵從主辦單位及其人員在上述狀況下的決定事項，本人不得異議。

                5. 本人同意報名進階登山健行活動，應自行衡量體力選擇參加，並應依本次活動屬性及氣候需要，準備個人所需裝備（個人建議裝備清單所提及之裝備）。雨天如未接獲主辦單位通知取消本次活動則照常出發，應自備雨具。

                6. 本人同意本次活動如有進入「國家公園」園區，需抽籤決定入園名單時，應請先繳交全額費用完成報名始辦理抽籤，若未抽中而不欲後補者，可以依本切結書「退款機制」申請退費。

                7.
                活動出發前，如因不可歸責於主辦單位之事由（例如:天然災害：颱風、冰雪、豪雨、坍方）而封山，或因顧慮安全，經主辦單位決定取消本次活動，本人可選擇延期參加或依「退款方式」申請退費，但因可歸責於本人之原因造成無法參加本次活動者除外。

                8. 活動內容主辦單位保有隨時修改及終止本活動之權利。

                9.
                本人同意活動出發當天應依約定時間至集合處，本人遲到、未到、中途離隊或於活動中，因本人身體情況等可歸責本人之原因造成無法完成本次活動，因而產生之額外費用本人同意自行負擔，本次活動費用無法退換。如因此造成主辦單位或其人員之損害、本人應為賠償。

                10. 活動中，本人同意遵守本次活動人員之一切指示，包括但不限於：（1）不超前、落後本次活動人員（2）不走捷徑（3）飲水不牛飲（4）不落單（不做獨行俠）（5）不飆山（急行軍）（6）不亂丟垃圾及煙蒂。

                11. 本次活動如需辦理甲種入山證、保險或其他申請手續，本人同意將個人資料提供給主辦單位及其人員。本人同意應於行前會前繳交身份證影本及提供所需資料，以便辦理。活動當日應帶身分證正本備查。

                12. 本次活動如未達人數，主辦單位及其人員得酌情延期或退費。

                13. 本次活動一律投保登山險，本次活動主辦單位及其人員之責任以保險所賠償範圍為限 。

                14. 報名活動如有不搭車、不住宿、或自行開車登山口報到、住宿方式（加住雙人套房、四人同住一間）等需求，本人應於報名時告知主辦單位，報名完成後，則無法更動。

                15. 活動行前會時間為活動前 2 至 3 週。

                【退款機制】

                1. 報名時匯款活動費用 30% 之訂金始得完成報名。

                2. 活動出發前 60 天-36天內取消，酌收活動費用 15% 之行政費用，其餘全數退還。

                3. 活動出發前 35－14 天之間則酌收活動費用 50% 之行政費用，其餘全數退還。（＊抽籤行程若未中籤可退費）

                4. 活動出發前 14 天內不予退費，敬請見諒。

                5. 若是有住宿山屋之行程，活動名額不得轉讓，入園申請上會有問題，敬請見諒。

                6. 如因不可歸責於主辦單位之情形（例如天氣、路況等因素），由主辦單位進行專業判斷是否影響行程安危進而取消活動，若由主辦單位取消本次活動，則全額退還。

                7. 如因本次活動人數不足，經主辦單位決定取消本次活動，則全額退還。

                8. 如本次活動取消欲退費者，應於主辦單位為通知後兩個月內辦理退費。</p>

        </div>
    </div>

    <button class="flex flex-initial" type="button" name="agree_btn">
        <input type="checkbox" id="agreeCheckbox" disabled/>
        <div class="checkbox"></div>
        <label class="cursor-pointer hover:text-[#ff9a63]" name="CheckLabel" for="agreeCheckbox">我同意條款<span
                    class="text-neutral-500 opacity-80">(請滑至同意書底部)</span></label>
    </button>

    <div class="hidden" id="trip_from">
        @include('Itinerary.t_from')
    </div>

@endsection

<template>
    <div
        class="d-flex justify-content-between align-items-center xxx:w-full xl:min-w-[550px] xl:max-w-[550px] lg992:min-w-[420px] lg992:max-w-[420px] md965:w-[370px]">
        <h3 class="fw-bold fs-3">行程訊息</h3>
        <a href="/itinerary"
           class="w-[18.8%] text-center ms-auto xxx:text-xs  ss:text-base sm:text-lg md965:text-base xs:px-0.5 py-2 border border-gray-800 text-gray-800 rounded hover:bg-[#DA8A51] bg-neutral-50  hover:text-white focus:outline-none">
            <span class="w-11/12 "></span> 看更多
            <i class="fas fa-chevron-right w-1/12"> </i>
        </a>
    </div>

    <div class="flex flex-row  gap-10">
        <v-tabs bg-color="transparent" v-model="tab"
                class="pt-2 hover:pt-6 xxx:w-full xl:min-w-[550px] xl:max-w-[550px] lg992:min-w-[420px] lg992:max-w-[420px] md965:w-[370px]">

            <div class="flex flex-row justify-between    w-full  items-end relative overflow-visible gap-1 h-full">


                <v-tab
                    color="black"

                    v-for="(label, key) in tripTab"
                    :key="key"
                    :value="key"
                    @click="refreshComponent"
                    class=" text-none text-black tabButton h-full pt-2 pb-1 transition-all duration-300 ease-in-out hover:py-6 xxx:w-[19.5%] xxs:w-[18.8%] us-420:w-[18%] lg:w-[18%]
        transform "
                    :style="{
            backgroundColor: tab === key ? '#5B9894' : '#b8cac9'
        }"
                >
    <span
        class=" xxx:text-xs ss:text-sm w-full h-full">
        {{ label }}
    </span>
                </v-tab>

            </div>

        </v-tabs>
        <div class=" flex-col hidden md965:flex">
            <span>     {{ hoveredTitle }}</span>
            <span>     {{ hoveredTime }}</span>
        </div>
    </div>

    <v-tabs-window v-model="tab">

        <v-tabs-window-item class=" " v-for="(data, d_key) in tripData" :key="d_key" :value="d_key">
            <div class="flex flex-row justify-between ">
                <v-card class="border-0 xxx:w-full xl:w-[550px] lg992:w-[420px] md965:w-[370px]">
                    <ul class="list-group list-group-flush fs-6 "
                        v-for="(item, key) in data"
                        :key="key">

                        <li class="xxx:pt-2 xs:pt-3 px-2  sm:pl-3  xxx:py-2  lg:py-3  transition-all transform itinerary_tab bg-neutral-50 border-b border-b-[#a8b6ad] cursor-pointer"
                            @click="toggleImage(key)"
                            :ref="(el) => (liRefs[key] = el)"
                            @mouseover="
                                       hoveredKey = item.trip.carouselSpell[0];
                                       hoveredTitle = item.trip.title + ' - ' + item.trip.subtitle;
                                       hoveredTime = item.dateAll"
                            @mouseleave="
                                       hoveredKey = null;
                                       hoveredTime = null;
                                       hoveredTitle = null"
                        >

                            <div class="flex items-center ">
                                <img class="mr-1 xxx:w-4 ss:w-5 sm:w-5 "
                                     :src="item.trip.iconSpell"
                                     loading="lazy" alt="icon1"/>
                                <span
                                    class="block fw-bold  text-lg ss:text-xl sm:text-xl  text-left ">{{
                                        item.trip.title + '-' + item.trip.subtitle
                                    }}</span>


                            </div>
                            <div
                                class="flex flex-row w-full items-end justify-between ">
                                <div
                                    class="flex  flex-col  gap-1 w-4/5 ">

                                                        <span
                                                            class="text-[#da8a51] text-xs ss:text-sm  w-auto ">{{
                                                                item.dateAll
                                                            }}</span>
                                    <div
                                        class="flex flex-wrap xxx:gap-1 ss:gap-1.5 items-center">
                                        <!--                                    @foreach($tripTime['trip']['tags'] as $tripTag)-->
                                        <span class="span_tag" v-for="(tag) in item.trip.tagSpell">{{
                                                tag
                                            }}</span>

                                    </div>

                                </div>
                                <div class="w-1/5 flex flex-col align-end h-full">
                                    <a :href="`/itinerary/${d_key}/trip/${item.trip.slug}?trip_time=${key}`"
                                       class="w-11/12 xxx:px-1 xxx:py-2 sm:p-2 md965:p-1 text-center  rounded-full bg-[#55958d] text-[#f7dbab] hover:text-[#467771] hover:bg-[#f8b551] xxx:text-xs xxs:text-sm xs:text-base sm:text-lg md965:text-base lg:text-base ">
                                        報名中

                                    </a>
                                </div>
                            </div>
                            <div
                                class="md965:hidden xxx:mr-2 sm:mr-3 flex justify-center  items-center ">
                                <i class="fa-solid fa-sort-down text-[#5B9894FF]"> </i>

                            </div>
                        </li>
                        <!--                        <pre>-->
                        <!--                           {{item}}-->
                        <!--                        </pre>-->
                        <img
                            v-if="visibleImages[key]"
                            :ref="(el) => (imageRefs[key] = el)"
                            class="itinerary_img md965:hidden"
                            :src="item.trip.carouselSpell[0]"
                            :class="{ hidden: !visibleImages[key] }"
                            loading="lazy"
                            @click="toggleImage(key)"
                        />
                    </ul>
                </v-card>
                <tripCarousel :tripData="data" :hoveredKey="hoveredKey" v-if="showCarousel"/>
            </div>

        </v-tabs-window-item>
    </v-tabs-window>

</template>

<script setup>
import {ref, onMounted, nextTick} from "vue";
import tripCarousel from "../views/tripCarousel.vue";
import axios from "axios";

const tab = ref("one");

const tripTab = ref(null);
const tripData = ref(null);
const showCarousel = ref(true);


const visibleImages = ref({});
const imageRefs = ref({});
const liRefs = ref({});
const hoveredKey = ref(null);
const hoveredTitle = ref(null);
const hoveredTime = ref(null);
// ✅ **切換圖片顯示並滾動**
const toggleImage = async (key) => {

    const liElement = liRefs.value[key];
    // 如果當前圖片已顯示，則關閉
    if (visibleImages.value[key]) {
        visibleImages.value[key] = false;
        liElement.scrollIntoView({behavior: "smooth", block: "center"});
        return;
    }

    // 先關閉所有圖片，再開啟當前選中的圖片
    visibleImages.value = {[key]: true};

    //  等待 DOM 更新
    await nextTick();

    const imgElement = imageRefs.value[key];
    if (!imageRefs.value[key]) return; // 防止 `undefined` 錯誤

    // **使用 `scrollIntoView` 滾動**
    imgElement.scrollIntoView({behavior: "smooth", block: "center"});
};
// 🔹 子件刷新解除swiper跟tab結合的bug
const refreshComponent = () => {
    showCarousel.value = false;
    setTimeout(() => {
        showCarousel.value = true;
    }, 50);
};
// 🔹 從 API 獲取資料
const fetchTripData = async () => {
    try {
        const response = await axios.get("/getTrip"); // API 端點
        if (response.data) {
            tripTab.value = response.data.tripTab || {};
            tripData.value = response.data.tripData || {};
        }

        // ✅ 自動選擇第一個 tab
        const firstKey = Object.keys(tripTab.value)[0];
        if (firstKey) {
            tab.value = firstKey;
        }
    } catch (error) {
        // console.error("API 請求失敗:", error);
    }
};

onMounted(() => {
    fetchTripData();
});

// onMounted(() => {
//     const tabElement = document.getElementById("tab");
//     if (tabElement) {
//         try {
//             tripTab.value = JSON.parse(tabElement.dataset.trip_tab);
//             tripData.value = JSON.parse(tabElement.dataset.trip_data);
//             // console.log("tripTab:", tripTab.value);
//             // console.log("tripData:", tripData.value);
//         } catch (error) {
//             // console.error("JSON 解析錯誤:", error);
//         }
//     }
//     // 自動選擇第一個 tab
//     const firstKey = Object.keys(tripTab.value)[0];
//     if (firstKey) {
//         tab.value = firstKey;
//     }
// });
</script>



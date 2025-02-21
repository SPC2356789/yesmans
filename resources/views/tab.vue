<template>
    <div
        class="d-flex justify-content-between align-items-center xxx:w-full xl:min-w-[550px] xl:max-w-[550px] lg992:min-w-[420px] lg992:max-w-[420px] md965:w-[370px]">
        <h3 class="fw-bold fs-3">行程訊息</h3>
        <a href="/itinerary"
           class="w-[18.8%] text-center ms-auto xxx:text-xs ss:text-base sm:text-lg md965:text-base xs:px-0.5 py-2 border border-gray-800 text-gray-800 rounded hover:bg-[#DA8A51] bg-neutral-50 hover:text-white focus:outline-none">
            <span class="w-11/12"></span> 看更多
            <i class="fas fa-chevron-right w-1/12"></i>
        </a>
    </div>

    <div class="flex flex-row gap-10">
        <v-tabs bg-color="transparent" v-model="tab"
                class="pt-2 xxx:w-full xl:min-w-[550px] xl:max-w-[550px] lg992:min-w-[420px] lg992:max-w-[420px] md965:w-[370px]">
            <div class="flex flex-row justify-between w-full items-end relative overflow-visible h-full">
                <v-tab
                    color="black"
                    v-for="(label, key) in tripTab"
                    :key="key"
                    :value="key"
                    class="text-none text-black tabButton h-full transition-all duration-300 ease-in-out hover:py-6 xxx:w-[19.5%] xxs:w-[18.8%] us-420:w-[18%] lg:w-[18%] transform"
                    :style="{ backgroundColor: tab === key ? '#5B9894' : '#b8cac9' }">
                    <span class="xxx:text-xs ss:text-sm w-full h-full">{{ label }}</span>
                </v-tab>
            </div>
        </v-tabs>
        <div class="flex-col justify-start hidden md965:flex">
            <span>{{ hoveredTitle }}</span>
            <span>{{ hoveredTime }}</span>
        </div>
    </div>

    <v-tabs-window v-model="tab">
        <v-tabs-window-item v-for="(data, d_key) in tripData" :key="d_key" :value="d_key">
            <div class="flex flex-row justify-between">
                <v-card class="border-0 xxx:w-full xl:w-[550px] lg992:w-[420px] md965:w-[370px] ">
                    <ul class="list-group list-group-flush fs-6">
                        <li v-for="(item, index,number) in data" :key="index"
                            class="xxx:pt-2 xs:pt-3 px-2 sm:pl-3 xxx:py-2 lg:py-3 transition-all transform itinerary_tab bg-neutral-50 border-b border-b-[#a8b6ad] cursor-pointer"
                            @click="toggleImage(index)"
                            @mouseover="
                                hoveredKey = number;
                                hoveredTitle = item.trip.title + ' - ' + item.trip.subtitle;
                                hoveredTime = item.dateAll"
                            @mouseleave="hoveredKey = null; hoveredTime = null; hoveredTitle = null"
                            :ref="(el) => (listItemRefs[index] = el)">
                            <div class="flex items-center">
                                <img class="mr-1 xxx:w-4 ss:w-5 sm:w-5" :src="item.trip.iconSpell" loading="lazy" alt="icon"/>
                                <span class="block fw-bold text-lg ss:text-xl sm:text-xl text-left">
                                    {{ item.trip.title + '-' + item.trip.subtitle }}
                                </span>
                            </div>
                            <div class="flex flex-row w-full items-end justify-between">
                                <div class="flex flex-col gap-1 w-4/5">
                                    <span class="text-[#da8a51] text-xs ss:text-sm">{{ item.dateAll }}</span>
                                    <div class="flex flex-wrap xxx:gap-1 ss:gap-1.5 items-center">
                                        <span class="span_tag" v-for="(tag) in item.trip.tagSpell" :key="tag">{{ tag }}</span>
                                    </div>
                                </div>
                                <div class="w-1/5 flex flex-col items-end h-full">
                                    <a :href="`/itinerary/${d_key}/trip/${item.trip.slug}?trip_time=${index}`"
                                       class="w-11/12 xxx:px-1 xxx:py-2 sm:p-2 md965:p-1 text-center rounded-full bg-[#55958d] text-[#f7dbab] hover:text-[#467771] hover:bg-[#f8b551] xxx:text-xs xxs:text-sm xs:text-base sm:text-lg md965:text-base lg:text-base">
                                        報名中
                                    </a>
                                </div>
                            </div>
                            <div class="md965:hidden xxx:mr-2 sm:mr-3 flex justify-center items-center">
                                <i class="fa-solid fa-sort-down text-[#5B9894FF]"></i>
                            </div>
                            <img
                                v-if="visibleImages[index]"
                                :ref="(el) => (imageRefs[index] = el)"
                                class="itinerary_img md965:hidden"
                                :src="item.trip.carouselSpell[0]"
                                :class="{ hidden: !visibleImages[index] }"
                                loading="lazy"
                                @click.stop="toggleImage(index)"
                            />
                        </li>
                    </ul>
                </v-card>
                <tripCarousel :tripData="currentTripData" :hoveredKey="hoveredKey" />
            </div>
        </v-tabs-window-item>
    </v-tabs-window>
</template>

<script setup>
import { ref, onMounted, nextTick, computed } from "vue";
import tripCarousel from "../views/tripCarousel.vue";
import axios from "axios";

const tab = ref("one");
const tripTab = ref({});
const tripData = ref({});

const visibleImages = ref({});
const imageRefs = ref({});
const listItemRefs = ref({});
const hoveredKey = ref(null);
const hoveredTitle = ref(null);
const hoveredTime = ref(null);

const currentTripData = computed(() => tripData.value[tab.value] || []);

const toggleImage = async (index) => {
    const liElement = listItemRefs.value[index];
    if (visibleImages.value[index]) {
        visibleImages.value[index] = false;
        liElement.scrollIntoView({ behavior: "smooth", block: "center" });
        return;
    }

    visibleImages.value = { [index]: true };
    await nextTick();

    const imgElement = imageRefs.value[index];
    if (!imgElement) return;

    imgElement.scrollIntoView({ behavior: "smooth", block: "center" });
};

const fetchTripData = async () => {
    try {
        const response = await axios.get("/getTrip");
        tripTab.value = response.data.tripTab || {};
        tripData.value = response.data.tripData || {};

        const firstKey = Object.keys(tripTab.value)[0];
        if (firstKey) tab.value = firstKey;
    } catch (error) {
        console.error("API 請求失敗:", error);
        alert("無法載入行程資料，請稍後再試");
    }
};

onMounted(fetchTripData);
</script>

<style scoped>
.itinerary-container {
    @apply xxx:w-full xl:min-w-[550px] xl:max-w-[550px] lg992:min-w-[420px] lg992:max-w-[420px] md965:w-[370px];
}

</style>

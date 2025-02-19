<template>



<div  class="md965:w-[435px] lg:min-w-[450px] lg:max-w-[450px] hidden md965:block w-full h-full">


    <swiper
        :spaceBetween="30"
        :effect="'fade'"

        :navigation="true"
        :pagination="{ clickable: true }"
        :modules="modules"

    >
        <swiper-slide v-for="(trip, index) in props.tripData" :key="index" class="">
            <img
                :src="props.hoveredKey ?props.hoveredKey :trip.trip.carouselSpell[0] "
                :alt="trip.title"
                class="w-full h-full"
            /></swiper-slide>
    </swiper>
</div>
</template>

<script setup>

import { Swiper, SwiperSlide } from "swiper/vue";
import { EffectFade, Navigation, Pagination, Autoplay } from "swiper/modules";

import "swiper/css";
import "swiper/css/effect-fade";
import "swiper/css/navigation";
import "swiper/css/pagination";

// 正確獲取 props
const props = defineProps({
    tripData: {
        type: Object,
        required: true
    },
    hoveredKey: {
        type: String,
        required: false, // 允許 hoveredKey 可選
        default: null    // 預設值為 null，避免 undefined
    }
});
//
// console.log("收到的 tripData:", props.tripData);
const modules = [EffectFade, Navigation, Pagination, Autoplay];
</script>


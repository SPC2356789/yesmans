<template>

    <swiper
        :spaceBetween="30"
        :effect="'fade'"

        :navigation="true"
        :pagination="{ clickable: true }"
        :modules="modules"
        class=""
    >
        <swiper-slide v-for="(trip, index) in props.tripData" :key="index">
            <img :src="trip.trip.carouselSpell[0]" :alt="trip.title" />
        </swiper-slide>
    </swiper>
</template>

<script setup>
import { defineProps } from "vue";
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
    }
});

console.log("收到的 tripData:", props.tripData);
const modules = [EffectFade, Navigation, Pagination, Autoplay];
</script>


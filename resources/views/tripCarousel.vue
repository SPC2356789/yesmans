<template>
    <div class="swiper-container md965:w-[435px] lg:min-w-[450px] lg:max-w-[450px] hidden md965:block w-full h-full">
        <swiper
            :spaceBetween="30"
            :effect="'fade'"
            :navigation="true"
            :pagination="{ clickable: true }"
            :modules="modules"
            @swiper="onSwiper"
        >
            <swiper-slide v-for="(trip, index) in props.tripData" :key="index">
                <img
                    :src="trip.trip.carouselSpell[0]"
                    :alt="trip.trip.title"
                    class="w-full h-full object-cover"
                />
            </swiper-slide>
        </swiper>
    </div>
</template>

<script setup>
import { ref, watch, onMounted, nextTick } from "vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import { EffectFade, Navigation, Pagination, Autoplay } from "swiper/modules";
import "swiper/css";
import "swiper/css/effect-fade";
import "swiper/css/navigation";
import "swiper/css/pagination";

const props = defineProps({
    tripData: {
        type: Object,
        required: true,
    },
    hoveredKey: {
        type: Number, // 改為 Number，因為現在是索引
        required: false,
        default: null,
    },
});

const modules = [EffectFade, Navigation, Pagination, Autoplay];
const swiperInstance = ref(null);

const onSwiper = (swiper) => {
    swiperInstance.value = swiper;
};

watch(
    () => props.tripData,
    () => {
        nextTick(() => {
            if (swiperInstance.value) {
                swiperInstance.value.update();
            }
        });
    },
    { deep: true }
);

watch(
    () => props.hoveredKey,
    (newHoveredKey) => {
        if (swiperInstance.value && newHoveredKey !== null) {
            nextTick(() => {
                swiperInstance.value.slideTo(newHoveredKey, 300); // 直接使用索引跳轉
            });
        }
    }
);

onMounted(() => {
    nextTick(() => {
        if (swiperInstance.value) {
            swiperInstance.value.update();
        }
    });
});
</script>


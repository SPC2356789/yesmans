import $ from 'jquery';
import Swiper from 'swiper';
import {Navigation, Pagination, Autoplay, EffectFade} from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/autoplay';
import 'swiper/css/effect-fade';
import {createApp} from 'vue'

// Vuetify
import '@mdi/font/css/materialdesignicons.css'

import 'vuetify/styles' // 確保仍載入其他 Vuetify 樣式

import {createVuetify} from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
// Components
import Tab from '../views/tab.vue'

const vuetify = createVuetify({
    components,
    directives,
    defaults: {
        global: {
            reset: false, // 防止 Vuetify 自動 Reset
        },
    },
})

createApp(Tab).use(vuetify).mount('#tab')


$(document).ready(function () {
    // 輪播渲染
    Carousel(); // 確保這個函式只在 DOM 載入後執行

})

function Carousel() {
    const swiperCarousel = new Swiper(".swiper.carousel", {
        spaceBetween: 30,
        effect: "fade",
        autoplay: {
            delay: 2500,
            // disableOnInteraction: false,
        },
        navigation: {
            nextEl: ".swiper-button-next.carousel_arrow",
            prevEl: ".swiper-button-prev.carousel_arrow",
        },
        modules: [Navigation, Autoplay, EffectFade],
    });
    const swiperItinerary = new Swiper(".swiper.Itinerary", {
        spaceBetween: 30,
        effect: "fade",
        navigation: {
            nextEl: ".swiper-button-next.Itinerary",
            prevEl: ".swiper-button-prev.Itinerary",
        },
        pagination: {
            el: ".swiper-pagination.Itinerary",
            clickable: true,
        },
        modules: [Navigation, Pagination, Autoplay, EffectFade],
    });


}


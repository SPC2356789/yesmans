import $ from 'jquery';
import Swiper from 'swiper';
import {Navigation, Pagination, Autoplay, EffectFade} from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/autoplay';
import 'swiper/css/effect-fade';

import { createApp } from 'vue'

// Vuetify
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
// Components
import Tab from '../views/tab.vue'

const vuetify = createVuetify({
    components,
    directives
})

createApp(Tab).use(vuetify).mount('#tab')



$(document).ready(function () {
    // 輪播渲染
    Carousel(); // 確保這個函式只在 DOM 載入後執行

    $(document).on('mouseenter', 'li.itinerary_tab', function () {
        const lock_data = $('.tab-lock img[data-lock="' + $(this).data('tab') + '"]');
        console.log('換');
        lock_data.addClass('lock-active');
    });

    $(document).on('mouseleave', 'li.itinerary_tab', function () {
        const lock_data = $('.tab-lock img[data-lock="' + $(this).data('tab') + '"]');
        console.log('回');
        lock_data.removeClass('lock-active');
    });
    $(document).on('click', '.tab-pane', function () {


    });

    $(document).on('click', 'li.itinerary_tab', function () {
        // 點擊 tab 切換圖片顯示
        const tab = $(this);  // 當前被點擊的 tab
        const tab_img = $('.tab-pane img.itinerary_img[data-lock="' + tab.data('tab') + '"]');  // 這個 tab 對應的圖片

        // 1. 如果當前 tab 已經是 active，則隱藏圖片並移除 active 類
        if (tab.hasClass('lock')) {
            tab.removeClass('lock');
            tab_img.addClass('hidden');  // 隱藏當前 tab 的圖片
            $('#itinerary_tab_content .card')[0].scrollIntoView({behavior: 'smooth', block: 'start'});  // 滾動到圖片

        } else {
            // 2. 否則，隱藏所有圖片
            $('.tab-pane img.itinerary_img').addClass('hidden');

            // 3. 顯示當前 tab 對應的圖片
            tab_img.removeClass('hidden');

            // 4. 移除所有 tab 的 active 類，然後給當前點擊的 tab 添加 active 類
            $('li.itinerary_tab').removeClass('lock');
            tab.addClass('lock');

            // 5. 滾動到當前圖片的位置
            tab_img[0].scrollIntoView({behavior: 'smooth', block: 'center'});  // 滾動到圖片
        }
    });

// 點擊圖片時，隱藏圖片並移除 active 類
    $('.tab-pane img.itinerary_img').on('click', function () {
        const img = $(this);  // 當前被點擊的圖片
        img.addClass('hidden');  // 隱藏該圖片
        const tab = $('li.itinerary_tab[data-tab="' + img.data('lock') + '"]');  // 找到與圖片對應的 tab
        tab.removeClass('lock');  // 移除該 tab 的 active 類
        // 5. 滾動到當前圖片的位置
        $('#itinerary_tab_content .card')[0].scrollIntoView({behavior: 'smooth', block: 'start'});  // 滾動到圖片
    });
})


;

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

    // // 初始化 Itinerary 轮播
    // $('.swiper.Itinerary').each(function () {
    //     let $swiper = $(this);
    //     let $container = $swiper.closest('.tab-pane');
    //
    //     if ($container.length === 0) {
    //         console.warn("swiper-container not found for:", $swiper);
    //         return; // 避免报错
    //     }
    //
    //     new Swiper(this, {
    //         spaceBetween: 30,
    //         effect: "fade",
    //         navigation: {
    //             nextEl: $container.find('.swiper-button-next')[0],
    //             prevEl: $container.find('.swiper-button-prev')[0],
    //         },
    //         pagination: {
    //             el: $container.find('.swiper-pagination')[0],
    //             clickable: true,
    //         },
    //         modules: [Navigation, Pagination, Autoplay, EffectFade],
    //     });
    // });


}


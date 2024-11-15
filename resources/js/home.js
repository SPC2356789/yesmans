import $ from 'jquery';
import Swiper from 'swiper';
import { Navigation, Pagination ,Autoplay,EffectFade  } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/autoplay';
import 'swiper/css/effect-fade';

$(document).ready(function () {

    var swiperCarousel = new Swiper(".swiper.carousel", {
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
        modules: [Navigation, Pagination,Autoplay,EffectFade],
    });

    // 初始化 Itinerary 轮播
    var swiperItinerary = new Swiper(".swiper.Itinerary", {
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
        modules: [Navigation,Autoplay,EffectFade],
    });

//行程欄位圖片鎖定
    $('li.itinerary_tab').hover(
        function () {
            const lock_data = $('.tab-lock img[data-lock="' + $(this).data('tab') + '"]');
            console.log(lock_data)
            lock_data.addClass('lock-active')
        },
        function () {
            const lock_data = $('.tab-lock img[data-lock="' + $(this).data('tab') + '"]');
            console.log(2)
            lock_data.removeClass('lock-active');
        }
    );
    // $('li.itinerary_tab.active').on('click', function () {
    //     console.log(212121)
    //     $(this).removeClass('active');
    // });
    // 點擊 tab 切換圖片顯示
    $('li.itinerary_tab').on('click', function () {
        const tab = $(this);  // 當前被點擊的 tab
        const tab_img = $('.tab-pane img.itinerary_img[data-lock="' + tab.data('tab') + '"]');  // 這個 tab 對應的圖片

        // 1. 如果當前 tab 已經是 active，則隱藏圖片並移除 active 類
        if (tab.hasClass('lock')) {
            tab.removeClass('lock');
            tab_img.addClass('hidden');  // 隱藏當前 tab 的圖片
            $('#itinerary_tab_content .card')[0].scrollIntoView({ behavior: 'smooth', block: 'start' });  // 滾動到圖片

        } else {
            // 2. 否則，隱藏所有圖片
            $('.tab-pane img.itinerary_img').addClass('hidden');

            // 3. 顯示當前 tab 對應的圖片
            tab_img.removeClass('hidden');

            // 4. 移除所有 tab 的 active 類，然後給當前點擊的 tab 添加 active 類
            $('li.itinerary_tab').removeClass('lock');
            tab.addClass('lock');

            // 5. 滾動到當前圖片的位置
            tab_img[0].scrollIntoView({ behavior: 'smooth', block: 'center' });  // 滾動到圖片
        }
    });

// 點擊圖片時，隱藏圖片並移除 active 類
    $('.tab-pane img.itinerary_img').on('click', function () {
        const img = $(this);  // 當前被點擊的圖片
        img.addClass('hidden');  // 隱藏該圖片
        const tab = $('li.itinerary_tab[data-tab="' + img.data('lock') + '"]');  // 找到與圖片對應的 tab
        tab.removeClass('lock');  // 移除該 tab 的 active 類
        // 5. 滾動到當前圖片的位置
        $('#itinerary_tab_content .card')[0].scrollIntoView({ behavior: 'smooth', block: 'start' });  // 滾動到圖片
    });



//
// // 使用 jQuery 选择目标元素
//     const fadeElement = $('#targetElement');
//     const fadeElements = $('.fade-on-scroll');
//
// // 创建 IntersectionObserver 实例
//     const observer = new IntersectionObserver((entries, observer) => {
//         entries.forEach(entry => {
//
//             // 判断 B元素是否进入视口，且有 50% 进入视口
//             if (entry.isIntersecting && entry.intersectionRatio >= 0.2) {
//                 // 如果元素进入视口 50%，添加透明度和模糊效果
//                 fadeElements.addClass('opacity-25 backdrop-blur-sm'); // 添加透明度 25% 类 和 模糊效果
//                 fadeElements.removeClass('opacity-100'); // 移除完全透明类
//                 // console.log(2);
//             } else {
//                 // 元素离开视口，恢复默认效果
//                 fadeElements.addClass('opacity-100'); // 添加完全透明类
//                 fadeElements.removeClass('opacity-25 backdrop-blur-sm'); // 移除透明度和模糊效果
//                 // console.log('Element is not visible, reset opacity and blur');
//             }
//         });
//     }, {
//         threshold: 0.5 // 触发条件：元素进入视口 50%
//     });
//
// // 开始观察 B元素
//     observer.observe(document.querySelector('#targetElement'));

})
;

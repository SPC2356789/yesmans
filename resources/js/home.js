import $ from 'jquery';
$(document).ready(function() {
    var swiper = new Swiper(".swiper.carousel", {
        // spaceBetween: 30,
        centeredSlides: true,
        autoplay: {
            delay: 2500,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination.carousel",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next.carousel",
            prevEl: ".swiper-button-prev.carousel",
        },
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
    });
    // 使用 jQuery 选择目标元素
    const fadeElement = $('#targetElement');
    const fadeElements = $('.fade-on-scroll');

// 创建 IntersectionObserver 实例
    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            // 输出每次检查的信息
            console.log('isIntersecting:', entry.isIntersecting, 'intersectionRatio:', entry.intersectionRatio);

            // 判断 B元素是否进入视口，且有 50% 进入视口
            if (entry.isIntersecting && entry.intersectionRatio >= 0.2) {
                // 如果元素进入视口 50%，添加透明度和模糊效果
                fadeElements.addClass('opacity-25 backdrop-blur-sm'); // 添加透明度 25% 类 和 模糊效果
                fadeElements.removeClass('opacity-100'); // 移除完全透明类
                console.log(2);
            } else {
                // 元素离开视口，恢复默认效果
                fadeElements.addClass('opacity-100'); // 添加完全透明类
                fadeElements.removeClass('opacity-25 backdrop-blur-sm'); // 移除透明度和模糊效果
                console.log('Element is not visible, reset opacity and blur');
            }
        });
    }, {
        threshold: 0.5 // 触发条件：元素进入视口 50%
    });

// 开始观察 B元素
    observer.observe(document.querySelector('#targetElement'));

});

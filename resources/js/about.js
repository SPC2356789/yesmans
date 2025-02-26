import $ from 'jquery';

$(document).ready(function () {
    const windowHeight = $('#scrollable-content').height();
    const scrollThreshold = 0.98; // 滚动触发的阈值，0.8表示滚动到屏幕高度的80%时触发

    $('#scrollable-content').scroll(function () {
        const scrollTop = $(this).scrollTop();
        const scrollBottom = scrollTop + windowHeight;

        $(".fade-in").each(function () {
            const $element = $(this);
            const elementTop = $element.offset().top;

            if (elementTop < scrollBottom * scrollThreshold) {
                $element.addClass("animate");
            }
        });
    });

// 初始检查一次，以防页面刷新时已经在滚动阈值内
    $(window).scroll();
});

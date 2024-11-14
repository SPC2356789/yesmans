import $ from 'jquery';

$(document).ready(function () {
    var windowHeight = $(window).height();
    var scrollThreshold = 0.98; // 滚动触发的阈值，0.8表示滚动到屏幕高度的80%时触发

    $(window).scroll(function () {
        var scrollTop = $(this).scrollTop();
        var scrollBottom = scrollTop + windowHeight;

        $(".fade-in").each(function () {
            var $element = $(this);
            var elementTop = $element.offset().top;

            if (elementTop < scrollBottom * scrollThreshold) {
                $element.addClass("animate");
            }
        });
    });

// 初始检查一次，以防页面刷新时已经在滚动阈值内
    $(window).scroll();
});

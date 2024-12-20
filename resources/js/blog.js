import $ from 'jquery';


// 点击按钮时，切换该按钮所在父元素的菜单显示/隐藏
$(document).ready(function () {

    $('button.span_tag').click(function () {
        $(this).toggleClass('active');
    });

});


import $ from "jquery";
import Swal from 'sweetalert2'
import {Tool} from "./tools";

let startTime;

$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // 從 <meta> 中取得 CSRF Token
        }
    });
    $(document).on('click', '[name="getOrder"]', function () {
        getOrder();
    });
    cus_select() //調用選擇器行為
    ABCopy()
    Loading()
    $('#tripTerm').on('keypress', function (event) {
        // if (event.key === 'Enter') {
            event.preventDefault(); // 防止表單提交
            let term = $(this).val().trim(); // 獲取輸入值
            if (term) {
                let url = new URL('/itinerary', window.location.origin);
                url.searchParams.set('term', term); // 更新網址參數
                window.location.href = url.toString(); // 重新導向
            }
        // }
    });
    //關鍵字搜尋
    let debounceTimer;
    $('#Search').change('input', function () {
        let url = new URL(window.location.href);
        let params = url.searchParams;
        let tag = params.get("tag");
        let tagArray = tag ? tag.split("+") : [];
        clearTimeout(debounceTimer);
        $('[name="loadingIcon"]').toggleClass('hidden');
        Tool.search(tagArray)
    });
});

function getOrder() {
    Swal.fire({
        title: "<strong>HTML <u>example</u></strong>",
        icon: "info",
        html: `
    You can use <b>bold text</b>,
    <a href="#" autofocus>links</a>,
    and other HTML tags
  `,
        showCloseButton: true,
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonText: `
    <i class="fa fa-thumbs-up"></i> Great!
  `,
        confirmButtonAriaLabel: "Thumbs up, great!",
        cancelButtonText: `
    <i class="fa fa-thumbs-down"></i>
  `,
        cancelButtonAriaLabel: "Thumbs down"
    }).then(r => '');
}

function ABCopy() {
    const A = document.getElementById('mainNav');
    const B = document.getElementById('B');

    // 使用 ResizeObserver 監聽 A 元素大小變化
    const resizeObserver = new ResizeObserver(() => {
        requestAnimationFrame(() => {
            B.style.width = `${A.offsetWidth}px`;
            B.style.height = `${A.offsetHeight}px`;
        });
    });

    // 觀察 A 元素
    resizeObserver.observe(A);

    // 初始化 B 的大小
    requestAnimationFrame(() => {
        B.style.width = `${A.offsetWidth}px`;
        B.style.height = `${A.offsetHeight}px`;
    });
}


function cus_select(selector = '[aria-haspopup="true"]', submenu = '[role="menu"]', subMenuitem = '[role="menuitem"]') {
    // 點擊按鈕時，切換該按鈕所在父元素的菜單顯示/隱藏
    $(document).on('click', selector, function (event) {
        event.stopPropagation(); // 防止事件冒泡到 document，導致立即關閉

        const $button = $(this);
        const $menu = $button.next(submenu);

        // 隱藏其他菜單
        $(submenu).not($menu).addClass('hidden');

        // 切換當前菜單的顯示/隱藏
        $menu.toggleClass('hidden');

        // 更新按鈕的 aria-expanded 屬性
        const expanded = $button.attr('aria-expanded') === 'true' ? 'false' : 'true';
        $button.attr('aria-expanded', expanded);

        // 如果有選中的項目，給它添加 active 樣式
        const selectedText = $button.attr('data-select');
        $menu.find(subMenuitem).each(function () {
            if ($(this).text().trim() === selectedText) {
                $menu.find(subMenuitem).removeClass('active');
                $(this).addClass('active');
            }
        });
    });

    // 如果菜單項被點擊，隱藏該菜單
    $(document).on('click', subMenuitem, function () {
        const $menu = $(this).closest(submenu);

        $menu.addClass('hidden');
        const sortText = $(this).text().trim();
        $menu.prev(selector).attr('data-select', sortText)
            .find('span')
            .text(sortText);

        $menu.prev(selector).attr('aria-expanded', 'false');
    });

    // 點擊框框外時隱藏所有選單
    $(document).on('click', function (event) {
        if (!$(event.target).closest(selector).length && !$(event.target).closest(submenu).length) {
            $(submenu).addClass('hidden');
            $(selector).attr('aria-expanded', 'false');
        }
    });
}


function Loading() {
    startTime = Date.now();

    const yesmanContainer = document.getElementById('yesmanContainer');
    const progressBar = document.getElementById('progressBar');
    const overlay = document.querySelector('.overlay');

    function adjustProgressBar() {
        const containerWidth = yesmanContainer.offsetWidth;
        progressBar.parentElement.style.width = containerWidth + 'px';
    }

    function disableScroll() {
        document.body.style.overflow = 'hidden';
    }

    function enableScroll() {
        document.body.style.overflow = '';
    }

    adjustProgressBar();
    window.addEventListener('resize', adjustProgressBar);

    function startProgressBar(totalDuration, callback) {
        let progress = 5;
        const targetProgress = 100;
        const increment = targetProgress / (totalDuration / 17); // 每帧大约更新一次

        // 显示遮罩层
        overlay.style.display = 'block';
        disableScroll(); // 禁用滚动

        function updateProgress() {
            if (progress < targetProgress) {
                progress += increment;
                progressBar.style.width = Math.min(progress, targetProgress) + '%';
                progressBar.textContent = Math.round(progress) + '%';
                requestAnimationFrame(updateProgress);
            } else {
                progressBar.style.width = targetProgress + '%';
                progressBar.textContent = targetProgress + '%';
                setTimeout(function () {
                    overlay.style.display = 'none'; // 淡出遮罩
                    document.querySelector('.progress-container').style.display = 'none'; // 淡出进度条
                    yesmanContainer.style.display = 'none'; // 淡出 YESMAN
                    enableScroll(); // 启用滚动

                    // 動畫
                    const element = document.getElementById('hover-image');

                    element.classList.remove('opacity-0', 'translate-y-5'); // 移除透明度和偏移
                    element.classList.add('opacity-100', 'translate-y-0');  // 添加完全不透明並移動回原位
                }, 500);

            }
        }

        requestAnimationFrame(updateProgress);
    }

    const setTime = 2000; // 设置最小时间
    const endTime = Date.now();
    const loadTime = endTime - startTime;


    const loadTimes = loadTime > setTime ? loadTime : setTime;

    // 启动进度条
    startProgressBar(setTime);

}


import $ from "jquery";
import Swal from 'sweetalert2'
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
    });
}

function ABCopy(a, b) {
    const A = document.getElementById('mainNav');
    const B = document.getElementById('B');

    // 使用 ResizeObserver 監聽 A 元素大小變化
    const resizeObserver = new ResizeObserver(() => {
        B.style.width = `${A.offsetWidth}px`;
        B.style.height = `${A.offsetHeight}px`;
    });

    // 觀察 A 元素
    resizeObserver.observe(A);

    // 初始化 B 的大小
    B.style.width = `${A.offsetWidth}px`;
    B.style.height = `${A.offsetHeight}px`;

}

function cus_select(selector = '[aria-haspopup="true"]', submenu = '[role="menu"]', submenuitem = '[role="menuitem"]') {
    // 點擊按鈕時，切換該按鈕所在父元素的菜單顯示/隱藏

    $(document).on('click', selector, function () {
        const $button = $(this);  // 當前點擊的按鈕
        const $menu = $button.next(submenu);  // 獲取該按鈕的菜單

        // 隱藏其他菜單
        $(submenu).not($menu).addClass('hidden');  // 隱藏其他菜單

        // 切換當前菜單的顯示/隱藏
        $menu.toggleClass('hidden');

        // 更新按鈕的 aria-expanded 屬性
        const expanded = $button.attr('aria-expanded') === 'true' ? 'false' : 'true';
        $button.attr('aria-expanded', expanded);


        // 如果有選中的項目，給它添加 active 樣式
        const selectedText = $button.attr('data-select');
        $menu.find(submenuitem).each(function () {
            if ($(this).text().trim() === selectedText) {
                $menu.find(submenuitem).removeClass('active');
                $(this).addClass('active'); // 添加 active 樣式
            }
        });
    });

// 如果菜單項被點擊，隱藏該菜單

    $(document).on('click', submenuitem, function () {
        const $menu = $(this).closest('[role="menu"]');  // 獲取該菜單

        $menu.addClass('hidden');  // 隱藏該菜單
        const sortText = $(this).text().trim(); // 使用 text() 來獲取選擇的排序項目的文字
        $menu.prev('[aria-haspopup="true"]').attr('data-select', sortText)  // 更新 data-select 屬性
            .find('span')  // 找到 <span> 標籤
            .text(sortText);  // 更新按鈕的顯示文字

        $menu.prev('[aria-haspopup="true"]').attr('aria-expanded', 'false');  // 更新 aria-expanded 屬性   });

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


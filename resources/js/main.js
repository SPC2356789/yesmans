let startTime;
document.addEventListener('DOMContentLoaded', function () {


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

    function startProgressBar(totalDuration,callback) {
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
                }, 1000);

            }
        }

        requestAnimationFrame(updateProgress);
    }
    const setTime = 1500; // 设置最小时间
    const endTime = Date.now();
    const loadTime = endTime - startTime;

    // console.log(`网页加载时间: ${loadTime} 毫秒`);
    const loadTimes = loadTime > setTime ? loadTime : setTime;
    // console.log(`进度条使用时间: ${loadTimes} 毫秒`);

    // 启动进度条
    startProgressBar(setTime);

    // window.addEventListener('load', function () {
    //     // const setTime = 2000; // 设置最小时间
    //     const endTime = Date.now();
    //     const loadTime = endTime - startTime;
    //
    //     // console.log(`网页加载时间: ${loadTime} 毫秒`);
    //     const loadTimes = loadTime > setTime ? loadTime : setTime;
    //     // console.log(`进度条使用时间: ${loadTimes} 毫秒`);
    //
    //     // 启动进度条
    //     startProgressBar(loadTimes);
    //
    // });



    // const brandLink = document.getElementById('brand-link');
    // const hoverImage = document.getElementById('hover-image');
    // function LogoStart(){
    //     hoverImage.classList.add('fade-in');
    // }
    //
    // let isDesktop = window.matchMedia('(min-width: 768px)').matches;
    //
    // // 添加或移除事件监听器的函数
    // function toggleHoverListeners() {
    //     if (isDesktop) {
    //         brandLink.addEventListener('mouseenter', handleMouseEnter);
    //         brandLink.addEventListener('mouseleave', handleMouseLeave);
    //     } else {
    //         brandLink.removeEventListener('mouseenter', handleMouseEnter);
    //         brandLink.removeEventListener('mouseleave', handleMouseLeave);
    //
    //     }
    // }
    //
    // // 处理鼠标进入和离开的函数
    // function handleMouseEnter() {
    //     if (hoverImage) {
    //         hoverImage.classList.remove('opacity-0', 'fade-out');
    //         hoverImage.classList.add('fade-in');
    //     }
    // }
    //
    // function handleMouseLeave() {
    //     if (hoverImage) {
    //         hoverImage.classList.remove('fade-in');
    //         hoverImage.classList.add('fade-out');
    //     }
    // }
    //
    // // 初始调用
    // toggleHoverListeners();
    //
    // // 监听窗口大小变化
    // window.addEventListener('resize', () => {
    //     const newIsDesktop = window.matchMedia('(min-width: 768px)').matches;
    //     if (newIsDesktop !== isDesktop) {
    //         isDesktop = newIsDesktop;
    //         toggleHoverListeners();
    //     }
    // });


});

// var url = ;

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

    function startProgressBar(totalDuration) {
        let progress = 0;
        const targetProgress = 100;
        const increment = targetProgress / (totalDuration / 10); // 每帧大约更新一次

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
                    yesmanContainer.style.display = 'none'; // 淡出 YESMAN
                    document.querySelector('.progress-container').style.display = 'none'; // 淡出进度条
                    enableScroll(); // 启用滚动
                }, 500);
            }
        }

        requestAnimationFrame(updateProgress);
    }
    const setTime = 2000; // 设置最小时间
    window.addEventListener('load', function () {
        // const setTime = 2000; // 设置最小时间
        const endTime = Date.now();
        const loadTime = endTime - startTime;

        console.log(`网页加载时间: ${loadTime} 毫秒`);
        const loadTimes = loadTime > setTime ? loadTime : setTime;
        console.log(`进度条使用时间: ${loadTimes} 毫秒`);

        // 启动进度条
        startProgressBar(loadTimes);
    });

    // 在页面加载时自动启动进度条
    // startProgressBar(setTime); // 设定进度条持续时间，例如 2000 毫秒
});

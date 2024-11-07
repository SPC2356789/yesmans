<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intersection Observer Test</title>
    <style>
        /* 添加一些样式，使页面有足够的高度可滚动 */
        .content {
            height: 1500px; /* 页面高度 */
        }

        #targetElement {
            margin-top: 1200px; /* 设置目标元素的位置 */
            height: 200px; /* 目标元素高度 */
            background-color: lightblue;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
            color: black;
        }
    </style>
</head>
<body>

<div class="content">
    <p>一些内容...</p>

    <!-- 目标元素 B -->
    <div id="targetElement">
        B元素
    </div>

    <p>更多内容...</p>
</div>

<script>
    // 获取 B元素
    const fadeElement = document.querySelector('#targetElement');

    // 创建 IntersectionObserver 实例
    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            // 输出每次检查的信息
            console.log('isIntersecting:', entry.isIntersecting, 'intersectionRatio:', entry.intersectionRatio);

            // 判断 B元素是否进入视口，且有 50% 进入视口
            if (entry.isIntersecting && entry.intersectionRatio >= 0.5) {
                // 当 B元素进入视口 50% 时，输出 1
                console.log(1);

                // 停止观察该元素（如果只需要触发一次，可以取消观察）
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.5 // 触发条件：元素进入视口 50%
    });

    // 开始观察 B元素
    observer.observe(fadeElement);
</script>

</body>
</html>

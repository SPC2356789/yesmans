
document.addEventListener("DOMContentLoaded", function () {
    const sidebarItems = document.querySelectorAll(".fi-sidebar-nav .fi-sidebar-item a");

    // 取得當前網址的 `/admin/XXX`
    const currentPathParts = window.location.pathname.split("/");
    const currentBasePath = `/${currentPathParts[1]}/${currentPathParts[2]}`; // `/admin/XXX`

    sidebarItems.forEach(a => {
        const sidebarHrefParts = new URL(a.href, window.location.origin).pathname.split("/");
        const sidebarBasePath = `/${sidebarHrefParts[1]}/${sidebarHrefParts[2]}`; // `/admin/XXX`

        if (currentBasePath === sidebarBasePath) {
            // 修改 SVG 樣式
            const svg = a.querySelector("svg");
            if (svg) {
                svg.classList.remove("dark:text-gray-500");
                svg.classList.add("dark:text-primary-400");
            }

            // 修改 span 樣式
            const span = a.querySelector("span");
            if (span) {
                span.classList.remove("dark:text-gray-200");
                span.classList.add("dark:text-primary-400");
            }
        }
    });
});

document.addEventListener('DOMContentLoaded', () => {
    window.copyToClipboard = function (button) {
        let text = button.getAttribute('data-copy'); // 從 data-copy 獲取數據
        // 將 HTML 實體解碼（例如 &quot; 轉回 "）
        const textarea = document.createElement('textarea');
        textarea.innerHTML = text;
        text = textarea.value;
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text)
                .then(() => {
                    // alert('已複製到剪貼板！');
                    // console.log('已成功複製到剪貼簿');
                })
                .catch(err => {
                    console.error('複製失敗', err);
                });
        } else {
            // alert('瀏覽器不支持剪貼板操作，請手動複製：' + text);
        }
    };
});
document.addEventListener('livewire:init', () => {
    Livewire.on('copy-to-clipboard', (event) => {
        const content = event.content;
        const successMessage = event.successMessage;
        const failedMessage = event.failedMessage;

        navigator.clipboard.writeText(content).then(() => {
            console.log('已複製到剪貼簿');
            alert(successMessage);
        }).catch(err => {
            console.error('複製失敗', err);
            alert(failedMessage + '\n請手動複製內容：' + content);
        });
    });
});

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

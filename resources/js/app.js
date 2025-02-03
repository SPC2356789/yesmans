
import './main.js'

import '../newLayout/js/scripts.js'
// import './home.js'
// import './about.js'
// import './blog.js'

// 自動載入與頁面對應的 JS
const pages = import.meta.glob('./*.js');

const page = document.body.dataset.page;
if (page && pages[`./${page}.js`]) {
    pages[`./${page}.js`]().then(module => {
        console.log(`${page}.js 已載入`);
    });
}


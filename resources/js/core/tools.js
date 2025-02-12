import $ from "jquery";

import axios from "axios";

export const Tool = {
    search:
        function (tag) {
            const page = $('#page-top').data('page')
            const obj = $('#Search')
            const key = obj.data('key')
            let searchTerm = obj.val()

            axios.patch('/' + page + '/' + key, {
                term: Tool.sanitizeInput(searchTerm),
                key: key ?? '',
                tag: tag ?? '',
            })
                .then(response => {
                    setTimeout(() => {
                        $('#search-results').html(response.data);
                    }, 500); // 延遲 1000 毫秒（1秒）
                })
                .catch(error => {
                    // console.error('搜尋時發生錯誤：', error);
                    $('#search-results').html('<div>發生不可預期的錯誤，請聯絡管理員。</div>');
                });


        },
    sanitizeInput:

        function (input) {
            return input.replace(/[^a-zA-Z0-9\u4e00-\u9fa5\s]/g, ''); // 只允許字母、數字、中文字符和空白

        }

    ,
    toggleUrlParameter: function (obj, param, delimiter = '', replace, isMultiple = true, isReload = false) {
        // 切換元素的 active 類
        obj.toggleClass('active');

        // 取得目前的 URL 和查詢參數
        let url = new URL(window.location.href);
        let params = url.searchParams;
        let value = obj.data('value');

// 如果 `replace` 不為空，則執行 replace
        if (replace) {
            value = value.replace(replace, '');
        }
        // 判斷是否為多個值處理
        let existingValues = isMultiple ? (params.has(param) ? params.get(param).split(delimiter) : []) : [];

        // 判斷是否已經存在該值
        if (existingValues.includes(value.toString())) {
            // 如果已存在，則移除該值
            existingValues = existingValues.filter(v => v !== value.toString());
        } else {
            // 如果不存在，則加入
            existingValues.push(value.toString());
        }

        // 如果是多個值處理
        if (isMultiple) {
            // 更新網址參數
            if (existingValues.length > 0) {
                params.set(param, existingValues.join(delimiter));  // 轉換為 `+` 分隔的字串
            } else {
                params.delete(param);  // 如果沒有值了，就移除參數
            }
        } else {
            // 單一值處理
            if (existingValues.length > 0) {
                params.set(param, existingValues[0]);
            } else {
                params.delete(param);  // 如果沒有值了，就移除參數
            }
        }
        params.delete('page');//只要更新就移除page
        window.history.pushState({}, '', url.toString());
        // 如果 isReload 為 true，刷新頁面
        if (isReload) {
            window.location.reload();  // 刷新頁面
        }
    }
    ,



}

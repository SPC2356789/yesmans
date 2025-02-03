import $ from "jquery";
import TomSelect from "tom-select"; // 這會加載帶插件的完整版本
import 'tom-select/dist/css/tom-select.css';

export const Tool = {
    loading: function () {
        return `<div class="flex justify-center items-center  gap-1 " >
    <div class="w-6 h-6 border-4 border-gray-300 border-t-yes-major rounded-full animate-spin"></div>
    探索中
</div>
`
    },
    sanitizeInput: function (input) {
        return input.replace(/[^a-zA-Z0-9\u4e00-\u9fa5\s]/g, ''); // 只允許字母、數字、中文字符和空白

    },
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
        window.history.pushState({}, '', url.toString());
        // 如果 isReload 為 true，刷新頁面
        if (isReload) {
            window.location.reload();  // 刷新頁面
        }
    },
    checkbox: function (Id) {
        const select = new TomSelect(Id, {
            plugins: ['checkbox_options'], // 啟用 checkbox_options 插件
            searchField: [],  // 禁用搜索框
            create: false,  // 禁用用户输入
        });

        // 取得所有選項的值
        const allValues = $(Id).map(function () {
            return this.value;
        }).get();

        // 設置初始選中的值（全選）
        select.setValue(allValues);
    }
    ,
    TomSelect: function (Id, optionsData) {
        // 初始化 TomSelect 实例，并填充选项
        const select = new TomSelect(Id, {
            maxOptions: 300,
            options: optionsData,  // 使用传入的国家列表数据
            create: false,  // 禁用用户创建新选项
            copyClassesToDropdown: false,
            // valueField: 'value',
            // labelField: 'label',
            sortField: 'value',
            searchField: 'label',
            render: {
                item: function (data) {
                    // 获取标签，如果没有就使用 nativeCommonName
                    const label = data.translations && data.translations.zho && data.translations.zho.common
                        ? data.translations.zho.common
                        : data.nativeCommonName;

                    // 获取图片链接
                    const imageUrl = data.imageUrl || 'default-image.jpg'; // 使用默认图片
                    return `<div class="">
<img class="w-6 mr-2" src="${imageUrl}" alt="${data.label} "loading="lazy" />
<span> ${data.label}</span></div>`;
                },
                option: function (data) {
                    // 获取图片链接
                    const imageUrl = data.imageUrl || 'default-image.jpg';  // 使用默认图片
                    return `<div class="flex flex-row items-center w-full"><img  class="w-6 mr-2" src="${imageUrl}" alt="${data.label}" loading="lazy"/>${data.label}</div>`;
                }
            }
        });
        // console.log(optionsData);
        // const optionCount = optionsData.length;
        //
        // console.log(`选项的总数是: ${optionCount}`);
        // 默认选中 value 为 'TW' 的选项
        select.setValue('TWN');
    }
    ,


}

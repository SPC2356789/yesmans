import $ from "jquery";
import TomSelect from "tom-select"; // 這會加載帶插件的完整版本
import 'tom-select/dist/css/tom-select.css';

export const Tool = {

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
    },
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
<img class="w-6 mr-2" src="${imageUrl}" alt="${data.label}" />
<span> ${data.label}</span
></div>`;
                },
                option: function (data) {
                    // 获取图片链接
                    const imageUrl = data.imageUrl || 'default-image.jpg';  // 使用默认图片
                    return `<div class="flex flex-row items-center w-full"><img  class="w-6 mr-2" src="${imageUrl}" alt="${data.label}" />${data.label}</div>`;
                }
            }
        });
        // console.log(optionsData);
        // const optionCount = optionsData.length;
        //
        // console.log(`选项的总数是: ${optionCount}`);
        // 默认选中 value 为 'TW' 的选项
        select.setValue('TWN');
    },


}

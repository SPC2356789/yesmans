import $ from 'jquery';
import {Tool} from './tools';
import Swal from 'sweetalert2'
import axios from "axios";

$(document).ready(function () {
    Tool.checkbox('#trip_month');
    updateActiveState('tag', '+',)
    $('button.span_tag').click(function () {
        Tool.toggleUrlParameter($(this), 'tag', '+', 'itry_tag_',);  // 'tag' 是你要更新的 URL 參數
        updateActiveState('tag', '+',)
    });

    $('.trip_btn_apply').click(function () {
        const timeContent = $(this).data('time');
        const timeHref = $(this).attr('href');
        let links = '';
        $.each(timeContent, function (index, value) {
            const startDate = new Date(value.date_start).toLocaleDateString();
            const endDate = new Date(value.date_end).toLocaleDateString();

            // 創建每個日期範圍的連結
            links += `<a href="${timeHref}?trip_time=${value.uuid}" class="text-start" data-mould="${value.mould_id}" >${startDate} - ${endDate}</a>`;
            // 你可以在這裡做一些處理，比如生成 HTML 或者其他操作
        });
        let time = `<div class="flex flex-col w-full">` + links + `</div>`;
        console.log(timeContent)
        // 顯示 SweetAlert
        Swal.fire({
            // title: "選擇未來一個月的月份，包含當月",
            // icon: "info",
            html: time // 在這裡將 data-tip 放進提示框的文本內容

        });
    });

    //關鍵字搜尋
    const Search = $('#Search')
    const key = Search.data('key')
    let debounceTimer;
    Search.change('input', function () {
        // console.log();  // 确保能在控制台看到更新的值
        clearTimeout(debounceTimer);
        $('#loading-icon').html(
            Tool.loading(),//等待圖示
        );
        Search(key, $(this).val());
    });

});


function Search(key, searchTerm) {
    axios.patch('/itinerary/' + key, {
        term: Tool.sanitizeInput(searchTerm),
        key: key
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
}
function updateActiveState(param, delimiter, isMultiple = true) {
    let url = new URL(window.location.href);
    let params = url.searchParams;
    let values = isMultiple ? (params.has(param) ? params.get(param).split(delimiter) : []) : [];
    // 遍歷所有相關的元素，更新它們的 active 類
    $('.span_tag[data-value]').each(function () {
        let obj = $(this);
        let value = obj.data('value').replace('itry_tag_', '');  // 假設是 `itry_tag_` 開頭的數據

        // 判斷元素的 data-value 是否在 values 中
        if (values.includes(value.toString())) {
            obj.addClass('active');  // 如果在列表中，則添加 active 類
        } else {
            obj.removeClass('active');  // 如果不在列表中，則移除 active 類
        }
    });
}

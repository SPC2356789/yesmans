import $ from 'jquery';
import {Tool} from './tools';
import Swal from 'sweetalert2'

$(document).ready(function () {
    Tool.checkbox('#trip_month');
    //標籤行為
    $('button.span_tag').click(function () {
        const obj = $(this);
        tagChange(obj)

    });
    $('[for="trip_month"]').click(function () {
        Swal.fire({
            title: "選擇未來一個月的月份，包含當月",
            icon: "info",
        })
    });

    $('.trip_btn_apply').click(function () {
        const timeContent = $(this).data('time');
        const timeHref = $(this).attr('href');
        let links = '';
        $.each(timeContent, function (index, value) {
            const startDate = new Date(value.date_start).toLocaleDateString();
            const endDate = new Date(value.date_end).toLocaleDateString();

            // 創建每個日期範圍的連結
            links += `<a href="${timeHref}?trip_time=${value.id}" class="text-start" data-id="${value.mould_id}" >${startDate} - ${endDate}</a>`;
            // 你可以在這裡做一些處理，比如生成 HTML 或者其他操作
        });
        let time = `<div class="flex flex-col w-full">` + links + `</div>`;
        console.log(timeContent)
        // 顯示 SweetAlert
        Swal.fire({
            title: "選擇未來一個月的月份，包含當月",
            icon: "info",
            html: time // 在這裡將 data-tip 放進提示框的文本內容

        });
    });


    function tagChange(obj) {
        obj.toggleClass('active');
        let url = new URL(window.location.href);
        let params = url.searchParams;
        let value = obj.data('value').replace('itry_tag_', '');
        ; // 取得 data-value 屬性


// 取得目前 URL 中 `tag` 的值（可能是多個）
        let existingValues = params.get('tag') ? params.get('tag').split('+') : [];

        if (existingValues.includes(value.toString())) {
            // 如果已存在，則移除該值
            existingValues = existingValues.filter(v => v !== value.toString());
        } else {
            // 如果不存在，則加入
            existingValues.push(value.toString());
        }

// 更新網址參數
        if (existingValues.length > 0) {
            params.set('tag', existingValues.join('+')); // 轉換為 `+` 分隔的字串
        } else {
            params.delete('tag'); // 如果沒有值了，就移除參數
        }
        window.history.pushState({}, '', url.toString()); // 更新網址，不刷新頁面

    }
});

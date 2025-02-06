import $ from 'jquery';
import {Tool} from './core/tools';
import Swal from 'sweetalert2'


$(document).ready(function () {
    Tool.checkbox('#trip_month');
    updateActiveState('tag', '+',)

    $('button.span_tag').click(function () {
        Tool.toggleUrlParameter($(this), 'tag', '+', '',);  // 'tag' 是你要更新的 URL 參數
        updateActiveState('tag', '+',)

        tag()
    });
    $(document).on('click', '.trip_btn_apply', function () {
        const timeContent = $(this).data('time');
        const timeHref = $(this).attr('href');
        let links = '';
        $.each(timeContent, function (index, value) {
            // 創建每個日期範圍的連結
            links += `<a href="${timeHref}?trip_time=${value.uuid}" class="flex flex-col gap-0.5 " data-mould="${value.mould_id}" ><span>${value.date}</span><span>名額:${value.quota} 已報名:${value.applied_count} </span></a>`;
            // 你可以在這裡做一些處理，比如生成 HTML 或者其他操作
        });
        let time = `<div class="flexflex-col w-full text-start gap-2.5 xxx:text-xs ss:text-base xs:text-sm md:text-lg">` + links + `</div>`;
        // console.log(timeContent)
        // 顯示 SweetAlert
        Swal.fire({
            // title: "選擇未來一個月的月份，包含當月",
            // icon: "info",
            html: time, // 在這裡將 data-tip 放進提示框的文本內容
            confirmButtonText: "探索其他行程", // 按鈕文字
            customClass: {
                confirmButton: "trip_card_Swal_confirm" // 設定自訂 CSS 類別
            }
            // customClass: {
            //     confirmButton: "bg-transparent text-yes-major border-2 border-black hover:border-yes-major hover:bg-yes-major hover:text-white !important"
            // }
        });
    });


});


function tag() {
    let url = new URL(window.location.href);
    let params = url.searchParams;
    let tag = params.get("tag");
    let tagArray = tag ? tag.split("+") : [];
    Tool.search(tagArray)
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

import $ from 'jquery';
import {Tool} from './core/tools';
import Swal from 'sweetalert2';
import axios from 'axios'


$(document).ready(function () {
    //更新標籤行為 是否浮動報名層
    let $signUpBtn = $("[name='Itinerary_Sign_Up']");
    if (isMobile()) {
        $signUpBtn.removeClass("md:hidden"); // 手機顯示
    } else {
        // $signUpBtn.addClass("md:hidden"); // 電腦隱藏
    }

    updateActiveState('tag', ',',)//更新標籤行為

    $('button.span_tag').click(function () {
        Tool.toggleUrlParameter($(this), 'tag', ',', '',);  // 'tag' 是你要更新的 URL 參數
        updateActiveState('tag', ',',)

        tag()
    });
    $(document).on('click', '.trip_btn_apply', function () {
        const timeContent = $(this).data('time');
        const timeHref = $(this).attr('href');
        let links = '';
        $.each(timeContent, function (index, value) {
            // 創建每個日期範圍的連結
            links += `<a href="${timeHref}?trip_time=${value.uuid}" class="flex flex-col gap-0.5 " data-mould="${value.mould_id}" ><span>${value.dateAll}</span><span>名額:${value.quota} 已報名:${value.applied_count} </span></a>`;
            // 你可以在這裡做一些處理，比如生成 HTML 或者其他操作
        });
        let time = `<div class="flexflex-col w-full text-start gap-2.5 xxx:text-xs ss:text-base xs:text-sm md:text-lg max-h-[400px] overflow-y-auto">` + links + `</div>`;
        // console.log(timeContent)
        // 顯示 SweetAlert
        Swal.fire({
            html: time, // 在這裡將 data-tip 放進提示框的文本內容
            confirmButtonText: "探索其他行程", // 按鈕文字
            customClass: {

                confirmButton: "trip_card_Swal_confirm" // 設定自訂 CSS 類別
            }
        });
    });


});


function tag() {
    let url = new URL(window.location.href);
    let params = url.searchParams;
    let tag = params.get("tag");
    let tagArray = tag ? tag.split(",") : [];
    Tool.search(tagArray)
}

function updateActiveState(param, delimiter, isMultiple = true) {
    let url = new URL(window.location.href);
    let params = url.searchParams;
    let values = isMultiple ? (params.has(param) ? params.get(param).split(delimiter) : []) : [];
    // 遍歷所有相關的元素，更新它們的 active 類
    $('.span_tag[data-value]').each(function () {
        let obj = $(this);
        let value = obj.data('value');  // 假設是 `itry_tag_` 開頭的數據

        // 判斷元素的 data-value 是否在 values 中
        if (values.includes(value.toString())) {
            obj.addClass('active');  // 如果在列表中，則添加 active 類
        } else {
            obj.removeClass('active');  // 如果不在列表中，則移除 active 類
        }
    });
}
function isMobile() {
    // 瀏覽器是否支援 `navigator.userAgent`（確保未來不會報錯）
    if (!navigator.userAgent) {
        console.warn("navigator.userAgent 停止支援");
        return false; // 預設為桌機
    }

    return /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
}

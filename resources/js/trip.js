import $ from 'jquery';

import Swiper from 'swiper';
import {FreeMode, Navigation, Thumbs} from 'swiper/modules';
// Import Swiper styles
import 'swiper/css';

import 'swiper/css/free-mode';
import 'swiper/css/navigation';
import 'swiper/css/thumbs';
import {Tool} from './core/tools';
import tripCountry from '../lib/trip_country.json';
import axios from 'axios';

import { createApp } from 'vue';
import FormClone from '../views/Itinerary/FormClone.vue';


const tripElement = document.getElementById('trip_from');
const tripData = JSON.parse(tripElement.dataset.trip_times);
// 創建 Vue 實例並註冊全局方法
const app = createApp(FormClone, {
    data: tripData,
    CountryData:getCountry()
});
app.mount('#trip_from');


import "choices.js/public/assets/styles/choices.css";

$(document).ready(function () {

    swiper()
    getCountry()//初始化國籍選擇
    applyAgree()//同意書

    $(document).on('click', 'li[name="trip_times"]', function () {    //變換出團時間
        let $this = $(this);  // 儲存 $(this) 在變數中
        let tripTimeValue = $this.data('value');  // Get the value of the clicked item

        axios.post('/update-trip-time', {
            trip_time: tripTimeValue,
        })
            .then(function (response) {
                console.log('Session updated successfully:', response.data);
                Tool.toggleUrlParameter($this, 'trip_time', '', null, false, true);  // 'tag' 是你要更新的 URL 參數
            })
            .catch(function (error) {
                console.error('Failed to update session:', error);
            });

    });
});





function swiper() {
    // 輪播
    const swiper = new Swiper(".btm_trip", {
        loop: true,
        slidesPerView: 3,
        spaceBetween: 10,
        pagination: {
            clickable: true,
        },
        breakpoints: {
            640: {
                slidesPerView: 3, // 小螢幕顯示一個滑塊
            },
            768: {
                slidesPerView: 4, // 中等螢幕顯示兩個滑塊
            },
            1024: {
                slidesPerView: 4, // 大螢幕顯示四個滑塊
            },
        },
        freeMode: true,
        watchSlidesProgress: true,
        modules: [Navigation, FreeMode, Thumbs],  // 引入模組
    });
    const swiper2 = new Swiper(".top_trip", {
        loop: true,
        spaceBetween: 10,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        thumbs: {
            swiper: swiper,
        },
        modules: [Navigation, FreeMode, Thumbs],  // 引入模組
    });
}


function applyAgree() {
    const $agreementContainer = $('.agree-ctn');
    const $agreementButton = $('[name="agree_btn"]');
    const $checkbox = $('[name="agree_btn"] .checkbox');
    const $checkboxLabel = $('[for="agreeCheckbox"]');
    const $tripFrom = $('#trip_from');

    $agreementContainer.on('scroll', function () {
        const scrollThreshold = 10;  // 設定誤差範圍（可以調整）
        if ($agreementContainer[0].scrollHeight - $agreementContainer.scrollTop() - $agreementContainer.outerHeight() <= scrollThreshold) {
            $tripFrom.removeClass('hidden');  // 勾選 checkbox
            $checkbox.addClass('active');
            $checkboxLabel.addClass('active');
        }
    });
    $(document).on('change', '#agreeCheckbox', function () {

        $tripFrom.toggleClass('hidden');  // 勾選 checkbox
        // $tripFrom.removeClass('hidden');  // 勾選 checkbox
        // console.log(1);
    });


    // 聚焦行為IOS不支持
    // $('[name="signupBtn"]').on('click', function () {
    //     $agreementButton.focus();
    // });
    //我要報名滾動到
// 讓 `[name="signupBtn"]` 點擊時，滑動到 `$agreementButton`
    $('[name="signupBtn"]').on('click', function () {
        $agreementButton[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

// 讓 `[name="agree_btn"]` 點擊時，勾選 checkbox 並觸發變化
    $agreementButton.on('click', function () {
        $('#agreeCheckbox').change(); // 觸發 change 事件
        $checkbox.toggleClass('active');
        $checkboxLabel.toggleClass('active');
    });

}

function getCountry() {
    try {
        const countryList = tripCountry.map(country => {
            let nativeCommonName = '';
            if (country.name.nativeName) {
                const firstNative = Object.values(country.name.nativeName)[0];
                nativeCommonName = firstNative.common;
            }
            let labelText = '';
            if (country.translations && country.translations.zho && country.translations.zho.common) {
                labelText = country.translations.zho.common;
            } else if (country.name.nativeName && country.name.nativeName.zho) {
                labelText = country.name.nativeName.zho.common;
            } else {
                labelText = country.name.common; // 如果找不到翻译，就用英文名称
            }

            return {
                value: country.cca3,
                label: `    <div class="flex flex-row items-center w-full">
        <img class="w-6 mr-2" src="${country.flags.png}" alt="${labelText}" loading="lazy" />
          <span>${labelText} <br>${country.name.common} </span>
</div>

    </div>`
            };
        });

        return countryList;

    } catch (error) {
        // console.error('Error processing country data:', error);
    }
}

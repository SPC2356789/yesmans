import $ from 'jquery';

import Swiper from 'swiper';
import {FreeMode, Navigation, Thumbs} from 'swiper/modules';
// Import Swiper styles
import 'swiper/css';

import 'swiper/css/free-mode';
import 'swiper/css/navigation';
import 'swiper/css/thumbs';
import {Tool} from './tools';

$(document).ready(function () {

    getCountry()//國籍選擇
    applyAgree()//同意書

    $(document).on('click', '#setCountry', function () {
        if (!$('#setCountry').hasClass('specific-class')) {
            console.log('NO')
            getCountry(); // 國籍選擇
        } else {
            console.log('有元素不執行')
        }
    });
    $(document).on('click', 'div[name="add_number"]', function () {
        const cloneItem = $('[name="apply_trip"]').last();
        // 克隆報名資訊表單
        const clonedForm = cloneItem.clone();

        // 獲取最後一個表單的 data-number 值並加 1
        const currentNumber = parseInt(cloneItem.data('number'));
        const newNumber = currentNumber + 1;

        // 設定新的 data-number 值
        clonedForm.attr('data-number', newNumber);

        // 更新表單內的序號顯示
        clonedForm.find('[name="apply_title"]').text('序號' + newNumber);
        clonedForm.find('[name="remove_number"]').removeClass('hidden');
        // 修改克隆表單中的所有 ID 屬性，並更新對應的 label 的 for 屬性
        clonedForm.find('[id]').each(function () {
            const originalId = $(this).attr('id');
            const newId = originalId + '_' + newNumber; // 使用當前時間戳來保證唯一性
            $(this).attr('id', newId);
            // 同時更新對應的 label 的 for 屬性
            clonedForm.find('label[for="' + originalId + '"]').attr('for', newId);
        });
        // 清空表單中的輸入欄位內容
        clonedForm.find('input').val('');
        clonedForm.find('select').prop('selectedIndex', 0); // 重設下拉選單
        //
        // // 將克隆的表單加到頁面中
        cloneItem.after(clonedForm);
    });
    $(document).on('click', '[name="remove_number"]', function () {
        $(this).closest('[name="apply_trip"]').remove();
    });


    // Tool.TomSelect('#setCountry');  // 样式化并传递国家列表数据

    // 輪播
    const swiper = new Swiper(".btm_trip", {
        loop: true,
        spaceBetween: 10,
        slidesPerView: 4,
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


});

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
        console.log(1);
    });
    // 聚焦行為
    $(document).on('click', '#signupBtn', function () {
        $agreementButton.focus();
    });
    $(document).on('click', '[name="agree_btn"]', function () {
        $('#agreeCheckbox').change();  // 勾選 checkbox 並觸發 change 事件
        $checkbox.toggleClass('active');
        $checkboxLabel.toggleClass('active');
    });
}

function getCountry() {
    // 使用 RESTCountries API 获取所有国家数据
    // $.getJSON('./js/trip_Lib.json', function(data) {
    //     // $('#jsonContent').html(JSON.stringify(data, null, 2));
    // console.log(1)
    // })
    fetch('./js/trip_Lib.json')
        .then(response => response.json())
        .then(data => {
            // 定义要优先显示的国家 ISO 代码
            const preferredCountries = ['TW', 'HK', 'CN', 'MY', 'SG', 'JP', 'KR', 'ID', 'VN', 'TH'];

            const countryList = data.map(country => {
                let nativeCommonName = '';
                if (country.name.nativeName) {
                    const firstNative = Object.values(country.name.nativeName)[0];
                    nativeCommonName = firstNative.common;
                }
                const labelText = (country.translations && country.translations.zho && typeof country.translations.zho.common !== null)
                    ? country.translations.zho.common
                    : country.name.nativeName.zho.common;
                return {
                    value: country.cca3,
                    // label: nativeCommonName + '(' + country.cca3 + ')' || country.name.common + '(' + country.cca3 + ')'
                    label: labelText + '(' + country.name.common + ')',
                    imageUrl: country.flags.png
                };
            });
            // console.log(data)

            Tool.TomSelect('#setCountry', countryList);  // 样式化并传递国家列表数据

        })
        .catch(error => {
            console.error('Error fetching country data:', error);
        });

}

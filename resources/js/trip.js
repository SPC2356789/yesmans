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

$(document).ready(function () {
    swiper()
    getCountry()//初始化國籍選擇
    applyAgree()//同意書

    $(document).on('click', '#fillTestData', function () {
        fillFormData()
    });
    $(document).on('click', '#submitTestForm', function () {

    });

    $(document).on('click', '#setCountry', function () {
        if (!$('#setCountry').hasClass('specific-class')) {
            // console.log('NO')
            getCountry(); // 國籍選擇
        } else {
            console.log('有元素不執行')
        }
    });
    $(document).on('click', 'li[name="trip_times"]', function () {
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
        // 修改克隆表單中的所有 ID 跟name 屬性，並更新對應的 label 的 for 屬性
        clonedForm.find('[id], [name]').each(function () {
            const originalId = $(this).attr('id');
            if (originalId) {
                const newId = originalId + '_' + newNumber;
                $(this).attr('id', newId);
                // 更新 label 的 for 屬性
                clonedForm.find('label[for="' + originalId + '"]').attr('for', newId);
            }

            const originalName = $(this).attr('name');
            if (originalName) {
                const newName = originalName + '_' + newNumber;
                $(this).attr('name', newName);
            }
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


});
function fillFormData() {
    $('[name="all-name"]').val("測試用戶");
    $('[name="gender"][value="male"]').prop("checked", true);
    $('[name="birthday"]').val("1990-01-01");
    $('[name="setCountry"]').val("TW");
    $('[name="id-card"]').val("A123456789");
    $('[name="street-address"]').val("台北市測試路123號");
    $('[name="email"]').val("test@example.com");
    $('[name="phone"]').val("0912345678");
    $('[name="emergency_contact"]').val("緊急聯絡人");
    $('[name="emergency_contact_phone"]').val("0987654321");
    $('[name="media_LINE"]').val("test_line");
    $('[name="media_IG"]').val("test_ig");
    $('[name="medical_history"]').val("無");
    $('[name="mountain_experience"]').val("每週爬山");
    $('[name="diet"][value="non-vegetarian"]').prop("checked", true);
}


function submitForm() {
    $("form").submit();
}


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
        $tripFrom.removeClass('hidden');  // 勾選 checkbox
        // console.log(1);
    });

    // $tripFrom.toggleClass('hidden');  // 勾選 checkbox
    // $tripFrom.removeClass('hidden');  // 勾選 checkbox

    // 聚焦行為IOS不支持
    // $('[name="signupBtn"]').on('click', function () {
    //     $agreementButton.focus();
    // });
    $(document).on('click', '[name="signupBtn"]', function () {
        $agreementButton[0].scrollIntoView({behavior: 'smooth', block: 'start'});  // 滾動到圖片
    });
    $(document).on('click', '[name="agree_btn"]', function () {
        $('#agreeCheckbox').change();  // 勾選 checkbox 並觸發 change 事件
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
                label: labelText + ' (' + country.name.common + ')',
                imageUrl: country.flags.png
            };
        });

        // 调用工具函数更新 UI
        Tool.TomSelect('#setCountry', countryList);

    } catch (error) {
        console.error('Error processing country data:', error);
    }
}

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

// 創建 Vue 實例並註冊全局方法
const app = createApp(FormClone);

// 定義全局方法，這樣所有組件可以訪問
app.config.globalProperties.$getCountry = getCountry;

// 挂载应用（只需掛載一次）
app.mount('#trip_from');
import Choices from 'choices.js';

import "choices.js/public/assets/styles/choices.css";

$(document).ready(function () {

    swiper()
    getCountry()//初始化國籍選擇
    // test();
    applyAgree()//同意書
    // window.Alpine = Alpine;
    // Alpine.start();
    $(document).on('click', '#fillTestData', function () {
        fillFormData()
        console.log('輸入成功')
    });
    $(document).on('click', '#submitTestForm', function () {

    });

    function applyData() {

    }

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
        cloneItem()//克隆表單

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
    $('[name="country"]').val("TW");
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

function cloneItem() {
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
    clonedForm.find('[id]').each(function () {
        const originalId = $(this).attr('id');
        if (originalId) {
            const newId = originalId + '_' + newNumber;
            // const newdropdown = originaldropdown + '_' + newNumber;
            $(this).attr('id', newId);

            // clonedForm.find('#dropdown-states').attr('id', 'dropdown-states');
            // 更新 label 的 for 屬性
            clonedForm.find('label[for="' + originalId + '"]').attr('for', newId);

        }
        const originalDropdown = $(this).attr('data-dropdown-toggle');
        if (originalDropdown) {
            const newId = originalId + '_' + newNumber;
            const newDropdown = originalDropdown + '_' + newNumber;
            $(this).attr('data-dropdown-toggle', newDropdown);
            $(this).attr('id', newId);

            // 也同步修改對應的 dropdown 元素的 ID
            // clonedForm.find(`#${originalDropdown}`).attr('id', newDropdown);
        }
        // const originalName = $(this).attr('name');
        // if (originalName) {
        //     const newName = originalName + '_' + newNumber;
        //     $(this).attr('name', newName);
        // }
    });

    // 清空表單中的輸入欄位內容
    clonedForm.find('input').val('');
    clonedForm.find('select').prop('selectedIndex', 0); // 重設下拉選單
    //
    // // 將克隆的表單加到頁面中
    cloneItem.after(clonedForm);
    // getCountry()
};

function test() {
    const $tripFrom = $('#trip_from');
    const $agreementButton = $('[name="agree_btn"]');
    cloneItem()//克隆表單
    fillFormData()
    $tripFrom.toggleClass('hidden');  // 勾選 checkbox
    $tripFrom.removeClass('hidden');  // 勾選 checkbox
    $agreementButton[0].scrollIntoView({behavior: 'smooth', block: 'start'});


    const dataCollection = {}; // 存放所有 data-number 對應的資料
    const obj = $('[name="apply_trip"]');
    obj.each(function (index, element) {
        const number = $(element).data('number');  // 取得每個元素的 data-number
        $(this).find('fieldset [name]').each(function () {
            const name = $(this).attr('name');
            const value = $(this).val();

            // dataCollection[number][name] = value;

            console.log(`第幾位 ${number} Name: ${name}, Value: ${value}`);
        });

    });
    console.log(dataCollection);  // 可以打印出索引和值
    // console.log(number)

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
    $(document).on('click', '[name="signupBtn"]', function () {
        $agreementButton[0].scrollIntoView({behavior: 'smooth', block: 'start'});
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
                label: `    <div class="flex flex-row items-center w-full">
        <img class="w-6 mr-2" src="${country.flags.png}" alt="${labelText}" loading="lazy" />
        <div class="flex flex-col">
            <span>
                ${labelText}
            </span>
            <span>
                ${country.name.common}
            </span>
        </div>

    </div>`
            };
        });
        // $('.js-country').each(function (index, element) {
        //     // 清除可能存在的舊 Choices 實例
        //     if (element.choices) {
        //         element.choices.destroy(); // 銷毀現有的 Choices 實例
        //     }
        //
        //     // 創建新的 Choices 實例
        //     const choices = new Choices($(element)[0], {
        //         choices: countryList,
        //         allowHTML: true, // 允許在標籤中使用 HTML
        //     });
        //
        //     // 設置選中的選項
        //     choices.setChoiceByValue('TWN'); // 預設選擇 'TWN'
        // });
        return countryList;

    } catch (error) {
        console.error('Error processing country data:', error);
    }
}

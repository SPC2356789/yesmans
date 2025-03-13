<template>

    <p class="mb-2">請輸入以下資料查詢訂單</p>

    <div class="mt-2">
        <label for="id_card" class="block text-sm font-medium text-gray-700 text-left">身分證號碼/居留證號</label>
        <input
            type="text"
            id="id_card"
            v-model="form.idCard"
            class="w-full p-2 border rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500 mt-1"
            placeholder="身分證號碼 (如: A123456789)"
        >
    </div>

    <div class="mt-2">
        <label for="phone" class="block text-sm font-medium text-gray-700 text-left">電話號碼</label>
        <input
            type="text"
            id="phone"
            v-model="form.phone"
            class="w-full p-2 border rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500 mt-1"
            placeholder="電話號碼 (如: 0912345678)"
        >
    </div>

    <div class="mt-2">
        <label for="email" class="block text-sm font-medium text-gray-700 text-left">電子郵件</label>
        <input
            type="email"
            id="email"
            v-model="form.email"
            class="w-full p-2 border rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500 mt-1"
            placeholder="電子郵件 (如: example@email.com)"
        >
    </div>
    <div class="grid grid-cols-2 gap-2 items-end mt-2">
        <div class="flex flex-row items-end">
            <img :src="captchaSrc" @click="refreshCaptcha" class="cursor-pointer w-full" alt="驗證碼"/>
            <div @click="refreshCaptcha" class="cursor-pointer p-0 inline-block w-6 ">
                <i class="fa-solid fa-rotate"></i>
            </div>
        </div>
        <input
            v-model="captchaInput"
            id="captcha"
            name="captcha"
            type="text"
            required
            placeholder="輸入驗證碼"
            class="w-full m-0 border"
        />

    </div>
    <p class="text-sm text-gray-500 mt-1">輸入後按「查詢」以繼續</p>
    <!-- 送出按鈕 -->
    <button
        @click="submitOrderQuery"
        class="mt-4 bg-yes-major opacity-90 text-white p-2 rounded-md hover:opacity-100 w-20"
    >
        <i class="fa fa-search"></i> <span class="text-white">查詢</span>
    </button>

</template>

<script setup>
import {createApp, onMounted, reactive, ref} from 'vue';
import axios from "axios";
import Swal from 'sweetalert2';
import getOrderPanel from "./getOrder.vue";
import {Tool}  from '../../js/core/tools.js';

const captchaSrc = ref('');
const captchaKey = ref('');
const captchaInput = ref('');
const form = reactive({
    idCard: 'A123456789',
    phone: '0912345678',
    email: 'test@example.com'
});
let refreshInterval = null; // 用於存儲定時器
onMounted(() => {
    refreshCaptcha();
    // 每 60 秒自動刷新一次驗證碼
    refreshInterval = setInterval(() => {
        refreshCaptcha();
    }, 180 * 1000); // 60 秒
});
const refreshCaptcha = async () => {
    try {
        console.log('refreshCaptcha triggered');
        const response = await axios.get('/captcha/api/flat');
        captchaSrc.value = response.data.img;
        captchaKey.value = response.data.key;
    } catch (error) {
        console.error('驗證碼載入失敗', error);
    }
};
const submitOrderQuery = () => {
    if (!form.idCard || !form.phone || !form.email) {
        Swal.showValidationMessage('請輸入身分證號碼、電話號碼和電子郵件！');
        return;
    }
    if (form.idCard.length !== 10) {
        Swal.showValidationMessage('身分證號碼應為10碼！');
        return;
    }
    if (!form.phone.match(/^09\d{8}$/)) {
        Swal.showValidationMessage('電話號碼格式錯誤，應為09開頭的10位數字！');
        return;
    }
    if (!form.email.match(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/)) {
        Swal.showValidationMessage('電子郵件格式錯誤！');
        return;
    }

    Swal.showLoading();

    return axios.post('/get-order', {
        key: captchaKey.value,
        captcha: captchaInput.value,
        id_card: form.idCard,          // 修正為 form.idCard
        phone: form.phone,             // 修正為 form.phone
        email: form.email              // 修正為 form.email
    }).then(response => {

            Swal.fire({
                title: '<strong>查詢結果</strong>',
                icon: 'success',
                html: '<div id="get_order" class="mt-4 max-h-60 overflow-y-auto"></div>',
                showCloseButton: true,
                showConfirmButton: false,
                didOpen: () => {
                    const get_order = Swal.getPopup().querySelector('#get_order');
                    const app = createApp(getOrderPanel, {
                        orders: response.data[0],
                        onBack: () => {
                            Swal.close();
                            Tool.getOrder(); // 重新觸發查詢
                        }
                    });
                    app.mount(get_order);
                }
            });
    }).catch(error => {
        console.log(error.response.data)
        Swal.fire({
            icon: 'error',
            title: '查詢失敗',
            text: error.response.data,
            showConfirmButton: false,
            html: `
                <button
                    id="backButton"
                    class="mt-4 px-3 py-1 bg-yes-minor text-white rounded-md hover:opacity-75 transition-colors text-sm"
                >
                    返回查詢
                </button>
            `,
            didOpen: () => {
                const backButton = Swal.getPopup().querySelector('#backButton');
                if (backButton) {
                    backButton.addEventListener('click', () => {
                        Swal.close();
                        if (typeof Tool.getOrder === 'function') {
                            Tool.getOrder();
                        } else {
                            console.error('Tool.getOrder is not a function');
                        }
                    });
                }
            },
        });
        return false;
    });

};
</script>

<style scoped>
/* 可選：若需自訂樣式 */
</style>

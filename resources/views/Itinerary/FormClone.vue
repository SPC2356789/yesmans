<template>
    <form @submit.prevent="onSubmit" class="space-y-6">
        <h2 class="text-xl font-bold mb-4">報名表單</h2>

        <!-- 銀行帳戶資訊區塊 -->
        <div class="bg-gray-50 p-4 rounded border border-gray-200 my-4">
            <p class="text-gray-700">請將款項轉入以下銀行帳戶：</p>
            <ul class="mt-2 space-y-1 cursor-pointer" @click="copyAccount">
                <li><span class="font-semibold">銀行名稱:</span> {{props.bank['Itinerary_bank.name']}}</li>
                <li><span class="font-semibold">分行:</span> {{props.bank['Itinerary_bank.branch']}}</li>
                <li><span class="font-semibold">帳號:</span> {{props.bank['Itinerary_bank.account']}}</li>
                <li><span class="font-semibold">戶名:</span> {{props.bank['Itinerary_bank.holder']}}</li>
                <li><span class="font-semibold">需轉帳金額:</span> {{props.data['amount']}}</li>
            </ul>
            <p class="text-sm text-gray-500 mt-2">轉帳完成後，請務必填寫帳號末五碼，以便確認付款。</p>
            <p class="text-gray-700">填寫匯款資訊：</p>
            <div class="mt-2 flex space-x-4">
                <div class="flex-1">
                    <input
                        v-model="account_last_five"
                        name="account_last_five"
                        type="text"
                        required
                        minlength="5"
                        maxlength="5"
                        class="w-full p-2 border rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500"
                        placeholder="請輸入轉帳號末五碼"
                    />
                    <span v-if="errors.account_last_five" class="mt-1 text-sm text-red-600">{{ errors.account_last_five }}</span>
                </div>
                <div class="flex-1">
                    <input
                        v-model="paid_amount"
                        name="paid_amount"
                        type="number"
                        required
                        min="0"
                        class="w-full p-2 border rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500"
                        placeholder="請輸入匯款金額"
                    />
                    <span v-if="errors.paid_amount" class="mt-1 text-sm text-red-600">{{ errors.paid_amount }}</span>
                </div>
            </div>
        </div>

        <!-- 動態表單項 -->
        <div v-for="(form, index) in formList" :key="form.id" class="mb-4 p-4 border rounded">
            <div class="flex flex-row justify-between">
                <p class="mt-1 text-sm/6 text-gray-600">序號 {{ index + 1 }}</p>
                <button
                    v-if="formList.length > 1"
                    @click="removeForm(form.id)"
                    class="inline-flex items-center cursor-pointer text-gray-600 hover:text-[#EE9900] active:scale-95 transition-transform duration-200 ease-in-out"
                >
                    <i class="fa-solid fa-user-minus"></i>
                </button>
            </div>

            <!-- 個人資訊 -->
            <fieldset>
                <legend class="text-sm/6 font-semibold text-gray-900">個人資訊</legend>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label :for="'name-' + form.id" class="block text-sm/6 font-medium text-gray-900">全名</label>
                        <input
                            v-model="form.name"
                            :id="'name-' + form.id"
                            :name="'name-' + form.id"
                            type="text"
                            required
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6"
                        />
                        <span v-if="errors[`name-${form.id}`]" class="mt-1 text-sm text-red-600">{{ errors[`name-${form.id}`] }}</span>
                    </div>

                    <div class="sm:col-span-2">
                        <label :for="'birthday-' + form.id" class="block text-sm/6 font-medium text-gray-900">生日</label>
                        <input
                            v-model="form.birthday"
                            :id="'birthday-' + form.id"
                            :name="'birthday-' + form.id"
                            type="date"
                            required
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6"
                        />
                        <span v-if="errors[`birthday-${form.id}`]" class="mt-1 text-sm text-red-600">{{ errors[`birthday-${form.id}`] }}</span>
                    </div>

                    <div class="sm:col-span-1">
                        <label :for="'gender-' + form.id" class="block text-sm/6 font-medium text-gray-900">性別</label>
                        <select
                            v-model="form.gender"
                            :id="'gender-' + form.id"
                            :name="'gender-' + form.id"
                            required
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6"
                        >
                            <option value="">請選擇</option>
                            <option value="male">男性</option>
                            <option value="female">女性</option>
                            <option value="other">其他</option>
                        </select>
                        <span v-if="errors[`gender-${form.id}`]" class="mt-1 text-sm text-red-600">{{ errors[`gender-${form.id}`] }}</span>
                    </div>

                    <div class="sm:col-span-2">
                        <label :for="'country-' + form.id" class="block text-sm/6 font-medium text-gray-900">國家</label>
                        <select
                            v-model="form.country"
                            :id="'country-' + form.id"
                            :name="'country-' + form.id"
                            required
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6"
                        >
                            <!-- Choices.js 會動態填充選項 -->
                        </select>
                        <span v-if="errors[`country-${form.id}`]" class="mt-1 text-sm text-red-600">{{ errors[`country-${form.id}`] }}</span>
                    </div>

                    <div class="sm:col-span-2">
                        <label :for="'id_card-' + form.id" class="block text-sm/6 font-medium text-gray-900">身分證/居留證</label>
                        <input
                            v-model="form.id_card"
                            :id="'id_card-' + form.id"
                            :name="'id_card-' + form.id"
                            type="text"
                            required
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6"
                        />
                        <span v-if="errors[`id_card-${form.id}`]" class="mt-1 text-sm text-red-600">{{ errors[`id_card-${form.id}`] }}</span>
                    </div>

                    <div v-if="props.data['passport_enabled'] === 1" class="sm:col-span-2">
                        <label :for="'PassPort-' + form.id" class="block text-sm/6 font-medium text-gray-900">護照</label>
                        <input
                            v-model="form.PassPort"
                            :id="'PassPort-' + form.id"
                            :name="'PassPort-' + form.id"
                            type="text"
                            required
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6"
                        />
                        <span v-if="errors[`PassPort-${form.id}`]" class="mt-1 text-sm text-red-600">{{ errors[`PassPort-${form.id}`] }}</span>
                    </div>

                    <div class="sm:col-span-3">
                        <label :for="'address-' + form.id" class="block text-sm/6 font-medium text-gray-900">居住地址</label>
                        <input
                            v-model="form.address"
                            :id="'address-' + form.id"
                            :name="'address-' + form.id"
                            type="text"
                            required
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6"
                        />
                        <span v-if="errors[`address-${form.id}`]" class="mt-1 text-sm text-red-600">{{ errors[`address-${form.id}`] }}</span>
                    </div>

                    <div class="sm:col-span-3">
                        <label :for="'email-' + form.id" class="block text-sm/6 font-medium text-gray-900">電子郵件</label>
                        <input
                            v-model="form.email"
                            :id="'email-' + form.id"
                            :name="'email-' + form.id"
                            type="email"
                            required
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6"
                        />
                        <span v-if="errors[`email-${form.id}`]" class="mt-1 text-sm text-red-600">{{ errors[`email-${form.id}`] }}</span>
                    </div>

                    <div class="sm:col-span-3">
                        <label :for="'phone-' + form.id" class="block text-sm/6 font-medium text-gray-900">聯絡電話</label>
                        <input
                            v-model="form.phone"
                            :id="'phone-' + form.id"
                            :name="'phone-' + form.id"
                            type="tel"
                            required
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6"
                        />
                        <span v-if="errors[`phone-${form.id}`]" class="mt-1 text-sm text-red-600">{{ errors[`phone-${form.id}`] }}</span>
                    </div>

                    <div class="sm:col-span-3">
                        <label :for="'emContact-' + form.id" class="block text-sm/6 font-medium text-gray-900">緊急連絡人</label>
                        <input
                            v-model="form.emContact"
                            :id="'emContact-' + form.id"
                            :name="'emContact-' + form.id"
                            type="text"
                            required
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6"
                        />
                        <span v-if="errors[`emContact-${form.id}`]" class="mt-1 text-sm text-red-600">{{ errors[`emContact-${form.id}`] }}</span>
                    </div>

                    <div class="sm:col-span-3">
                        <label :for="'emContactPh-' + form.id" class="block text-sm/6 font-medium text-gray-900">緊急連絡人電話</label>
                        <input
                            v-model="form.emContactPh"
                            :id="'emContactPh-' + form.id"
                            :name="'emContactPh-' + form.id"
                            type="tel"
                            required
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6"
                        />
                        <span v-if="errors[`emContactPh-${form.id}`]" class="mt-1 text-sm text-red-600">{{ errors[`emContactPh-${form.id}`] }}</span>
                    </div>
                </div>
            </fieldset>

            <!-- 聯絡資訊 -->
            <fieldset>
                <legend class="text-sm/6 font-semibold text-gray-900">聯絡資訊</legend>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label :for="'LINE-' + form.id" class="block text-sm/6 font-medium text-gray-900">LINE-ID(必填)</label>
                        <input
                            v-model="form.LINE"
                            :id="'LINE-' + form.id"
                            :name="'LINE-' + form.id"
                            type="text"
                            required
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6"
                        />
                        <span v-if="errors[`LINE-${form.id}`]" class="mt-1 text-sm text-red-600">{{ errors[`LINE-${form.id}`] }}</span>
                    </div>
                    <div class="sm:col-span-3">
                        <label :for="'IG-' + form.id" class="block text-sm/6 font-medium text-gray-900">IG-ID</label>
                        <input
                            v-model="form.IG"
                            :id="'IG-' + form.id"
                            :name="'IG-' + form.id"
                            type="text"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6"
                        />
                        <span v-if="errors[`IG-${form.id}`]" class="mt-1 text-sm text-red-600">{{ errors[`IG-${form.id}`] }}</span>
                    </div>
                </div>
            </fieldset>

            <!-- 過往經驗 -->
            <fieldset>
                <legend class="text-sm/6 font-semibold text-gray-900">過往經驗(請準確填寫)</legend>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-2">
                        <label :for="'disease-' + form.id" class="block text-sm/6 font-medium text-gray-900">病史</label>
                        <select
                            multiple
                            v-model="form.disease"
                            :id="'disease-' + form.id"
                            :name="'disease-' + form.id"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6"
                        >
                            <!-- Choices.js 會動態填充選項 -->
                        </select>
                    </div>

                    <div class="sm:col-span-3">
                        <label :for="'experience-' + form.id" class="block text-sm/6 font-medium text-gray-900">爬山/運動經驗(過去一年)</label>
                        <input
                            v-model="form.experience"
                            :id="'experience-' + form.id"
                            :name="'experience-' + form.id"
                            type="text"
                            required
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6"
                        />
                        <span v-if="errors[`experience-${form.id}`]" class="mt-1 text-sm text-red-600">{{ errors[`experience-${form.id}`] }}</span>
                    </div>
                    <div v-if="props.data['food'] === 1" class="sm:col-span-1">
                        <label :for="'diet-' + form.id" class="block text-sm/6 font-medium text-gray-900">飲食</label>
                        <select
                            v-model="form.diet"
                            :id="'diet-' + form.id"
                            :name="'diet-' + form.id"
                            required
                            class="border p-2 w-full"
                        >
                            <option value="">請選擇</option>
                            <option value="vegetarian">素食</option>
                            <option value="non-vegetarian">葷食</option>
                        </select>
                        <span v-if="errors[`diet-${form.id}`]" class="mt-1 text-sm text-red-600">{{ errors[`diet-${form.id}`] }}</span>
                    </div>
                </div>
            </fieldset>
        </div>

        <!-- 新增表單按鈕 -->
        <div
            @click="addForm"
            class="cursor-pointer inline-flex items-center cursor-pointer text-gray-600 hover:text-yes-major active:scale-95 transition-transform duration-200 ease-in-out"
        >
            <i class="fa-solid fa-user-plus"></i>
        </div>

        <!-- 驗證碼與提交按鈕 -->
        <div class="mt-6 flex justify-between items-end gap-2 w-full">
            <div class="grid xxx:grid-cols-1 sm:grid-cols-2 gap-2 items-end">
                <img :src="captchaSrc" @click="refreshCaptcha" class="cursor-pointer w-full" alt="驗證碼" />
                <div @click="refreshCaptcha" class="cursor-pointer p-0 inline-block w-6">
                    <i class="fa-solid fa-rotate"></i>
                </div>
                <input
                    v-model="captchaInput"
                    name="captcha"
                    type="text"
                    required
                    placeholder="輸入驗證碼"
                    class="w-full m-0"
                />
                <span v-if="errors.captcha" class="mt-1 text-sm text-red-600">{{ errors.captcha }}</span>
            </div>
            <button
                type="submit"
                class="xxx:w-1/2 xs:w-1/4 rounded-md bg-yes-major px-3 py-2 text-sm font-semibold hover:text-yes-major text-neutral-50 shadow-sm hover:bg-neutral-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yes-major"
            >
                提交
            </button>
        </div>
    </form>
</template>

<script setup>
import { ref, computed, onMounted, nextTick, defineProps } from 'vue';
import Choices from 'choices.js';
import axios from 'axios';
import Swal from 'sweetalert2';

// 動態表單數據
const formList = ref([
    {
        id: 1,
        name: '測試使用者',
        gender: 'male',
        birthday: '1990-01-01',I    
        email: 'test@example.com',
        phone: '0912345678',
        country: 'TWN',
        id_card: 'A123456789',
        address: '桃園市中壢區長江路85號',
        PassPort: 'E12345678',
        diet: 'vegetarian',
        experience: '5年戶外經驗',
        disease: [], // 改為陣列
        LINE: '@test123',
        IG: 'test_ig',
        emContactPh: '0922334455',
        emContact: '王小明 (父親)',
    },
]);

// 自訂錯誤訊息
const errors = ref({});
const fieldLabels = computed(() => {
    const baseLabels = {
        account_last_five: '帳號末五碼',
        paid_amount: '匯款金額',
        captcha: '驗證碼',
    };
    const dynamicLabels = {};
    formList.value.forEach((form) => {
        dynamicLabels[`name-${form.id}`] = '全名';
        dynamicLabels[`birthday-${form.id}`] = '生日';
        dynamicLabels[`gender-${form.id}`] = '性別';
        dynamicLabels[`country-${form.id}`] = '國家';
        dynamicLabels[`id_card-${form.id}`] = '身分證/居留證';
        dynamicLabels[`PassPort-${form.id}`] = '護照';
        dynamicLabels[`address-${form.id}`] = '居住地址';
        dynamicLabels[`email-${form.id}`] = '電子郵件';
        dynamicLabels[`phone-${form.id}`] = '聯絡電話';
        dynamicLabels[`emContact-${form.id}`] = '緊急連絡人';
        dynamicLabels[`emContactPh-${form.id}`] = '緊急連絡人電話';
        dynamicLabels[`LINE-${form.id}`] = 'LINE-ID';
        // dynamicLabels[`IG-${form.id}`] = 'IG-ID';
        // dynamicLabels[`disease-${form.id}`] = '病史';
        // dynamicLabels[`experience-${form.id}`] = '爬山/運動經驗';
        dynamicLabels[`diet-${form.id}`] = '飲食';
    });
    return { ...baseLabels, ...dynamicLabels };
});

// Props 定義
const props = defineProps({
    data: { type: Object, required: false, default: () => ({}) },
    bank: { type: Object, required: false, default: () => ({}) },
    CountryData: { type: Object, required: false, default: () => ({}) },
});

// 其他數據
const countries = ref([]);//國家選項
const disease = ref([]);//國家選項
const diseases = ref([
    { value: '流感', label: '流感' },
    { value: 'COVID-19', label: 'COVID-19' },
    { value: '哮喘', label: '哮喘' },
]);//疾病選項
const captchaSrc = ref('');
const captchaKey = ref('');
const captchaInput = ref('');
const account_last_five = ref('');//帳號後五碼
const paid_amount = ref('');//付款金額
let addFormId = 2;

// 初始化
onMounted(() => {
    refreshCaptcha();
    console.log(props.data);
    if (props && props.CountryData) {
        countries.value = props.CountryData;
    }
    initChoices();
});

// 新增表單項
const addForm = () => {
    const newId = addFormId++;
    formList.value.push({
        id: newId,
        name: '測試使用者'+newId,
        gender: 'male',
        birthday: '1990-01-01',
        email: 'test@example.com',
        phone: '0912345678',
        country: 'TWN',
        id_card: 'A123456789',
        address: '桃園市中壢區長江路85號',
        PassPort: 'E12345678',
        diet: 'vegetarian',
        experience: '5年戶外經驗',
        disease: ['無'], // 改為陣列
        LINE: '@test123',
        IG: 'test_ig',
        emContactPh: '0922334455',
        emContact: '王小明 (父親)',
    });
    nextTick(() => {
        initChoices(newId);
    });
};

// 移除表單項
const removeForm = (id) => {
    const countryId = `country-${id}`;
    if (countries.value[countryId]) {
        countries.value[countryId].destroy();
        delete countries.value[countryId];
    }
    formList.value = formList.value.filter((item) => item.id !== id);
};

// 提交表單處理
const onSubmit = async (event) => {
    console.log('提交事件觸發');
    const form = event.target;
    errors.value = {};

    const isValid = form.checkValidity();

    if (isValid) {
        console.log('驗證通過，提交數據:', { formList: formList.value, account_last_five: account_last_five.value, paid_amount: paid_amount.value, captcha: captchaInput.value });
        try {
            const urlWithoutParams = window.location.origin + window.location.pathname;
            const response = await axios.post(urlWithoutParams + '/apply', {
                uuid: props.data.uuid,
                data: formList.value,
                amount: props.data.amount,
                account_last_five: account_last_five.value,
                paid_amount: paid_amount.value,
                captcha: captchaInput.value,
                key: captchaKey.value,
            });

            window.scrollTo(0, 0);
            Swal.fire({
                showCloseButton: true, // 顯示右上角的 X 按鈕
                showConfirmButton: false, // 隱藏確認按鈕
                showCancelButton: false, // 隱藏取消按鈕
                allowOutsideClick: true, // 可選：允許點擊外部關閉
                title: '報名成功',
                html: response.data.message,
            }).then(() => {
                location.reload();
            });
        } catch (error) {
            console.error('提交失敗:', error);
            Swal.fire({
                title: '錯誤',
                text: JSON.stringify(error.response?.data?.message || '提交失敗'),
                icon: 'error',
                confirmButtonText: '確定',
            }).then(() => {
                refreshCaptcha();
            });
        }
    } else {
        console.log('驗證失敗');
        const invalidFields = form.querySelectorAll(':invalid');
        invalidFields.forEach((field) => {
            const fieldName = field.name;
            if (fieldName === 'account_last_five' && (account_last_five.value.length < 5 || account_last_five.value.length > 5)) {
                errors.value[fieldName] = `${fieldLabels.value[fieldName]} 長度必須為 5 位`;
            } else if (fieldName === 'paid_amount' && isNaN(paid_amount.value)) {
                errors.value[fieldName] = `${fieldLabels.value[fieldName]} 必須是數字`;
            } else if (fieldName.includes('email') && !field.validity.valid) {
                errors.value[fieldName] = `${fieldLabels.value[fieldName]} 必須是有效的電子郵件`;
            } else {
                errors.value[fieldName] = `${fieldLabels.value[fieldName]} 是必填欄位`;
            }
        });
        const firstInvalidField = invalidFields[0];
        if (firstInvalidField) {
            console.log(`滾動到: ${firstInvalidField.name}`);
            firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalidField.focus();
        }
    }
};

// 刷新驗證碼
const refreshCaptcha = async () => {
    try {
        console.log('refreshCaptcha triggered');
        const response = await axios.get('/captcha/api/math');
        captchaSrc.value = response.data.img;
        captchaKey.value = response.data.key;
    } catch (error) {
        console.error('驗證碼載入失敗', error);
    }
};

// 初始化 Choices.js
const initChoices = (newId = 1) => {

    const disease_id = `disease-${newId}`;
    disease.value[disease_id] = new Choices(`#${disease_id}`, {
        searchEnabled: true,
        allowHTML: false,
        itemSelectText: '',
        choices: diseases.value,
        searchPlaceholderValue: '輸入疾病名稱搜尋或新增...',
        noResultsText: '無符合疾病，按 Enter 新增',
        addItems: true, // 必須啟用
        addChoices: true, // 新增此屬性以啟用動態新增
        removeItems: true,
        removeItemButton: true,
        duplicateItemsAllowed: false,
        editItems: false,

        maxItemCount: 10,
        addItemFilter: (value) => value.trim().length >= 2,
        callbackOnInit: function () {
            console.log(`${disease_id} 初始化完成`);
        },
        // 強制顯示下拉選項
        shouldSort: false,
        position: 'bottom', // 確保下拉位置正確
    });


    const country_id = `country-${newId}`;
    countries.value[country_id] = new Choices(`#${country_id}`, {
        searchEnabled: true,
        allowHTML: true,
        itemSelectText: '',
        choices: countries.value,
    });
    countries.value[country_id].setChoiceByValue('TWN');
};

// 複製銀行帳號
const copyAccount = () => {
    const account = props.bank['Itinerary_bank.account'];
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(account)
            .then(() => {
                alert('帳號已複製到剪貼簿');
            })
            .catch((error) => {
                console.error('複製失敗:', error);
            });
    } else {
        console.warn('Clipboard API 不支援，請手動複製');
    }
};
</script>

<style scoped>
input,
select {
    display: block;
    margin-top: 5px;
    margin-bottom: 10px;
}
</style>

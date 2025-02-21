<script setup>
import {ref} from 'vue';
import Choices from 'choices.js';
import {onMounted, nextTick, defineProps} from 'vue';
import axios from "axios";
import Swal from "sweetalert2";


const countries = ref([]);

const props = defineProps({
    data: {
        type: Object,
        required: false, // 取消 required，避免崩潰
        default: () => ({}) // 預設為空物件
    },
    CountryData: {
        type: Object,
        required: false,
        default: () => ({})
    }
});




const formList = ref([
    {
        id: 1,
        name: '測試使用者',
        gender: 'male', // 或 '女'
        birthday: '1990-01-01',
        email: 'test@example.com',
        phone: '0912345678',
        country: 'TWN',
        id_card: 'A123456789',
        address: '桃園市中壢區長江路85號',
        PassPort: 'E12345678',
        diet: 'vegetarian', // 例如：素食、葷食等
        experience: '5年戶外經驗',
        disease: '無', // 若有病史可填寫，如 "高血壓"
        LINE: '@test123',
        IG: 'test_ig',
        emContactPh: '0922334455', // 緊急聯絡人電話
        emContact: '王小明 (父親)' // 緊急聯絡人名稱與關係
    }

]);

let addFormId = 2;  // 初始化一個 ID 計數器
onMounted(() => {
    refreshCaptcha();

    // const {proxy} = getCurrentInstance(); // 獲取 Vue 實例
    if (props && props.CountryData) {
        countries.value = props.CountryData
    }

    // 初始化選擇框
    initChoices();

});
const addForm = () => {
    // 使用 nextId 作為新 ID，並將計數器加 1
    const newId = addFormId++;
    formList.value.push({
        id: newId,
        name: '測試使用者'+newId,
        gender: 'male', // 或 '女'
        birthday: '1990-01-01',
        email: 'test@example.com',
        phone: '0912345678',
        country: 'TWN',
        id_card: 'A123456789',
        address: '桃園市中壢區長江路85號',
        PassPort: 'E12345678',
        diet: 'vegetarian', // 例如：素食、葷食等
        experience: '5年戶外經驗',
        disease: '無', // 若有病史可填寫，如 "高血壓"
        LINE: '@test123',
        IG: 'test_ig',
        emContactPh: '0922334455', // 緊急聯絡人電話
        emContact: '王小明 (父親)' // 緊急聯絡人名稱與關係
    });
    nextTick(() => {
        initChoices(newId);
    });
};

const removeForm = (index) => {
    let id = "country-" + index; // 確保 id 正確

    if (countries.value[id]) {
        countries.value[id].destroy(); // 銷毀 Choices 實例，避免記憶體洩漏
        delete countries.value[id]; // 從物件中移除該選項
    }
    formList.value = formList.value.filter(item => item.id !== index);
    // console.log("已移除 ID:", index);
};
// 提交表單
const submitForms = async () => {
    try {
        const urlWithoutParams = window.location.origin + window.location.pathname;
        axios.post(urlWithoutParams + '/apply', {
            uuid: props.data.uuid,
            data: formList.value,
            amount: props.data.amount,
            captcha: captchaInput.value, // 使用者輸入的驗證碼
            key: captchaKey.value, // 後端需要的 key
        })
            .then(response => {
                window.scrollTo(0, 0); // 滑動到頁面頂部
                Swal.fire({
                    title: "報名成功",
                    html: response.data.message,
                    icon: "success",
                    confirmButtonText: "確定"
                }).then(() => {

                    location.reload(); // 重新整理頁面
                });
            })
            .catch(error => {
                Swal.fire({
                    title: "驗證碼錯誤",
                    text: "請重新輸入正確的驗證碼。",
                    icon: "error",
                    confirmButtonText: "確定"
                }).then(() => {
                    refreshCaptcha(); // 確保驗證碼刷新
                });
            });
    } catch (error) {
        console.error("驗證失敗:", error.response?.data || error);
    }
};


const captchaSrc = ref(""); // 存驗證碼圖片
const captchaKey = ref(""); // 存驗證碼 Key
const captchaInput = ref(""); // 存使用者輸入的驗證碼
// 重新加載驗證碼
const refreshCaptcha = async () => {
    try {

        const response = await axios.get("/captcha/api/math"); // Laravel API 端點
        // console.log("Captcha API Response:", response.data);
        captchaSrc.value = response.data.img; // 設定驗證碼圖片
        captchaKey.value = response.data.key; // 設定驗證碼 key
    } catch (error) {
        // console.error("驗證碼載入失敗", error);
    }
};

// 初始化 Choices.js
const initChoices = (newId = 1) => {
    let id = "country-" + newId;
    countries.value[id] = new Choices("#" + id, {
        searchEnabled: true, // 啟用搜尋
        allowHTML: true,
        itemSelectText: '',   // 禁用 '選擇國家' 文本
        choices: countries.value, // 傳入國家列表
    });
    // 確保 Choices 被初始化後再設置預設值
    countries.value[id].setChoiceByValue('TWN');

};
</script>

<template>
    <!--    <div class="max-w-2xl mx-auto p-6 shadow-md rounded">-->
    <h2 class="text-xl font-bold mb-4">報名表單</h2>

    <div v-for="(form, index) in formList" :key="form.id" class="mb-4 p-4 border rounded">
        <div class="flex flex-row justify-between">
            <p class="mt-1 text-sm/6 text-gray-600" name="apply_title">序號{{ index + 1 }}</p>
            <button v-if="formList.length > 1" @click="removeForm(form.id)"
                    class="inline-flex items-center cursor-pointer text-gray-600 hover:text-[#EE9900] active:scale-95 transition-transform duration-200 ease-in-out">
                <i class="fa-solid fa-user-minus"></i>
            </button>
        </div>
        <fieldset>
            <legend class="text-sm/6 font-semibold text-gray-900">個人資訊</legend>
            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                <div class="sm:col-span-3">

                    <label :for="'name-' + form.id" class="block text-sm/6 font-medium text-gray-900">全名</label>

                    <input v-model="form.name" type="text" :id="'name-' + form.id" :name="'name-' + form.id"
                           autocomplete="name"
                           class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6">

                </div>


                <div class="sm:col-span-2">
                    <label :for="'birthday-' + form.id" for="birthday"
                           class="block text-sm/6 font-medium text-gray-900">生日</label>
                    <input v-model="form.birthday" :id="'birthday-' + form.id" :name="'birthday-' + form.id" type="date"
                           class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6"/>
                </div>
                <div class="sm:col-span-1">
                    <label :for="'gender-' + form.id" for="birthday" class="block text-sm/6 font-medium text-gray-900">性別</label>
                    <select v-model="form.gender" :id="'gender-' + form.id" :name="'gender-' + form.id"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6">
                        <option value="">請選擇</option>
                        <option value="male">男性</option>
                        <option value="female">女性</option>
                        <option value="other">其他</option>
                    </select>
                </div>

                <div class="sm:col-span-3">

                    <label :for="'id_card-' + form.id"
                           class="block text-sm/6 font-medium text-gray-900">身分證/居留證</label>
                    <div class="mt-2">
                        <input v-model="form.id_card" :id="'id_card-' + form.id" :name="'id_card-' + form.id"
                               type="text" autocomplete="off"
                               class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6">
                    </div>
                </div>
                <div class="sm:col-span-3">
                    <label :for="'PassPort-' + form.id"
                           class="block text-sm/6 font-medium text-gray-900">護照</label>
                    <div class="mt-2">
                        <input v-model="form.PassPort" :id="'PassPort-' + form.id" :name="'PassPort-' + form.id"
                               type="text" autocomplete="off"
                               class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6">
                    </div>
                </div>
                <div class="sm:col-span-3">
                    <label :for="'country-' + form.id" for="country" class="block text-sm/6 font-medium text-gray-900">國家</label>
                    <select v-model="form.country" :id="'country-' + form.id" :name="'country-' + form.id"
                            class="">

                    </select>
                </div>
                <div class="sm:col-span-3">
                    <label :for="'address-' + form.id"
                           class="block text-sm/6 font-medium text-gray-900">居住地址</label>
                    <div class="mt-2">
                        <input type="text" :id="'address-' + form.id" :name="'address-' + form.id"
                               v-model="form.address"
                               autocomplete="street-address"
                               class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6">
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label :for="'email-' + form.id" class="block text-sm/6 font-medium text-gray-900">電子郵件</label>
                    <div class="mt-2">
                        <input v-model="form.email" :id="'email-' + form.id" :name="'email-' + form.id" type="email"
                               autocomplete="email"
                               class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6">
                    </div>
                </div>
                <div class="sm:col-span-3">
                    <label :for="'phone-' + form.id" class="block text-sm/6 font-medium text-gray-900">聯絡電話</label>
                    <div class="mt-2">
                        <input v-model="form.phone" :id="'phone-' + form.id" :name="'phone-' + form.id" type="tel"
                               autocomplete="tel"
                               class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6">
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label :for="'emContact-' + form.id"
                           class="block text-sm/6 font-medium text-gray-900">緊急連絡人</label>
                    <div class="mt-2">
                        <input v-model="form.emContact" :id="'emContact-' + form.id" :name="'emContact-' + form.id"
                               type="text" autocomplete="off"
                               class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6">
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label :for="'emContactPh-' + form.id"
                           class="block text-sm/6 font-medium text-gray-900">緊急連絡人電話</label>
                    <div class="mt-2">
                        <input v-model="form.emContactPh" :id="'emContactPh-' + form.id"
                               :name="'emContactPh-' + form.id" type="tel"
                               autocomplete="tel"
                               class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6">
                    </div>
                </div>

            </div>
        </fieldset>
        <fieldset>
            <legend class="text-sm/6 font-semibold text-gray-900">聯絡資訊</legend>
            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                <div class="sm:col-span-3">
                    <label :for="'LINE-' + form.id"
                           class="block text-sm/6 font-medium text-gray-900">LINE-ID(必填)</label>
                    <div class="mt-2">
                        <input v-model="form.LINE" :id="'LINE-' + form.id" :name="'LINE-' + form.id" type="text"
                               class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6">
                    </div>
                </div>
                <div class="sm:col-span-3">
                    <label :for="'IG-' + form.id" class="block text-sm/6 font-medium text-gray-900">IG-ID</label>
                    <div class="mt-2">
                        <input v-model="form.IG" :id="'IG-' + form.id" :name="'IG-' + form.id" type="text"
                               class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6">
                    </div>
                </div>

            </div>
        </fieldset>
        <fieldset>
            <legend class="text-sm/6 font-semibold text-gray-900">過往經驗(請準確填寫)</legend>
            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label :for="'disease-' + form.id" class="block text-sm/6 font-medium text-gray-900">病史</label>
                    <div class="mt-2">
                        <input v-model="form.disease" :id="'disease-' + form.id" :name="'disease-' + form.id"
                               type="text"
                               class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6">
                    </div>
                </div>
                <div class="sm:col-span-3">
                    <label :for="'experience-' + form.id"
                           class="block text-sm/6 font-medium text-gray-900">爬山/運動經驗(過去一年)</label>
                    <div class="mt-2">
                        <input v-model="form.experience" :id="'experience-' + form.id" :name="'experience-' + form.id"
                               type="text"
                               class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6">
                    </div>
                </div>
                <div v-if="props.data.food === 1" class="sm:col-span-1">
                    <label :for="'diet-' + form.id"
                           class="block text-sm/6 font-medium text-gray-900">飲食</label>
                    <select v-model="form.diet" :id="'diet-' + form.id" :name="'diet-' + form.id"
                            class="border p-2 w-full">
                        <option value="">請選擇</option>
                        <option value="vegetarian">素食</option>
                        <option value="non-vegetarian">葷食</option>
                    </select>
                </div>


            </div>
        </fieldset>

    </div>

    <button
        @click="addForm"
        class="inline-flex items-center cursor-pointer text-gray-600 hover:text-yes-major active:scale-95 transition-transform duration-200 ease-in-out">
        <i class="fa-solid fa-user-plus"></i>
    </button>

    <div class="mt-6 flex  justify-between items-end gap-2 w-full">
        <div class="grid xxx:grid-cols-1 sm:grid-cols-2   gap-2 items-end">
            <!-- 驗證碼圖片 -->
            <img :src="captchaSrc" @click="refreshCaptcha" class="cursor-pointer w-full" alt="驗證碼">
            <!-- 輸入框 -->
            <input v-model="captchaInput" type="text" placeholder="輸入驗證碼" class="w-full m-0">

        </div>
        <button @click="submitForms"
                class="xxx:w-1/2 xs:w-1/4 rounded-md bg-yes-major px-3 py-2 text-sm font-semibold  hover:text-yes-major text-neutral-50 shadow-sm hover:bg-neutral-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yes-majorw-full">
            提交
        </button>
    </div>
</template>

<style scoped>
input, select {
    display: block;
    margin-top: 5px;
    margin-bottom: 10px;
}
</style>

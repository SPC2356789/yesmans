<script setup>
import {ref} from 'vue';
import Choices from 'choices.js';

import {getCurrentInstance, onMounted, nextTick} from 'vue';

let fetchCountry;

// 使用 watchEffect 監視 formList 的變化，並在表單新增後重新初始化 Choices.js

const countries = ref([]);
// props: {
//     food: {
//         type: Boolean, // 指定類型為布林值
//         required: true, // 強制要求傳遞此屬性
//     },
// },

const formList = ref([
    {
        id: 1,
        name: '',
        gender: '',
        birthday: '',
        email: '',
        phone: '',
        country: '',
        id_card: '',
        PassPort: '',
        diet: '',
        experience: '',
        disease: '',
        LINE: '',
        IG: '',
        emContactPh: '',
        emContact: ''
    }

]);

const addForm = () => {
    const newId = formList.value.length + 1;
    formList.value.push({
        id: newId,
        name: '',
        gender: '',
        birthday: '',
        email: '',
        phone: '',
        country: '',
        id_card: '',
        PassPort: '',
        diet: '',
        LINE: '',
        IG: '',
        experience: '',
        disease: '',
        media_IG: '',
        media_LINE: '',
        emContactPh: '',
        emContact: ''
    });
    nextTick(() => {
        initChoices(newId);
    });
};

const removeForm = (index) => {
    formList.value.splice(index, 1);
};

const submitForms = () => {
    console.log("提交的資料：", formList.value);
    // 這裡可以用 fetch 或 axios 提交到後端
};


onMounted(() => {
    const {proxy} = getCurrentInstance(); // 獲取 Vue 實例
    if (proxy && proxy.$getCountry) {
        countries.value = proxy.$getCountry(); // 調用全局方法取得國家數據
    }
    // 初始化選擇框
    initChoices();

});

// 初始化 Choices.js
const initChoices = (newId = 1) => {


    let id = "country-" + newId;

    countries.value[id] = new Choices("#" + id, {
        searchEnabled: true, // 啟用搜尋
        allowHTML: true,
        itemSelectText: '選擇國家',
        choices: countries.value, // 傳入國家列表
    });

};
</script>

<template>
    <!--    <div class="max-w-2xl mx-auto p-6 shadow-md rounded">-->
    <h2 class="text-xl font-bold mb-4">報名表單</h2>

    <div v-for="(form, index) in formList" :key="form.id" class="mb-4 p-4 border rounded">
        <div class="flex flex-row justify-between">
            <p class="mt-1 text-sm/6 text-gray-600" name="apply_title">序號{{ index + 1 }}</p>
            <button v-if="formList.length > 1" @click="removeForm(index)"
                    class="inline-flex items-center cursor-pointer text-gray-600 hover:text-[#EE9900] active:scale-95 transition-transform duration-200 ease-in-out">
                <i class="fa-solid fa-user-minus"></i>
            </button>
        </div>
        <fieldset>
            <legend class="text-sm/6 font-semibold text-gray-900">個人資訊</legend>
            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                <div class="sm:col-span-3">

                    <label :for="'name-' + form.id" class="block text-sm/6 font-medium text-gray-900">全名</label>
                    <div class="mt-2">
                        <input v-model="form.name" type="text" :id="'name-' + form.id" :name="'name-' + form.id"
                               autocomplete="name"
                               class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6">
                    </div>
                </div>

                <div class="sm:col-span-1">
                    <label :for="'gender-' + form.id" for="birthday" class="block text-sm/6 font-medium text-gray-900">性別</label>
                    <select v-model="form.gender" :id="'gender-' + form.id" :name="'gender-' + form.id"
                            class="border p-2 w-full">
                        <option value="">請選擇</option>
                        <option value="male">男性</option>
                        <option value="female">女性</option>
                        <option value="other">其他</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label :for="'birthday-' + form.id" for="birthday"
                           class="block text-sm/6 font-medium text-gray-900">生日</label>
                    <input v-model="form.birthday" :id="'birthday-' + form.id" :name="'birthday-' + form.id" type="date"
                           class="border p-2 w-full"/>
                </div>
                <div class="sm:col-span-2">
                    <label :for="'country-' + form.id" for="country" class="block text-sm/6 font-medium text-gray-900">國家</label>
                    <select v-model="form.country" :id="'country-' + form.id" :name="'country-' + form.id"
                            class="">

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
                <div class="col-span-full">
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
            <legend class="text-sm/6 font-semibold text-gray-900">聯絡資訊(擇一填寫)</legend>
            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                <div class="sm:col-span-3">
                    <label :for="'LINE-' + form.id" class="block text-sm/6 font-medium text-gray-900">LINE-ID</label>
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
                <!--                    @if ($trip_times['food'])-->
                <div class="sm:col-span-1">
                    <label :for="'diet-' + form.id"
                           class="block text-sm/6 font-medium text-gray-900">飲食</label>
                    <select v-model="form.diet" :id="'diet-' + form.id" :name="'diet-' + form.id"
                            class="border p-2 w-full">
                        <option value="">請選擇</option>
                        <option value="vegetarian">素食</option>
                        <option value="non-vegetarian">葷食</option>
                    </select>
                </div>
                <!--                    @endif-->

            </div>
        </fieldset>

    </div>
    <button
        @click="addForm"
        class="inline-flex items-center cursor-pointer text-gray-600 hover:text-yes-major active:scale-95 transition-transform duration-200 ease-in-out">
        <i class="fa-solid fa-user-plus"></i>
    </button>
    <!--        <button  class="bg-blue-500 text-white p-2 mb-2 w-full">新增表單</button>-->
    <button @click="submitForms" class="bg-green-500 text-white p-2 w-full">提交</button>
    <!--    </div>-->
</template>

<style scoped>
input, select {
    display: block;
    margin-top: 5px;
    margin-bottom: 10px;
}
</style>

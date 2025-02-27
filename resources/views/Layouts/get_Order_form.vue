<template>

        <p class="mb-2">請輸入以下資料查詢訂單</p>

        <div class="mt-2">
            <label for="id_card" class="block text-sm font-medium text-gray-700 text-left">身分證號碼</label>
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

        <p class="text-sm text-gray-500 mt-1">輸入後按「查詢」以繼續</p>

</template>

<script setup>
import { reactive } from 'vue';

const form = reactive({
    idCard: 'D122833599',
    phone: '0989816399',
    email: 'test123@example.com'
});
</script>

<style scoped>
/* 可選：若需自訂樣式 */
</style>

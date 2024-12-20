<template>
    <!-- 右側內容區 -->
    <div class="w-3/4 pl-8" name="info_data">
        <div class="flex flex-row justify-between items-center mb-6" name="data_h">
            <h1 class="text-2xl font-semibold text-gray-800">近期活動</h1>

            <Menu as="div" class="relative inline-block text-left">
                <div>
                    <MenuButton
                        class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        Options
                        <ChevronDownIcon class="-mr-1 size-5 text-gray-400" aria-hidden="true"/>
                    </MenuButton>
                </div>

                <transition enter-active-class="transition ease-out duration-100"
                            enter-from-class="transform opacity-0 scale-95"
                            enter-to-class="transform opacity-100 scale-100"
                            leave-active-class="transition ease-in duration-75"
                            leave-from-class="transform opacity-100 scale-100"
                            leave-to-class="transform opacity-0 scale-95">
                    <MenuItems
                        class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none">
                        <div class="py-1">
                            <MenuItem v-slot="{ active }">
                                <a href="#"
                                   :class="[active ? 'bg-gray-100 text-gray-900 outline-none' : 'text-gray-700', 'block px-4 py-2 text-sm']">Account
                                    settings</a>
                            </MenuItem>
                            <MenuItem v-slot="{ active }">
                                <a href="#"
                                   :class="[active ? 'bg-gray-100 text-gray-900 outline-none' : 'text-gray-700', 'block px-4 py-2 text-sm']">Support</a>
                            </MenuItem>
                            <MenuItem v-slot="{ active }">
                                <a href="#"
                                   :class="[active ? 'bg-gray-100 text-gray-900 outline-none' : 'text-gray-700', 'block px-4 py-2 text-sm']">License</a>
                            </MenuItem>
                            <form method="POST" action="#">
                                <MenuItem v-slot="{ active }">
                                    <button type="submit"
                                            :class="[active ? 'bg-gray-100 text-gray-900 outline-none' : 'text-gray-700', 'block w-full px-4 py-2 text-left text-sm']">
                                        Sign out
                                    </button>
                                </MenuItem>
                            </form>
                        </div>
                    </MenuItems>
                </transition>
            </Menu>


            <!-- 活動卡片區域 -->
            <div class="flex flex-wrap">
                <!-- 使用 v-for 來遍歷活動卡片 -->
                <a
                    v-for="(card, index) in cards"
                    :key="index"
                    href="#"
                    class="w-full sm:w-1/2 lg:w-1/3 2xl:w-1/5 p-2 transform transition-transform duration-300 hover:scale-105 hover:z-50"
                >
                    <div class="bg-white bg-opacity-90 shadow-lg rounded-lg overflow-hidden">
                        <img
                            :src="card.imgSrc"
                            class="w-full h-auto rounded-t-lg"
                            :alt="card.title"
                        />
                        <div class="p-4 text-center font-bold text-gray-800 text-lg">
                            {{ card.title }}
                        </div>
                    </div>
                </a>
            </div>
        </div>
</template>

<script setup>
import {ref} from 'vue'

import {Menu, MenuButton, MenuItem, MenuItems} from '@headlessui/vue'
import {ChevronDownIcon} from '@heroicons/vue/20/solid'

const cards = ref([
    {imgSrc: 'https://via.placeholder.com/1024x1024', title: '登山實用'},
    {imgSrc: 'https://via.placeholder.com/1024x1024', title: '登山實用'},
    {imgSrc: 'https://via.placeholder.com/1024x1024', title: '登山實用'},
    {imgSrc: 'https://via.placeholder.com/1024x1024', title: '登山實用'},
    {imgSrc: 'https://via.placeholder.com/1024x1024', title: '登山實用'},
    {imgSrc: 'https://via.placeholder.com/1024x1024', title: '登山實用'},
    {imgSrc: 'https://via.placeholder.com/1024x1024', title: '登山實用'},
    {imgSrc: 'https://via.placeholder.com/1024x1024', title: '登山實用'},
    {imgSrc: 'https://via.placeholder.com/1024x1024', title: '登山實用'}
])
</script>

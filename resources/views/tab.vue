
<template>
    <v-card>
        <v-tabs v-model="tab" bg-color="primary">
            <!-- 動態生成 Tabs -->
            <v-tab
                v-for="(label, key) in tripTab"
                :key="key"
                :value="key"
                @click="refreshComponent"
            >
                {{ label }}
            </v-tab>
        </v-tabs>

        <v-card-text>
            <v-tabs-window v-model="tab">
                <v-tabs-window-item v-for="(data, key) in tripData" :key="key" :value="key">
                    <tripCarousel :tripData="data" />
                </v-tabs-window-item>
            </v-tabs-window>
        </v-card-text>


    </v-card>
</template>

<script setup>
import { ref, onMounted } from "vue";
import tripCarousel from "../views/tripCarousel.vue";

const tab = ref("one");
const refreshKey = ref(0);
const tripTab = ref(null);
const tripData = ref(null);

const refreshComponent = () => {
    setTimeout(() => {
        refreshKey.value++;
    }, 1000);
};

onMounted(() => {
    const tabElement = document.getElementById("tab");
    if (tabElement) {
        try {
            tripTab.value = JSON.parse(tabElement.dataset.trip_tab);
            tripData.value = JSON.parse(tabElement.dataset.trip_data);
            console.log("tripTab:", tripTab.value);
            console.log("tripData:", tripData.value);
        } catch (error) {
            console.error("JSON 解析錯誤:", error);
        }
    }
    // 自動選擇第一個 tab
    const firstKey = Object.keys(tripTab.value)[0];
    if (firstKey) {
        tab.value = firstKey;
    }
});
</script>



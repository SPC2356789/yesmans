<template>
    <div>
        <div v-if="props.orders && props.orders.length > 0" class="bg-white p-2 border rounded-md shadow-sm">
            <div class="text-left">
                <strong class="text-base text-gray-800">姓名:{{ order.name || '無姓名' }}</strong><br>
                <strong class="text-base text-gray-800">{{ order.title || '無標題' }}</strong><br>
                <span class="text-gray-600 text-sm">{{ order.times || '無時間' }}</span><br>
                <span class="text-gray-600 text-sm">{{ order.orders || '無訂單' }}</span><br>
                <span class="text-gray-600 text-sm">{{ order.status || '無狀態' }}</span>
            </div>
            <div class="flex justify-between items-center mt-4">
                <button
                    @click="prevStep"
                    :disabled="currentStep === 0"
                    class="px-3 py-1 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    上一步
                </button>
                <span class="text-sm text-gray-600">
                    第 {{ currentStep + 1 }} / {{ props.orders.length }} 筆
                </span>
                <button
                    @click="nextStep"
                    :disabled="currentStep === props.orders.length - 1"
                    class="px-3 py-1 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    下一步
                </button>
            </div>
        </div>
        <div v-else class="text-gray-600 text-sm">
            無查詢結果 (orders: {{ props.orders ? props.orders.length : 'undefined' }})
        </div>
        <button
            @click="props.onBack"
            class="mt-4 px-3 py-1 bg-yes-minor text-white rounded-md hover:opacity-75 transition-colors text-sm"
        >
            返回查詢
        </button>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

const props = defineProps({
    orders: {
        type: Array,
        required: true,
        default: () => []
    },
    onBack: {
        type: Function,
        required: true
    }
});

const currentStep = ref(0);

const order = computed(() => {
    const result = props.orders[currentStep.value];
    // console.log('Current Step:', currentStep.value);
    // console.log('Current Result:', result);
    return result || { title: '', times: '', orders: '', status: '' };
});

const prevStep = () => {
    if (currentStep.value > 0) {
        currentStep.value--;
    }
};

const nextStep = () => {
    if (props.orders && currentStep.value < props.orders.length - 1) {
        currentStep.value++;
    }
};

onMounted(() => {
    // console.log('Mounted - props.orders:', props.orders);
});
</script>

<style scoped>
/* 可選：若需自訂樣式 */
</style>

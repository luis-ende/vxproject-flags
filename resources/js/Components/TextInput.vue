<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: String,
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        class="text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 shadow-sm focus:ring-vxproject-secondary focus:border-vxproject-secondary dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-vxproject-secondary dark:focus:border-vxproject-secondary dark:shadow-sm-light"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
</template>

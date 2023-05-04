<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type" class="md:inline-flex items-center px-4 py-2 vxproject-button">
        <slot />
    </button>
</template>

<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button :type="type" class="md:inline-flex items-center px-4 py-2 text-stone-400 border-stone-400 vxproject-button">
        <slot />
    </button>
</template>

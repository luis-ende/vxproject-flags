<script setup>

defineProps({
    map: {
        type: Object,
        default: null,
    },
});

</script>

<template>
    <div id="map" class="w-full h-full"></div>
</template>

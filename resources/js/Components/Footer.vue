<script setup>
defineProps({
    fixedBar: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <div class="w-full" :class="fixedBar ? '' : 'md:fixed md:bottom-0'">
        <div class="bg-vxproject-primary text-xs font-montserrat text-white font-bold md:h-12 py-5 flex md:flex-row flex-col items-center justify-center md:space-x-10 md:space-y-0 space-y-2">
            <a :href="route('terms.show')"
               target="_blank" class="flex flex-col items-center">
                Términos y condiciones
            </a>
            <a :href="route('policy.show')" target="_blank" class="flex flex-col items-center">
                Políticas de privacidad
            </a>
            <a href="mailto:contacto@vxproject.com.mx?subject=VX Project Field"
               target="_blank"
               class="flex flex-col items-center">
                contacto@vxproject.com.mx
            </a>
        </div>
        <div class="bg-white font-bold text-base text-gray-800 h-5 text-center font-montserrat">
            <span>&#169; {{ new Date().getFullYear()  }} VX Project Field</span>
        </div>
    </div>
</template>

<style scoped>

</style>
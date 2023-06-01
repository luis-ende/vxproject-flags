<script setup>

</script>

<template>
    <div class="fixed bottom-0 w-full bg-stone-100 text-xs text-gray-800">
        <div class="w-full md:w-1/2 md:flex flex-row justify-between items-center text-center h-10 px-2 mx-auto">
            <p>&#169; 2023 VX Project Field</p>
            <a :href="route('terms.show')" class="vxproject-link-primary bg-transparent mr-5 md:mr-0">Términos y condiciones</a>
            <a :href="route('policy.show')" class="vxproject-link-primary bg-transparent md:mr-0">Políticas de privacidad</a>
        </div>
    </div>
</template>

<style scoped>

</style>
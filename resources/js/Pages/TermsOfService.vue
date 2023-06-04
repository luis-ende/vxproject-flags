<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import FieldLogo from "../Components/FieldLogo.vue";
import Footer from "../Components/Footer.vue";

defineProps({
    terms: String,
});
</script>

<template>
    <Head title="Términos y condiciones" />

    <div class="font-sans text-gray-900 antialiased">
        <div class="pt-4">
            <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
                <div class="flex flex-col items-center">
                    <AuthenticationCardLogo />
                    <div class="w-48 h-16">
                        <FieldLogo />
                    </div>
                </div>

                <div class="w-full sm:max-w-7xl my-10 p-6 bg-white overflow-hidden sm:rounded-lg prose" v-html="terms" />
            </div>
        </div>
    </div>

    <Footer :fixed-bar="true" />
</template>

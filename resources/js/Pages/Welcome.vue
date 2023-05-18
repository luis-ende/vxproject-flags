<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticationCardLogo from "../Components/AuthenticationCardLogo.vue";
import FieldLogo from "../Components/FieldLogo.vue";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    status: String,
});
</script>

<template>
    <Head title="VX Project Field - Inicio" />

    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-center bg-white dark:bg-gray-900 selection:bg-red-500 selection:text-white">
        <div class="max-w-5xl mx-auto p-6 lg:p-8 flex flex-col text-lg md:text-xl items-center">
            <div class="w-96 h-16">
                <AuthenticationCardLogo />
            </div>
            <div class="w-48 h-16">
                <FieldLogo />
            </div>
            <p class="my-10 text-center">
                <span class="font-bold">Field</span> es una herramienta que te dará acceso a información de sitios existentes con la cuál podrás realizar un prediseño de la cimentación que requiera el proyecto de radiobase. De esta manera podrás conocer los costos preliminares del proyecto completo en menor tiempo. Para un proyecto definitivo se tendrá que realizar un estudio de mecánica de suelos y/o de rocas.
            </p>
            <a :href="route('login')" class="vxproject-button mb-4 py-1 w-40 text-center">Iniciar sesión</a>
            <a href="https://vxproject-dev.vxproject.com.mx/contacto?asunto=3"
               target="_blank" class="vxproject-button py-1 w-40 text-center">Suscríbete</a>
            <p class="mt-10 text-center">
                Si ya formas parte de la comunidad de Field, inicia sesión.
            </p>
            <p class="text-center">
                Si aún no formas parte de la comunidad de Field, contáctanos para brindarte información acerca de los planes de suscripción.
            </p>
        </div>
    </div>
</template>
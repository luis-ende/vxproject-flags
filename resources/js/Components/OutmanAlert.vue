<script setup>
import {onMounted, ref} from "vue";

const props = defineProps({
    id: Number,
});

const alerting = ref(false);

onMounted(() => {
    window.Echo.channel(`outman.user.${props.id}`)
        .listen('UserAuthenticatedEvent', () => {
            alerting.value = true
            setTimeout(() => {
                window.location ='/login'
            }, 4000)
        })
});
</script>

<template>
    <div class="w-full" v-if="alerting">
        <div class="bg-red-100 text-red-900 rounded text-center" role="alert">
            Tu cuenta está siendo utilizada en otro lugar. Esta sesión ha sido cerrada.
        </div>
    </div>
</template>
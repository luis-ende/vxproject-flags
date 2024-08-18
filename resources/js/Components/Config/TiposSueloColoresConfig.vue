<script setup>
import {onMounted, reactive, ref} from "vue";
import ConfirmationModal from "../ConfirmationModal.vue";
import PrimaryButton from "../PrimaryButton.vue";
import SecondaryButton from "../SecondaryButton.vue";
import {useForm} from "@inertiajs/vue3";

const props = defineProps({
    groupId: {
        type: Number
    },
    colors: {
        type: Object,
    }
});

let updateGroupForm = useForm({
    tipoSueloColors: {...props.colors},
});
const sismoColorsDialog = ref(false)

const updateColors = () => {
    updateGroupForm.put(route('config-panel.tipos-suelo-colors', [props.groupId]), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            sismoColorsDialog.value = false
        },
    });
}

</script>

<template>
    <button type="button"
            class="ml-5 w-40 h-8 vxproject-button align-top"
            @click="sismoColorsDialog = true">Colores sismo</button>

    <ConfirmationModal :show="sismoColorsDialog === true" @close="sismoColorsDialog = false" max-width="sm">
        <template #title>
            Colores para señales de sismo
        </template>

        <template #content>
            <div v-for="(color, key) in updateGroupForm.tipoSueloColors" class="">
                <div class="flex flex-row flex-2 border-b-2 p-1 items-center">
                    <span class="w-40 font-extrabold">
                        {{ key }}
                    </span>
                    <span>
                        <input type="color"
                               name="color"
                               id="key"
                               v-model="updateGroupForm.tipoSueloColors[key]">
                    </span>
                </div>
            </div>
        </template>

        <template #footer>
            <SecondaryButton @click="sismoColorsDialog = false">
                Cancelar
            </SecondaryButton>

            <PrimaryButton
                class="ml-3"
                @click="updateColors"
            >
                Guardar
            </PrimaryButton>
        </template>
    </ConfirmationModal>
</template>

<style scoped>

</style>

<script setup>

import {ref, watch, computed} from "vue";
import ConfirmationModal from "../ConfirmationModal.vue";
import SecondaryButton from "../SecondaryButton.vue";
import PrimaryButton from "../PrimaryButton.vue";
import {router} from "@inertiajs/vue3";
import { useFlagInfoStore } from '../../stores/FlagInfoStore'

const flagInfoStore = useFlagInfoStore()

const props = defineProps({
    flagInfo: Object,
    dataLoading: {
        type: Boolean,
        default: false,
    }
});

const flagNotes = ref('')
const flagNotesDialog = computed({
    get() {
        return flagInfoStore.flagInfoNotesDialogShow
    },
    set(newValue) {
        flagInfoStore.flagInfoNotesDialogShow = newValue
    }
})
const successUpdate = ref(false);
const notesLoading = ref(false);

const loadFlagNotes = () => {
    successUpdate.value = false
    notesLoading.value = true
    fetch('/vx-flags/notes/' + props.flagInfo.id).then(res => res.json()).then(json => {
        if (json.notes) {
            flagNotes.value = json.notes
        } else {
            flagNotes.value = null
        }
    }).finally(() => notesLoading.value = false)
}

const updateFlagNotes = () => {
    const data = { notes: flagNotes.value }
    router.put(`/vx-flags/notes/${props.flagInfo.id}`, data, {
        only: ['dashboard'],
        preserveScroll: true,
        onSuccess: () => successUpdate.value = true
    })
}

watch(flagNotesDialog, (value) => {
    if (value === true) {
        loadFlagNotes()
    } else {
        flagNotes.value = ''
    }
})

watch(props.flagInfo, (value) => {
    if (flagNotesDialog.value === true) {
        loadFlagNotes()
    }
})

</script>

<template>
    <div class="basis-1/2 flex flex-row items-end justify-end">
        <button type="button"
                class="vxproject-button w-12 h-6"
                title="Editar notas"
                :disabled="dataLoading"
                :class="{ 'opacity-25': dataLoading }"
                @click="flagNotesDialog = true">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor"><path d="M200,88l-72,72H96V128l72-72Z" opacity="0.2"></path><path d="M229.66,58.34l-32-32a8,8,0,0,0-11.32,0l-96,96A8,8,0,0,0,88,128v32a8,8,0,0,0,8,8h32a8,8,0,0,0,5.66-2.34l96-96A8,8,0,0,0,229.66,58.34ZM124.69,152H104V131.31l64-64L188.69,88ZM200,76.69,179.31,56,192,43.31,212.69,64ZM224,128v80a16,16,0,0,1-16,16H48a16,16,0,0,1-16-16V48A16,16,0,0,1,48,32h80a8,8,0,0,1,0,16H48V208H208V128a8,8,0,0,1,16,0Z"></path></svg>
        </button>
    </div>

    <ConfirmationModal :show="flagNotesDialog === true" icon="none"
                       max-width="md" custom-position="top-1/2 right-0 bottom-0 left-1/9">
        <template #title>
            <span class="text-sm">Notas del sitio {{ flagInfo.sitio }}</span>
        </template>

        <template #content>
            <span class="block text-gray-700" v-if="notesLoading">Cargando notas...</span>
            <textarea
                v-if="!notesLoading"
                v-model="flagNotes"
                class="border focus:ring-vxproject-secondary border-vxproject-secondary"
                @input="successUpdate = false" />
            <span v-if="successUpdate"  class="block text-xs text-green-600">Sitio actualizado</span>
        </template>

        <template #footer>
            <SecondaryButton @click="flagNotesDialog = false">
                Cerrar
            </SecondaryButton>

            <PrimaryButton
                :disable="dataLoading"
                class="ml-3"
                @click="updateFlagNotes()"
            >
                Actualizar
            </PrimaryButton>
        </template>
    </ConfirmationModal>
</template>

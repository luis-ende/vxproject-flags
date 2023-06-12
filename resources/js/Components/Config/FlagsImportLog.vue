<script setup>
import {ref} from "vue";
import {DateTime} from "luxon";
import ConfirmationModal from "../ConfirmationModal.vue";
import PrimaryButton from "../PrimaryButton.vue";
import SecondaryButton from "../SecondaryButton.vue";
import {useForm} from "@inertiajs/vue3";

const props = defineProps({
    tipoSueloSitiosImport: {
        type: Object,
    }
});

const emit = defineEmits(['importFinished']);

const showImportDetails = ref(false);
const tipoSueloImportLog = JSON.parse(props.tipoSueloSitiosImport.import_log);

const importForm = useForm({});
const importConfirmation = ref(false);
const importFinished = ref(false);

const formatDate = (date) => {
    if (date) {
        const dateToFormat = new Date(date);

        return dateToFormat.toLocaleString('es-MX',
            { ...DateTime.DATETIME_MED_WITH_SECONDS })
    }
};

const runImport = () => {
    importForm.post(route('vx-flags.tipos-suelo.import'), {
        preserveScroll: true,
        onSuccess: () => {
            importFinished.value = true;
            setTimeout(() => {
                importFinished.value = false;
                importConfirmation.value = false;
                emit('importFinished');
            }, 2000)
        },
        //onError: () => ,
        // onFinish: () => {
        // },
    });
};

</script>

<template>
    <div class="my-5 text-sm text-stone-700 flex flex-col w-fit">
        <p>Ultima importación: {{ formatDate(tipoSueloSitiosImport?.created_at) }} </p>
        <a href="#"
           @click="showImportDetails = !showImportDetails"
           class="text-vxproject-primary">{{ showImportDetails ? 'Ocultar detalles' : 'Ver detalles' }}</a>
        <div class="bg-neutral-200 text-xs rounded-lg h-28 overflow-y-scroll p-3"
             v-show="showImportDetails">
            <p>Archivo: {{ tipoSueloImportLog?.archivo }}</p>
            <p>Estatus: {{ tipoSueloImportLog?.status }}</p>
            <p>Renglones procesados: {{ tipoSueloImportLog?.num_importados }}</p>
            <table v-if="tipoSueloImportLog?.importados_info?.length > 0" class="table-auto mt-3">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Operación</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in tipoSueloImportLog.importados_info">
                        <td class="w-32">{{ item.key }}</td>
                        <td>{{ item.action }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <button type="button"
                class="my-3 w-40 vxproject-button"
                @click="importConfirmation = true">Importar sitios</button>
    </div>

    <ConfirmationModal :show="importConfirmation === true" @close="importConfirmation = false">
        <template #title>
            Tipos de suelo
        </template>

        <template #content>
            <p v-show="!importFinished && !importForm.processing">¿Deseas ejecutar ahora el proceso de importación/actualización de sitios del grupo Tipo de suelo?</p>
            <p v-show="!importFinished && importForm.processing" class="font-bold">Ejecutando importación...</p>
            <p v-show="importFinished" class="font-bold">Importación finalizada.</p>
        </template>

        <template #footer>
            <SecondaryButton v-show="!importFinished && !importForm.processing"
                             @click="importConfirmation = false">
                Cancelar
            </SecondaryButton>

            <PrimaryButton
                v-show="!importFinished"
                class="ml-3"
                :class="{ 'opacity-25': importForm.processing }"
                :disabled="importForm.processing"
                @click="runImport"
            >
                Aceptar
            </PrimaryButton>
        </template>
    </ConfirmationModal>
</template>
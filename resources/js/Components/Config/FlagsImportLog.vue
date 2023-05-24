<script setup>
import {ref} from "vue";
import {DateTime} from "luxon";

const props = defineProps({
    tipoSueloSitiosImport: {
        type: Object,
    }
});

const showImportDetails = ref(false);
const tipoSueloImportLog = JSON.parse(props.tipoSueloSitiosImport.import_log);

const formatDate = (date) => {
    if (date) {
        const dateToFormat = new Date(date);

        return dateToFormat.toLocaleString('es-MX',
            { ...DateTime.DATETIME_MED_WITH_SECONDS })
    }
};

</script>

<template>
    <div class="my-5 text-sm text-stone-700 flex flex-col w-fit">
        <p>Ultima importación: {{ formatDate(tipoSueloSitiosImport?.created_at) }} </p>
        <a href="#"
           @click="showImportDetails = !showImportDetails"
           class="text-vxproject-primary">{{ showImportDetails ? 'Ocultar detalles' : 'Ver detalles' }}</a>
        <div class="bg-neutral-200 text-xs rounded-lg h-28 overflow-y-scroll p-3" v-show="showImportDetails">
            <p>Archivo: {{ tipoSueloImportLog?.archivo }}</p>
            <p>Estatus: {{ tipoSueloImportLog?.status }}</p>
            <p>Renglones procesados: {{ tipoSueloImportLog?.num_importados }}</p>
            <table v-if="tipoSueloImportLog?.importados_info.length > 0" class="table-auto mt-3">
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
    </div>
</template>
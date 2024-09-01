<script setup>
import FlagNotes from '../Flags/FlagNotes.vue'

const props = defineProps({
    flagInfo: Object,
    dataLoading: {
        type: Boolean,
        default: false,
    }
});

const formatCoordinates = (text) => {
    if (!text) {
        return '';
    }

    let dms = text.split(' ');
    const sec = dms[2];
    dms[2] = parseFloat(sec).toFixed(2).padStart(5, '0') + "''";

    return dms.join(' ');
}

</script>

<template>
    <div v-show="dataLoading" role="status" class="py-32 text-center">
        <svg aria-hidden="true" class="inline w-10 h-10 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-vxproject-primary" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
        </svg>
        <span class="sr-only">Loading...</span>
    </div>
    <div v-show="!dataLoading" class="uppercase text-sm">
        <div class="flex flex-row my-7">
            <span class="basis-1/2 text-base md:text-lg font-bold">Sitio existente</span>
            <FlagNotes :flag-info="flagInfo" :data-loading="dataLoading" />
        </div>
        <div class="flex flex-col md:flex-row md:space-x-10">
            <div class="md:basis-1/2 grid grid-cols-2 grid-rows-6">
                <div class="text-right border-b border-b-vxproject-secondary">Sitio:</div>
                <div class="border-b border-b-vxproject-secondary pl-5">{{ flagInfo.sitio }}</div>
                <div class="text-right border-b border-b-vxproject-secondary">Latitud:</div>
                <div class="border-b border-b-vxproject-secondary pl-5">{{ formatCoordinates(flagInfo.latitud) }}</div>
                <div class="text-right border-b border-b-vxproject-secondary">Longitud:</div>
                <div class="border-b border-b-vxproject-secondary pl-5">{{ formatCoordinates(flagInfo.longitud) }}</div>
                <div class="text-right border-b border-b-vxproject-secondary">Sismo:</div>
                <div class="border-b border-b-vxproject-secondary pl-5">{{ flagInfo.sismo }}</div>
                <div class="text-right border-b border-b-vxproject-secondary">Zona:</div>
                <div class="border-b border-b-vxproject-secondary pl-5">{{ flagInfo.zona }}</div>
                <div class="text-right border-b border-b-vxproject-secondary">Tipo terreno:</div>
                <div class="border-b border-b-vxproject-secondary pl-5">{{ flagInfo.tipo_terreno }}</div>
            </div>
            <div class="md:basis-1/2 grid grid-cols-2 md:grid-cols-3 grid-rows-6">
                <div class="text-right border-b border-b-vxproject-secondary">Capacidad:</div>
                <div class="md:col-span-2 border-b border-b-vxproject-secondary pl-5">
                    {{ flagInfo.capacidad !== 'NA' ? parseFloat(flagInfo.capacidad).toFixed(2) : 'NA' }}
                    <span v-show="flagInfo.capacidad !== 'NA'" class="lowercase">t/m&#178;</span>
                </div>
                <div class="text-right border-b border-b-vxproject-secondary"><span class="lowercase">Ø</span> Terreno:</div>
                <div class="md:col-span-2 border-b border-b-vxproject-secondary pl-5">
                    {{ flagInfo.o_terreno !== 'NA' ? parseFloat(flagInfo.o_terreno).toFixed(2) : 'NA' }}<span v-show="flagInfo.o_terreno !== 'NA'">°</span>
                </div>
                <div class="text-right border-b border-b-vxproject-secondary"><span class="lowercase">δ</span> Terreno:</div>
                <div class="md:col-span-2 border-b border-b-vxproject-secondary pl-5">
                    {{ flagInfo.d_terreno !== 'NA' ? parseFloat(flagInfo.d_terreno).toFixed(2) : 'NA' }}
                    <span v-show="flagInfo.d_terreno !== 'NA'" class="lowercase">t/m&#179;</span>
                </div>
                <div class="text-right border-b border-b-vxproject-secondary">DF Min:</div>
                <div class="md:col-span-2 border-b border-b-vxproject-secondary pl-5">
                    {{ flagInfo.df_min !== 'NA' ? parseFloat(flagInfo.df_min).toFixed(2).toString().padStart(5, '0') : 'NA' }}<span v-show="flagInfo.df_min !== 'NA'" class="lowercase">m</span>
                </div>
                <div class="text-right border-b border-b-vxproject-secondary">Proyecto:</div>
                <div class="md:col-span-2 border-b border-b-vxproject-secondary pl-5 text-[0.775rem]">
                    {{ flagInfo.proyecto }}
                </div>
                <div class="text-right border-b border-b-vxproject-secondary">Altura:</div>
                <div class="md:col-span-2 border-b border-b-vxproject-secondary pl-5">
                    {{ parseFloat(flagInfo.altura).toFixed(2) }}<span class="lowercase">m</span>
                </div>
            </div>
        </div>
        <span class="block mt-10 mb-5 border-b border-b-vxproject-secondary">Descripción suelo:</span>
        <div class="h-16 overflow-y-auto" v-html="flagInfo.descripcion_suelo">
        </div>
        <span class="block mt-10 mb-5 border-b border-b-vxproject-secondary">Observaciones:</span>
        <div class="h-48 overflow-y-auto" v-html="flagInfo.observaciones">
        </div>
    </div>
</template>

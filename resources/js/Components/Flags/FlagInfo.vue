<script setup>
defineProps({
    flagInfo: Object
});

</script>

<template>
    <div class="uppercase text-sm">
        <span class="block my-7 text-lg font-bold">Sitio existente</span>
        <div class="flex flex-row space-x-10">
            <div class="basis-1/2 grid grid-cols-2 grid-rows-6">
                <div class="text-right border-b border-b-vxproject-secondary">Sitio:</div>
                <div class="border-b border-b-vxproject-secondary pl-5">{{ flagInfo.sitio }}</div>
                <div class="text-right border-b border-b-vxproject-secondary">Latitud:</div>
                <div class="border-b border-b-vxproject-secondary pl-5">{{ flagInfo.latitud }}</div>
                <div class="text-right border-b border-b-vxproject-secondary">Longitud:</div>
                <div class="border-b border-b-vxproject-secondary pl-5">{{ flagInfo.longitud2 }}</div>
                <div class="text-right border-b border-b-vxproject-secondary">Sismo:</div>
                <div class="border-b border-b-vxproject-secondary pl-5">{{ flagInfo.sismo }}</div>
                <div class="text-right border-b border-b-vxproject-secondary">Zona:</div>
                <div class="border-b border-b-vxproject-secondary pl-5">{{ flagInfo.zona }}</div>
                <div class="text-right border-b border-b-vxproject-secondary">Tipo terreno:</div>
                <div class="border-b border-b-vxproject-secondary pl-5">{{ flagInfo.tipo_terreno }}</div>
            </div>
            <div class="basis-1/2  grid grid-cols-3 grid-rows-6">
                <div class="text-right border-b border-b-vxproject-secondary">Capacidad:</div>
                <div class="col-span-2 border-b border-b-vxproject-secondary pl-5">{{ flagInfo.capacidad }} <span class="lowercase">t/m&#179;</span></div>
                <div class="text-right border-b border-b-vxproject-secondary"><span class="lowercase">Ø</span> Terreno:</div>
                <div class="col-span-2 border-b border-b-vxproject-secondary pl-5">{{ flagInfo.o_terreno }}°</div>
                <div class="text-right border-b border-b-vxproject-secondary"><span class="lowercase">δ</span> Terreno:</div>
                <div class="col-span-2 border-b border-b-vxproject-secondary pl-5">{{ flagInfo.d_terreno }} <span class="lowercase">t/m&#179;</span></div>
                <div class="text-right border-b border-b-vxproject-secondary">DF Min:</div>
                <div class="col-span-2 border-b border-b-vxproject-secondary pl-5">{{ flagInfo.df_min }}<span class="lowercase">m</span></div>
                <div class="text-right border-b border-b-vxproject-secondary">Proyecto:</div>
                <div class="col-span-2 border-b border-b-vxproject-secondary pl-5">{{ flagInfo.proyecto }}</div>
                <div class="text-right border-b border-b-vxproject-secondary">Altura:</div>
                <div class="col-span-2 border-b border-b-vxproject-secondary pl-5">{{ flagInfo.altura }}<span class="lowercase">m</span></div>
            </div>
        </div>
        <span class="block mt-10 mb-5 border-b border-b-vxproject-secondary">Observaciones:</span>
        <div class="h-48 overflow-y-auto" v-html="flagInfo.observaciones">
        </div>
    </div>
</template>
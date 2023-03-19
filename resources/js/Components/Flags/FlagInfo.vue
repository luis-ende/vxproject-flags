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
                <div class="text-right border-b border-b-vxproject-primary">Sitio:</div>
                <div class="border-b border-b-vxproject-primary pl-5">{{ flagInfo.sitio }}</div>
                <div class="text-right border-b border-b-vxproject-primary">Latitud:</div>
                <div class="border-b border-b-vxproject-primary pl-5">{{ flagInfo.latitud }}</div>
                <div class="text-right border-b border-b-vxproject-primary">Longitud:</div>
                <div class="border-b border-b-vxproject-primary pl-5">{{ flagInfo.longitud2 }}</div>
                <div class="text-right border-b border-b-vxproject-primary">Sismo:</div>
                <div class="border-b border-b-vxproject-primary pl-5">{{ flagInfo.sismo }}</div>
                <div class="text-right border-b border-b-vxproject-primary">Zona:</div>
                <div class="border-b border-b-vxproject-primary pl-5">{{ flagInfo.zona }}</div>
                <div class="text-right border-b border-b-vxproject-primary">Tipo terreno:</div>
                <div class="border-b border-b-vxproject-primary pl-5">{{ flagInfo.tipo_terreno }}</div>
            </div>
            <div class="basis-1/2  grid grid-cols-3 grid-rows-6">
                <div class="text-right border-b border-b-vxproject-primary">Capacidad:</div>
                <div class="col-span-2 border-b border-b-vxproject-primary pl-5">{{ flagInfo.capacidad }} <span class="lowercase">t/m&#179;</span></div>
                <div class="text-right border-b border-b-vxproject-primary"><span class="lowercase">Ø</span> Terreno:</div>
                <div class="col-span-2 border-b border-b-vxproject-primary pl-5">{{ flagInfo.o_terreno }}°</div>
                <div class="text-right border-b border-b-vxproject-primary"><span class="lowercase">δ</span> Terreno:</div>
                <div class="col-span-2 border-b border-b-vxproject-primary pl-5">{{ flagInfo.d_terreno }} <span class="lowercase">t/m&#179;</span></div>
                <div class="text-right border-b border-b-vxproject-primary">DF Min:</div>
                <div class="col-span-2 border-b border-b-vxproject-primary pl-5">{{ flagInfo.df_min }}<span class="lowercase">m</span></div>
                <div class="text-right border-b border-b-vxproject-primary">Proyecto:</div>
                <div class="col-span-2 border-b border-b-vxproject-primary pl-5">{{ flagInfo.proyecto }}</div>
                <div class="text-right border-b border-b-vxproject-primary">Altura:</div>
                <div class="col-span-2 border-b border-b-vxproject-primary pl-5">{{ flagInfo.altura }}<span class="lowercase">m</span></div>
            </div>
        </div>
        <span class="block mt-10 mb-5 border-b border-b-vxproject-primary">Observaciones:</span>
        <div class="h-48 overflow-y-auto" v-html="flagInfo.observaciones">
        </div>
    </div>
</template>
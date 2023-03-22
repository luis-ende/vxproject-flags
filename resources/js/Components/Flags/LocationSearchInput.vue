<script setup>

import {onMounted, reactive, ref} from "vue";

const emit = defineEmits([
    'search',
    'update:lat',
    'update:lng',
]);

const sistemaC = ref('grados');

const props = defineProps({
    lat: {
        type: Number,
        default: 0,
    },
    lng: {
        type: Number,
        default: 0,
    },
});

const latData = reactive({
    deg: 0,
    min: 0,
    sec: 0,
});

const lngData = reactive({
    deg: 0,
    min: 0,
    sec: 0,
});

const errorMessage = ref('');

onMounted(() => {
    const dmsCoordinates = decToDms(props.lat, props.lng);
    latData.deg = dmsCoordinates.lat.deg;
    latData.min = dmsCoordinates.lat.min;
    latData.sec = dmsCoordinates.lat.sec;
    lngData.deg = dmsCoordinates.lng.deg;
    lngData.min = dmsCoordinates.lng.min;
    lngData.sec = dmsCoordinates.lng.sec;
});

const onClickSearchButton = () => {
    let coordinates = { lat: props.lat, lng: props.lng }
    if (sistemaC.value === 'grados') {
        coordinates = dmsToDec(latData, lngData);
    } else if (sistemaC.value === 'decimales') {
        const dmsCoordinates = decToDms(props.lat, props.lng);
        latData.deg = dmsCoordinates.lat.deg;
        latData.min = dmsCoordinates.lat.min;
        latData.sec = dmsCoordinates.lat.sec;
        lngData.deg = dmsCoordinates.lng.deg;
        lngData.min = dmsCoordinates.lng.min;
        lngData.sec = dmsCoordinates.lng.sec;
    }

    emit('update:lat', coordinates.lat);
    emit('update:lng', coordinates.lng);

    emit('search');
};

const validateNumericInput = (i) => {
    i.value = i.value.replace(/[^-0-9.]/g, '').replace(/(\..*)\./g, '$1');
};

const validateCNumericInput = (i) => {
    errorMessage.value = '';
    if (i.name.endsWith('_g')) {
        if (i.name.startsWith('lat')) {
            if (!(i.value >= -90 && i.value <= 90)) {
                errorMessage.value = 'Rango permitido de grados de latitud: entre -90 y 90.';
                i.focus();
            }
        }

        if (i.name.startsWith('lng')) {
            if (!(i.value >= -180 && i.value <= 180)) {
                errorMessage.value = 'Rango permitido de grados de latitud: entre -180 y 180.';
                i.focus();
            }
        }
    }

    if (i.name.endsWith('_m') || i.name.endsWith('_s')) {
        if (!(i.value >= 0 && i.value <= 59)) {
            errorMessage.value = 'Rango permitido: entre 0 y 59.';
            i.focus();
        }
    }
};

const dmsToDec = (latData, lngData) => {
    const absdlat = Math.round(Math.abs(latData.deg) * 1000000);
    const absmlat = Math.round(Math.abs(latData.min) * 1000000);
    const absslat = Math.round(Math.abs(latData.sec) * 1000000);
    const latsign = latData.deg < 0 ? -1 : 1;

    const absdlon = Math.round(Math.abs(lngData.deg) * 1000000);
    const absmlon = Math.round(Math.abs(lngData.min) * 1000000);
    const absslon = Math.round(Math.abs(lngData.sec) * 1000000);
    const lonsign = lngData.deg < 0 ? -1 : 1;

    return {
        lat: Math.round(absdlat + (absmlat/60.) + (absslat/3600.) ) * latsign/1000000,
        lng: Math.round(absdlon + (absmlon/60) + (absslon/3600) ) * lonsign/1000000,
    };
}

const decToDms = (latData, lngData) => {
    const abslat = Math.abs( Math.round(props.lat * 1000000.));
    const abslng = Math.abs( Math.round(props.lng * 1000000.));
    const latsign = props.lat < 0 ? -1 : 1;
    const lonsign = props.lng < 0 ? -1 : 1;

    return {
        lat: {
            deg: Math.floor(abslat / 1000000) * latsign,
            min: Math.floor(  ((abslat/1000000) - Math.floor(abslat/1000000)) * 60),
            sec: Math.floor(((((abslat/1000000) - Math.floor(abslat/1000000)) * 60) - Math.floor(((abslat/1000000) - Math.floor(abslat/1000000)) * 60)) * 100000) *60/100000,
        },
        lng: {
            deg: Math.floor(abslng / 1000000) * lonsign,
            min: Math.floor(  ((abslng/1000000) - Math.floor(abslng/1000000)) * 60),
            sec: Math.floor(((((abslng/1000000) - Math.floor(abslng/1000000)) * 60) - Math.floor(((abslng/1000000) - Math.floor(abslng/1000000)) * 60)) * 100000) *60/100000,
        }
    }
}

</script>

<template>
    <div class="flex flex-col space-y-4">
        <fieldset class="flex flex-row space-x-4">
            <span class="uppercase font-bold">Sitio:</span>
            <div>
                <input type="radio" id="grados" name="sistema_coordenadas" value="grados" v-model="sistemaC"
                       class="w-4 h-4 text-vxproject-secondary border-vxproject-secondary focus:ring-vxproject-secondary rounded-full"
                       checked>
                <label class="ml-2" for="huey">Grados</label>
            </div>
            <div class="inline-block">
                <input type="radio" id="decimales" name="sistema_coordenadas" value="decimales" v-model="sistemaC"
                       class="w-4 h-4 text-vxproject-secondary border-vxproject-secondary focus:ring-vxproject-secondary rounded-full">
                <label class="ml-2" for="dewey">Decimales</label>
            </div>
        </fieldset>
        <div class="flex flex-row space-x-4">
            <div v-if="sistemaC === 'grados'" class="flex flex-row space-x-2">
                <input type="text"
                       name="lat_g"
                       v-model="latData.deg"
                       @input="validateNumericInput($event.target)"
                       @blur="validateCNumericInput($event.target)"
                       class="border focus:ring-vxproject-secondary border-vxproject-secondary inline-block w-16 h-7">
                <input type="text"
                       name="lat_m"
                       v-model="latData.min"
                       @input="validateNumericInput($event.target)"
                       @blur="validateCNumericInput($event.target)"
                       class="border focus:ring-vxproject-secondary border-vxproject-secondary inline-block w-16 h-7">
                <input type="text"
                       name="lat_s"
                       v-model="latData.sec"
                       @input="validateNumericInput($event.target)"
                       @blur="validateCNumericInput($event.target)"
                       class="border focus:ring-vxproject-secondary border-vxproject-secondary inline-block w-16 h-7">
                <span class="font-bold self-end">,</span>
                <input type="text"
                       name="lng_g"
                       v-model="lngData.deg"
                       @input="validateNumericInput($event.target)"
                       @blur="validateCNumericInput($event.target)"
                       class="border focus:ring-vxproject-secondary border-vxproject-secondary inline-block w-16 h-7">
                <input type="text"
                       name="lng_m"
                       v-model="lngData.min"
                       @input="validateNumericInput($event.target)"
                       @blur="validateCNumericInput($event.target)"
                       class="border focus:ring-vxproject-secondary border-vxproject-secondary inline-block w-16 h-7">
                <input type="text"
                       name="lng_s"
                       v-model="lngData.sec"
                       @input="validateNumericInput($event.target)"
                       @blur="validateCNumericInput($event.target)"
                       class="border focus:ring-vxproject-secondary border-vxproject-secondary inline-block w-16 h-7">
            </div>
            <div v-if="sistemaC === 'decimales'" class="flex flex-row space-x-2">
                <input
                    ref="inputLatitude"
                    class="border focus:ring-vxproject-secondary border-vxproject-secondary inline-block w-44 h-7"
                    type="text"
                    :value="lat"
                    @input="validateNumericInput($event.target); $emit('update:lat', parseFloat($event.target.value))"
                    placeholder="lat">
                <span class="font-bold self-end">,</span>
                <input
                    ref="inputLongitude"
                    class="border focus:ring-vxproject-secondary border-vxproject-secondary inline-block w-44 h-7"
                    type="text"
                    :value="lng"
                    @input="validateNumericInput($event.target); $emit('update:lng', parseFloat($event.target.value))"
                    placeholder="lng">
            </div>
            <button type="button" @click="onClickSearchButton()"
                    class="font-montserrat uppercase text-vxproject-primary font-bold text-sm rounded-full border border-vxproject-primary px-3">Buscar</button>
        </div>
        <span class="text-xs text-red-500 block">{{ errorMessage }}</span>
    </div>
</template>
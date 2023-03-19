<script setup>

const emit = defineEmits([
    'search',
    'update:lat',
    'update:lng',
]);

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

const onClickSearchButton = () => {
    emit('search');
};

</script>

<template>
    <div class="flex flex-row space-x-4 items-center">
        <label class="uppercase font-bold">Sitio</label>
        <div>
            <input
                ref="inputLatitude"
                class="border focus:ring-vxproject-primary border-vxproject-primary inline-block w-44 h-7"
                type="number"
                :value="lat"
                @input="$emit('update:lat', parseFloat($event.target.value))"
                placeholder="lat">
            <span class="font-bold"> - </span>
            <input
                ref="inputLongitude"
                class="border focus:ring-vxproject-primary border-vxproject-primary inline-block w-44 h-7"
                type="number"
                :value="lng"
                @input="$emit('update:lng', parseFloat($event.target.value))"
                placeholder="lng">
        </div>
        <button type="button" @click="onClickSearchButton()"
            class="uppercase text-vxproject-primary font-bold text-sm rounded-full border border-vxproject-primary px-3">Buscar</button>
    </div>
</template>
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import FlagsMap from "../Components/FlagsMap.vue";
import FlagsGroupSelect from "../Components/FlagsGroupSelect.vue";
import {onMounted, ref} from "vue";
import FlagInfo from "../Components/FlagInfo.vue";
import MapLocationSearch from "../Components/MapLocationSearch.vue";

const props = defineProps({
    flagsGroups: {
        type: Array,
        default: [],
    },
});

const map = ref(null);
const markers = ref([]);

onMounted(() => {
    map.value = L.map('map').setView([19.3886, -99.16335], 6);
    // @todo Get url from global app settings
    L.tileLayer('https://api.maptiler.com/maps/basic-v2/{z}/{x}/{y}.png?key=aoG5PxooEJs06nNutrZH', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map.value)
});

const groupsChange = (groupId, checked) => {
    const currentGroup = props.flagsGroups.find(g => g.id === groupId );
    if (checked) {
        loadFlags(currentGroup);
    } else {
        clearFlags(currentGroup);
    }
};

const loadFlags = (group) => {
    const defIcon = {
        iconUrl: 'marker-icon.png',
        iconRetinaUrl: 'marker-icon-2x.png',
        iconSize: [15, 41],
        iconAnchor: [12, 41],
        className: 'vxproject-address-flag',
    };

    let layer = [];
    group.flags.forEach(flag => {
        let desc = flag.description;
        let customIcon = L.divIcon({
            ...defIcon,
            html: `<div><svg fill="${group.color}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Free 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2023 Fonticons, Inc. --><path d="M32 144a144 144 0 1 1 288 0A144 144 0 1 1 32 144zM176 80c8.8 0 16-7.2 16-16s-7.2-16-16-16c-53 0-96 43-96 96c0 8.8 7.2 16 16 16s16-7.2 16-16c0-35.3 28.7-64 64-64zM144 480V317.1c10.4 1.9 21.1 2.9 32 2.9s21.6-1 32-2.9V480c0 17.7-14.3 32-32 32s-32-14.3-32-32z"></path></svg><div class="vxproject-address-label">${desc}</div></div>`,
        });
        let marker = L.marker([flag.latitude, flag.longitude], { icon: customIcon });
        layer.push(marker);
        map.value.addLayer(marker);
        marker.bindPopup(desc);
    });

    markers.value.push({
        id: group.id,
        markers: layer
    });
};

const clearFlags = (group) => {
    let groupMarkers = markers.value.find(gm => gm.id === group.id);
    groupMarkers.markers.forEach(marker => {
        map.value.removeLayer(marker);
    });
    markers.value = markers.value.filter(gm => gm.id !== group.id);
};

</script>

<template>
    <AppLayout title="Escritorio">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Escritorio
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-full mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="flex flex-row h-[750px]">
                        <div class="basis-2/3 p-5">
<!--                            <FlagsMap
                                :map="map"
                            />-->
                        </div>
                        <div class="basis-1/3 p-5">
                            <div class="my-10">
                                <MapLocationSearch />
                            </div>
                            <div class="my-10">
                                <FlagsGroupSelect
                                    :groups="flagsGroups"
                                    @groups-change="groupsChange"
                                />
                            </div>
                            <div>
                                <FlagInfo />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

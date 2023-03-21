<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import FlagsMap from "../Components/Flags/FlagsMap.vue";
import FlagsGroupSelect from "../Components/Flags/FlagsGroupSelect.vue";
import {onMounted, reactive, ref, toRaw} from "vue";
import FlagInfo from "../Components/Flags/FlagInfo.vue";
import LocationSearchInput from "../Components/Flags/LocationSearchInput.vue";

const props = defineProps({
    flagsGroups: {
        type: Array,
        default: [],
    },
});

// @todo Consider: https://stackoverflow.com/questions/73542576/leaflet-error-when-zooming-after-closing-popup
const map = ref(null);
const markersGroup = ref([]);
const currentLocationMarker = ref(null);
let searchLocation = reactive({
    lat: 19.3886,
    lng: -99.16335,
    zoom: 6,
});
const violetIcon = new L.Icon({
    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-violet.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
});
const currentVxFlagInfo = reactive({
    active: false,
    groupType: 2,
});
const vxFlagInfoSuelo = reactive({
    altura: null,
    capacidad: null,
    d_terreno: null,
    df_min: null,
    latitud: null,
    longitud2: null,
    n: null,
    o_terreno: null,
    observaciones: null,
    proyecto: null,
    sismo: null,
    sitio: null,
    tipo_terreno: null,
    zona: null,
});
const vxFlagInfoTemperatura = reactive({
    temperatura: null,
});

onMounted(() => {
    map.value = L.map('map').setView([searchLocation.lat, searchLocation.lng], searchLocation.zoom);
    map.value.on('click', onMapClick)
    map.value.on('dblclick', onMapDoubleClick)
    // @todo Get url from global app settings
    //L.tileLayer('https://api.maptiler.com/maps/basic-v2/{z}/{x}/{y}.png?key=aoG5PxooEJs06nNutrZH', {
    L.tileLayer('https://api.maptiler.com/maps/winter-v2/{z}/{x}/{y}.png?key=aoG5PxooEJs06nNutrZH', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map.value)
});

const onMapClick = (e) => {
    clearVxFlagInfoPanel();
}

const onMapDoubleClick = (e) => {
    searchLocation.lat = e.latlng.lat;
    searchLocation.lng = e.latlng.lng;
    setSearchLocation();
}

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

    let layerMarkers = [];
    group.flags.forEach(flag => {
        let desc = flag.description;
        //@debug desc = desc + ' id: ' + flag.id;
        let customIcon = L.divIcon({
            ...defIcon,
            html: `<div><svg fill="${group.color}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Free 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2023 Fonticons, Inc. --><path d="M32 144a144 144 0 1 1 288 0A144 144 0 1 1 32 144zM176 80c8.8 0 16-7.2 16-16s-7.2-16-16-16c-53 0-96 43-96 96c0 8.8 7.2 16 16 16s16-7.2 16-16c0-35.3 28.7-64 64-64zM144 480V317.1c10.4 1.9 21.1 2.9 32 2.9s21.6-1 32-2.9V480c0 17.7-14.3 32-32 32s-32-14.3-32-32z"></path></svg></div>`,
        });
        let marker = L.marker([flag.latitude, flag.longitude], {
            icon: customIcon,
            vxFlagId: flag.id
        });
        marker.on('click', onMarkerClick);
        layerMarkers.push(marker);
        marker.addTo(toRaw(map.value));
        marker.bindPopup(desc);
    });

    markersGroup.value.push({
        id: group.id,
        markers: layerMarkers
    });
};

const clearFlags = (group) => {
    clearVxFlagInfoPanel();
    let groupMarkers = markersGroup.value.find(gm => gm.id === group.id);
    groupMarkers.markers.forEach(marker => {
        map.value.removeLayer(marker);
    });
    markersGroup.value = markersGroup.value.filter(gm => gm.id !== group.id);
};

const setSearchLocation = () => {
    clearVxFlagInfoPanel();
    let searchLocationInfo = {
        ...searchLocation,
    }
    searchLocationInfo.zoom = 9;
    setMapLocation(searchLocationInfo)
};

const setMapLocation = (searchLocationInfo) => {
    map.value.setView([searchLocationInfo.lat, searchLocationInfo.lng], searchLocationInfo.zoom);

    if (currentLocationMarker.value) {
        map.value.removeLayer(currentLocationMarker.value);
    }

    let marker = L.marker([searchLocationInfo.lat, searchLocationInfo.lng], { icon: violetIcon });
    map.value.addLayer(marker);
    currentLocationMarker.value = marker;
}

const onMarkerClick = (e) => {
    const currentVxFlagId = e.target.options.vxFlagId;
    currentVxFlagInfo.active = true;
    currentVxFlagInfo.groupType =
        props.flagsGroups.find(fg => fg.flags.find(f => f.id === currentVxFlagId)).type;
    loadVxFlagInfo(currentVxFlagId);
};

const loadVxFlagInfo = (vxFlagId) => {
    switch (currentVxFlagInfo.groupType) {
        case 1:
            props.flagsGroups.every(fg => {
                const vxFlag = fg.flags.find(f => f.id === vxFlagId);
                if (vxFlag) {
                    vxFlagInfoTemperatura.temperatura = vxFlag.description;
                    return false;
                }

                return true;
            });
            break;
        case 2:
            fetch('/vx-flags/info/' + vxFlagId).then(res => res.json()).then(json => {
                if (json.attributes) {
                    const vxFlagAttrs = JSON.parse(json.attributes);
                    let setAll = (obj, val) => Object.keys(obj).forEach(k => obj[k] = val);
                    let setNull = obj => setAll(obj, null);
                    setNull(vxFlagInfoSuelo);
                    Object.keys(vxFlagAttrs).forEach(k => {
                        if (k in vxFlagInfoSuelo) {
                            vxFlagInfoSuelo[k] = vxFlagAttrs[k];
                        }
                    });
                    vxFlagInfoSuelo.observaciones =
                        vxFlagInfoSuelo.observaciones.replace(/(\r\n|\r|\n)/g, '<br>');
                }
            });
            break;
    }
}

const clearVxFlagInfoPanel = () => {
    currentVxFlagInfo.active = false;
}

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
                            <FlagsMap
                                :map="map"
                            />
                        </div>
                        <div class="basis-1/3 pr-5">
                            <div class="my-10">
                                <LocationSearchInput
                                    v-model:lat="searchLocation.lat"
                                    v-model:lng="searchLocation.lng"
                                    @search="setSearchLocation"
                                />
                            </div>
                            <div class="my-10">
                                <FlagsGroupSelect
                                    :groups="flagsGroups"
                                    @groups-change="groupsChange"
                                />
                            </div>
                            <div v-show="currentVxFlagInfo.active">
                                <div v-show="currentVxFlagInfo.groupType === 1">
                                    <p class="uppercase font-bold text-lg my-10">Información</p>
                                    {{ vxFlagInfoTemperatura.temperatura }}
                                </div>
                                <div v-show="currentVxFlagInfo.groupType === 2">
                                    <FlagInfo
                                        :flag-info="vxFlagInfoSuelo"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

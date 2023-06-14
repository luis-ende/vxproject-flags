<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import FlagsMap from "../Components/Flags/FlagsMap.vue";
import FlagsGroupSelect from "../Components/Flags/FlagsGroupSelect.vue";
import {onMounted, reactive, ref} from "vue";
import FlagInfo from "../Components/Flags/FlagInfo.vue";
import LocationSearchInput from "../Components/Flags/LocationSearchInput.vue";
import FieldLogo from "../Components/FieldLogo.vue";

const props = defineProps({
    flagsGroups: {
        type: Array,
        default: [],
    },
    mapLayerUrl: {
        type: String,
    }
});

const TIPO_GRUPO_TEMPERATURAS = 1;
const TIPO_GRUPO_TIPO_SUELO = 2;

// @todo Consider: https://stackoverflow.com/questions/73542576/leaflet-error-when-zooming-after-closing-popup
let map = null;
let markersGroup = [];
let currentLocationMarker = null;
let grupoTiposSueloActivo = false;
let searchLocation = reactive({
    lat: 19.43262,
    lng: -99.13317,
    zoom: 6,
});
const violetIcon = new L.Icon({
    // conUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-violet.png',
    // shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
    iconUrl: '/images/grommet-map.png',
    // iconSize: [25, 41],
    iconSize: [25, 25],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
});
const currentVxFlagInfo = reactive({
    active: false,
    groupType: TIPO_GRUPO_TIPO_SUELO,
});
const vxFlagInfoSuelo = reactive({
    altura: null,
    capacidad: null,
    d_terreno: null,
    df_min: null,
    latitud: null,
    longitud: null,
    n: null,
    o_terreno: null,
    observaciones: null,
    proyecto: null,
    sismo: null,
    sitio: null,
    tipo_terreno: null,
    zona: null,
    descripcion_suelo: null,
});
const vxFlagInfoTemperatura = reactive({
    temperatura: null,
});
const regionesDefault = reactive([]);
const isLoading = ref(false);
const isLoadingFlagInfo = ref(false);
const loadError = ref('');

onMounted(() => {
    map = L.map('map').setView([searchLocation.lat, searchLocation.lng], searchLocation.zoom);
    map.on('click', onMapClick)
    map.on('dblclick', onMapDoubleClick)
    L.tileLayer(props.mapLayerUrl, {
        maxZoom: 19,
        attribution: '<a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap</a>'
    }).addTo(map)
});

const onMapClick = (e) => {
    clearVxFlagInfoPanel();
}

const onMapDoubleClick = (e) => {
    searchLocation.lat = e.latlng.lat;
    searchLocation.lng = e.latlng.lng;
    setSearchLocation();
}

const groupsChange = async (groupId, checked) => {
    const currentGroup = props.flagsGroups.find(g => g.id === groupId);
    if (checked) {
        if (currentGroup.type !== TIPO_GRUPO_TIPO_SUELO) {
            await fetchGroupFlags(currentGroup);
        }
        loadFlags(currentGroup);
    } else {
        clearFlags(currentGroup);
    }

    if (currentGroup.type === TIPO_GRUPO_TIPO_SUELO) {
        grupoTiposSueloActivo = checked;
        regionesDefault.length = 0;
        regionesDefault.push('R9');
        await regionesChange(regionesDefault);
    }
};

const regionesChange = async (regiones) => {
    if (grupoTiposSueloActivo) {
        const grupoTiposSuelo = props.flagsGroups.find(g => g.type === 2);
        clearFlags(grupoTiposSuelo);
        await fetchGroupFlags(grupoTiposSuelo, regiones);
        loadFlags(grupoTiposSuelo);
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
        let desc = flag.description.replace(/(\r\n|\r|\n)/g, '<br>');
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
        marker.addTo(map);
        marker.bindPopup(desc);
    });

    markersGroup.push({
        id: group.id,
        markers: layerMarkers
    });
};

const clearFlags = (group) => {
    clearVxFlagInfoPanel();
    let groupMarkers = markersGroup.find(gm => gm.id === group.id);
    groupMarkers.markers.forEach(marker => {
        map.removeLayer(marker);
    });
    markersGroup = markersGroup.filter(gm => gm.id !== group.id);
    group.flags = [];
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
    map.setView([searchLocationInfo.lat, searchLocationInfo.lng], searchLocationInfo.zoom);

    if (currentLocationMarker) {
        map.removeLayer(currentLocationMarker);
    }

    let marker = L.marker([searchLocationInfo.lat, searchLocationInfo.lng], { icon: violetIcon });
    map.addLayer(marker);
    currentLocationMarker = marker;
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
        case TIPO_GRUPO_TEMPERATURAS:
            props.flagsGroups.every(fg => {
                const vxFlag = fg.flags.find(f => f.id === vxFlagId);
                if (vxFlag) {
                    vxFlagInfoTemperatura.temperatura = vxFlag.description;
                    return false;
                }

                return true;
            });
            break;
        case TIPO_GRUPO_TIPO_SUELO:
            isLoadingFlagInfo.value = true;
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
                isLoadingFlagInfo.value = false;
            });
            break;
    }
}

const clearVxFlagInfoPanel = () => {
    currentVxFlagInfo.active = false;
}

const fetchGroupFlags = async (group, regiones = null) => {
    let queryParams = '';
    if (regiones) {
        if (! regiones.includes('R0')) {
            queryParams = '?reg=' + regiones.join(',');
        }
    }

    isLoading.value = true;
    await fetch(route('vx-flags.group.list', [group.id]) + queryParams).then(res => {
        if (res.ok) {
            return res.json();
        } else {
            isLoading.value = false;
            loadError.value = res.statusText;
        }
    })
    .then(json => {
        if (json && json.flags) {
            group.flags = json.flags;
        }
        isLoading.value = false;
    })
};

</script>

<template>
    <AppLayout title="Escritorio">
        <template #header>
            <div class="ml-2 w-24 h-10">
                <FieldLogo />
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-full mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="flex flex-col md:flex-row min-h-screen md:h-[850px]">
                        <div class="md:basis-2/3 p-5 h-[450px] md:h-full">
                            <FlagsMap
                                :data-loading="isLoading"
                                :map="map"
                            />
                        </div>
                        <div class="md:basis-1/3 md:pr-5 mx-4 md:mx-0">
                            <div class="my-5">
                                <LocationSearchInput
                                    v-model:lat="searchLocation.lat"
                                    v-model:lng="searchLocation.lng"
                                    :data-loading="isLoading"
                                    @search="setSearchLocation"
                                />
                            </div>
                            <div class="my-5">
                                <FlagsGroupSelect
                                    :groups="flagsGroups"
                                    :regiones-default="regionesDefault"
                                    :data-loading="isLoading"
                                    @groups-change="groupsChange"
                                    @regiones-change="regionesChange"
                                />
                            </div>
                            <div v-show="currentVxFlagInfo.active">
                                <div v-show="currentVxFlagInfo.groupType === TIPO_GRUPO_TEMPERATURAS">
                                    <p class="uppercase font-bold text-lg my-10">Temperatura</p>
                                    <p class="mb-10">{{ vxFlagInfoTemperatura.temperatura }}</p>
                                </div>
                                <div v-show="currentVxFlagInfo.groupType === TIPO_GRUPO_TIPO_SUELO">
                                    <FlagInfo
                                        :data-loading="isLoadingFlagInfo"
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

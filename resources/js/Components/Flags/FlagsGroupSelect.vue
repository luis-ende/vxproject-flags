<script setup>

import TiposSueloRegionesSelect from "./TiposSueloRegionesSelect.vue";
import {ref} from "vue";
import TiposSueloPaisesSelect from "./TiposSueloPaisesSelect.vue";

const emit = defineEmits(['groupsChange', 'regionesChange', 'paisChange']);

const props = defineProps({
    groups: {
        type: Array,
        default: [],
    },
    regionesDefault: {
        type: Array,
    },
    paisIdDefault: {
        type: Number,
    },
    paises: {
        type: Array,
        default: [],
    },
    dataLoading: {
        type: Boolean,
        default: false,
    }
});

const tiposSueloSelected = ref(false);
let paisSelected = ref(props.paisIdDefault);

const onGroupsSelected = (e) => {
    if (parseInt(e.target.dataset.groupType) === 2) {
        tiposSueloSelected.value = e.target.checked;
    }
    emit('groupsChange', parseInt(e.target.dataset.group), e.target.checked);
};

const onRegionesChange = (regiones) => {
    emit('regionesChange', regiones);
};

const onPaisChange = (paisCode) => {
    paisSelected.value = paisCode;
    emit('paisChange', paisCode);
};

</script>

<template>
    <ul>
        <li v-for="(group, index) in groups" class="my-3 flex flex-row md:pl-14">
            <input type="checkbox"
                   :id="'group_' + index"
                   name="groups"
                   :disabled="dataLoading"
                   @change="onGroupsSelected($event)"
                   class="w-5 h-5 text-vxproject-secondary border-vxproject-secondary focus:ring-vxproject-primary rounded-full"
                   :class="{ 'opacity-25': dataLoading }"
                   :data-group="group.id"
                   :data-group-type="group.type">
            <label :for="'group_' + index" class="uppercase ml-3" :class="dataLoading ? 'text-stone-400' : ''">
                {{ group.name }}
            </label>
        </li>
    </ul>
    <div v-show="tiposSueloSelected" class="md:pl-24 block">
        <TiposSueloPaisesSelect
            :paises="paises"
            :data-loading="dataLoading"
            :pais-id-default="paisIdDefault"
            @pais-change="onPaisChange"
        />
        <div v-show="paisSelected === paisIdDefault">
            <TiposSueloRegionesSelect
                :data-loading="dataLoading"
                :regiones-default="regionesDefault"
                @regiones-change="onRegionesChange"/>
        </div>
    </div>
</template>
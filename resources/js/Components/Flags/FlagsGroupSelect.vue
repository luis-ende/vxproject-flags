<script setup>

import TiposSueloRegionesSelect from "./TiposSueloRegionesSelect.vue";
import {ref} from "vue";

const emit = defineEmits(['groupsChange', 'regionesChange']);

const tiposSueloSelected = ref(false);

defineProps({
    groups: {
        type: Array,
        default: [],
    },
});

const onGroupsSelected = (e) => {
    if (parseInt(e.target.dataset.groupType) === 2) {
        tiposSueloSelected.value = e.target.checked;
    }
    emit('groupsChange', parseInt(e.target.dataset.group), e.target.checked);
};

const onRegionesChange = (regiones) => {
    emit('regionesChange', regiones);
};

</script>

<template>
    <ul>
        <li v-for="(group, index) in groups" class="my-3 flex flex-row pl-14">
            <input type="checkbox"
                   :id="'group_' + index"
                   name="groups"
                   @change="onGroupsSelected($event)"
                   class="w-5 h-5 text-vxproject-secondary border-vxproject-secondary focus:ring-vxproject-primary rounded-full"
                   :data-group="group.id"
                   :data-group-type="group.type">
            <label :for="'group_' + index" class="uppercase ml-3">{{ group.name }}</label>
        </li>
    </ul>
    <div v-show="tiposSueloSelected" class="pl-24 block">
        <TiposSueloRegionesSelect
                @regiones-change="onRegionesChange"/>
    </div>
</template>
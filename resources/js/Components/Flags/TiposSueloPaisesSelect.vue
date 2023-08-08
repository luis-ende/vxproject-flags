<script setup>

import {onMounted} from "vue";

const emit = defineEmits(['paisChange']);

const props = defineProps({
    paises: {
      type: Array,
      default: [],
    },
    dataLoading: {
        type: Boolean,
        default: false,
    }
});

onMounted(() => {
    const paisesSelect = document.getElementById('paises-select');
    paisesSelect.addEventListener('change', event => {
        emit('paisChange', parseInt(event.target.value))
    });
})

</script>

<template>
    <div v-show="!dataLoading" class="flex flex-col md:flex-row mb-3 items-center">
        <label for="paises-select" class="w-20 uppercase mr-3">País:</label>
        <select id="paises-select"
                class="border focus:ring-vxproject-secondary border-vxproject-secondary w-fit">
            <option v-for="pais in paises" :value="pais.id" :key="pais.id">
                {{ pais.pais.toUpperCase() }}
            </option>
        </select>
    </div>
</template>
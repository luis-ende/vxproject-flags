<script setup>
import Choices from 'choices.js';
import {onMounted, watch} from "vue";

const props = defineProps({
    paisCode: {
        type: String,
    },
    regionesDefault: {
        type: Array,
        default: [],
    },
    dataLoading: {
        type: Boolean,
        default: false,
    }
});

const emit = defineEmits(['regionesChange']);
let choicesData = [
    { value: 'R0', label: 'Todos', selected: false },
];
let choices = null;

watch(() => props.regionesDefault, () => {
    let selected = [];
    props.regionesDefault.forEach(r => {
        const item = choicesData.find(i => i.value === r);
        if (item) {
            selected.push(item.value);
        }
    });
    choices.removeActiveItems();
    choices.setChoiceByValue(selected);
}, { deep: true })

onMounted(() => {
    setChoicesRegionesMexico();

    const regionesSelect = document.getElementById('regiones-select')
    choices = new Choices(regionesSelect, {
        allowHTML: false,
        itemSelectText: 'Seleccionar',
        removeItemButton: true,
        shouldSort: false,
        noChoicesText: 'No hay más regiones para seleccionar.',
        classNames: {
            containerInner: 'regiones__choices__inner',
            input: 'regiones__choices__input',
        }
    });

    choices.setChoices(choicesData);

    let updating = false;
    regionesSelect.addEventListener('change', event => {
        if (!updating) {
            const options = event.target.selectedOptions;
            if (options.length === 0) {
                choices.setChoiceByValue('R0');
            } else {
                updating = true;
                if (event.detail.value === 'R0') {
                    choices.removeActiveItems(null);
                    choices.setChoiceByValue('R0');
                } else {
                    choices.removeActiveItemsByValue('R0');
                }
                updating = false;
            }

            let regiones = [];
            for (let i = 0; i < options.length; i++) {
                regiones.push(options[i].value);
            }
            if (regiones.length === 0) {
                regiones.push('R0');
            }
            emit('regionesChange', regiones)
        }
    });
});

const setChoicesRegionesMexico = () => {
    choicesData.push({ value: 'R1', label: 'R1', selected: false })
    choicesData.push({ value: 'R2', label: 'R2', selected: false });
    choicesData.push({ value: 'R3', label: 'R3', selected: false });
    choicesData.push({ value: 'R4', label: 'R4', selected: false });
    choicesData.push({ value: 'R5', label: 'R5', selected: false });
    choicesData.push({ value: 'R6', label: 'R6', selected: false });
    choicesData.push({ value: 'R7', label: 'R7', selected: false });
    choicesData.push({ value: 'R8', label: 'R8', selected: false });
    choicesData.push({ value: 'R9', label: 'R9', selected: false });
}

</script>

<template>
    <div v-show="!dataLoading" class="flex flex-col md:flex-row items-center">
        <label for="regiones-select" class="w-20 uppercase mr-3">Regiones:</label>
        <select id="regiones-select"
                class="w-fit"
                multiple>
        </select>
    </div>
</template>

<style scoped>

</style>
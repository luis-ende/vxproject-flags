<script setup>
import AppLayout from "../Layouts/AppLayout.vue";
import {onMounted, reactive, ref} from "vue";
import PrimaryButton from "../Components/PrimaryButton.vue";
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net';
import {useForm} from "@inertiajs/vue3";

DataTable.use(DataTablesCore);

let dt;
const table = ref();

const props = defineProps({
    flagsGroups: {
        type: Array,
        default: [],
    },
});

const vxFlags = reactive([] );
const selectedGroup = ref(1);
let currentGroup = reactive(props.flagsGroups[0]);
const isLoading = ref(false);
const loadError = ref('');

const updateGroupForm = useForm({
    color: null,
});

const updateGroup = () => {
    updateGroupForm.put(route('vx-flags.group.update', [currentGroup])).then(res => {

    });
};

const groupClasses = (groupId, index) => {
    const rounded = index === 0 ? 'rounded-t-lg' : '';
    return selectedGroup.value === groupId
        ? `block w-full px-4 py-2 text-white bg-violet-700 border-b border-gray-200 ${rounded} cursor-pointer dark:bg-gray-800 dark:border-gray-600 text-left font-bold`
        : 'block w-full px-4 py-2 border-b border-gray-200 cursor-pointer hover:bg-gray-100 hover:text-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-700 focus:text-violet-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white text-left';
};

const setCurrentGroup = (groupId) => {
    selectedGroup.value = groupId;
    currentGroup = reactive(props.flagsGroups.find(g => g.id === groupId));
    updateGroupForm.color = currentGroup.color;
    loadGroupFlags(groupId);
};

const loadGroupFlags = (groupId) => {
    isLoading.value = true;
    loadError.value = '';
    fetch(route('vx-flags.group.list', [groupId])).then(res => {
        isLoading.value = false;
        if (res.ok) {
            return res.json();
        } else {
            loadError.value = res.statusText;
        }
    })
    .then(json => {
        if (json && json.flags) {
            vxFlags.length = 0;
            json.flags.forEach(f => {
                vxFlags.push([
                    f.description,
                    f.latitude,
                    f.longitude,
                ]);
            });
            isLoading.value = false;
        }
    })
};

onMounted(() => {
    setCurrentGroup(props.flagsGroups[0].id);
});

</script>

<template>
    <AppLayout title="Panel de Administración">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Configuraciones
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-full mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg min-h-screen">
                    <div class="flex flex-row flex-wrap px-5 py-5">
                        <div class="basis-full md:basis-1/3 md:px-10">
                            <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Grupos de sitios</h2>
                            <div class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                 :class="{ 'opacity-25': isLoading }">
                                <button type="button"
                                        :disabled="isLoading"
                                        v-for="(group, index) in flagsGroups"
                                        @click="setCurrentGroup(group.id)"
                                        :class="groupClasses(group.id, index)">
                                    {{ group.name }}
                                </button>
                            </div>
                        </div>
                        <div v-if="currentGroup" class="basis-full md:basis-2/3 flex flex-col">
                            <section class="bg-white dark:bg-gray-900">
                                <div class="w-fit pt-8 md:pt-0">
                                    <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Editar grupo</h2>
                                    <form action="#">
                                        <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                                            <div class="sm:col-span-2">
                                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
                                                <input type="text"
                                                       id="name"
                                                       class="bg-neutral-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-vxproject-secondary focus:border-vxproject-secondary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                       v-model="currentGroup.name"
                                                       placeholder="Nombre del grupo"
                                                       disabled>
                                            </div>
                                            <div class="w-full">
                                                <label for="color" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Color</label>
                                                <input type="color"
                                                       name="color"
                                                       id="color"
                                                       @change="updateGroup"
                                                       v-model="updateGroupForm.color">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </section>

                            <section class="my-5 text-sm text-stone-700">
                                <p>Ultima importación de datos: 12May2023 22:33:11 </p>
                                <p>Archivo de importación de datos: /etc/filerun/abc.xlsx</p>
                            </section>

                            <section>
                                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Sitios del grupo</h2>
                                <div v-show="isLoading" class="text-stone-500">Cargando sitios del grupo...</div>
                                <div v-show="loadError" class="text-sm my-5 text-red-600">Ocurrió un error al cargar datos: {{ loadError }}</div>
                                <div v-show="!isLoading" class="relative overflow-x-auto overflow-y-auto shadow-md sm:rounded-lg">
                                    <DataTable
                                            class="display"
                                            :options="{ language: { url: '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json'} }"
                                            :data="vxFlags"
                                    >
                                        <thead>
                                            <tr>
                                                <th scope="col" class="px-6 py-3">
                                                    Descripción
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Latitud
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Longitud
                                                </th>
                                            </tr>
                                        </thead>
                                    </DataTable>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
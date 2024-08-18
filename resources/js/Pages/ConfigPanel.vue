<script setup>
import AppLayout from "../Layouts/AppLayout.vue";
import {onMounted, reactive, ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net';
import 'datatables.net-select';
import 'datatables.net-responsive';
import ConfirmationModal from "../Components/ConfirmationModal.vue";
import SecondaryButton from "../Components/SecondaryButton.vue";
import FlagsImportLog from "../Components/Config/FlagsImportLog.vue";
import PrimaryButton from "../Components/PrimaryButton.vue";
import TiposSueloColoresConfig from "../Components/Config/TiposSueloColoresConfig.vue";

DataTable.use(DataTablesCore);

let dt;

const tableColumns = [
    {
        data: 1,
    },
    {
        data: 2,
    },
    {
        data: 3,
    },
    {
        data: null,
        className: "dt-center editor-delete",
        defaultContent: '<svg style="cursor: pointer" title="Eliminar renglón" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path><title>Eliminar</title></svg>',
        orderable: false
    }
];

const flagsDataTableOptions = {
    language: {
        sProcessing:     "Procesando...",
        sLengthMenu:     "Mostrar _MENU_ registros",
        sZeroRecords:    "No se encontraron resultados",
        sEmptyTable:     "Ningún dato disponible en esta tabla",
        sInfo:           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        sInfoEmpty:      "Mostrando registros del 0 al 0 de un total de 0 registros",
        sInfoFiltered:   "(filtrado de un total de _MAX_ registros)",
        sInfoPostFix:    "",
        sSearch:         "Buscar:",
        sUrl:            "",
        sInfoThousands:  ",",
        sLoadingRecords: "Cargando...",
        oPaginate: {
            sFirst:    "Primero",
            sLast:     "Último",
            sNext:     "Siguiente",
            sPrevious: "Anterior"
        },
        oAria: {
            sSortAscending:  ": Activar para ordenar la columna de manera ascendente",
            sSortDescending: ": Activar para ordenar la columna de manera descendente"
        },
        select: {
            rows: {
                _: " / %d seleccionados",
                1: " / 1 seleccionado"
            }
        }
    },
    responsive: true,
    select: {
        style: 'single',
        toggleable: false,
    },
};
const flagsDatatable = ref();

const props = defineProps({
    flagsGroups: {
        type: Array,
        default: [],
    },
    tipoSueloSitiosImport: {
        type: Object,
    }
});

const vxFlags = reactive([] );
const selectedGroup = ref(1);
let currentGroup = reactive(props.flagsGroups[0]);
const isLoading = ref(false);
const loadError = ref('');
const rowBeingRemoved = ref(false);

const deleteFlagForm = useForm({});

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
                    f.id,
                    f.description,
                    f.latitude,
                    f.longitude,
                ]);
            });
            isLoading.value = false;
        }
    })
};

const deleteFlag = () => {
    const selected = dt.rows({ selected: true });
    if (selected[0].length >= 1) {
        const index = selected[0][0];
        const vxFlagId = vxFlags[index][0];
        deleteFlagForm.delete(route('vx-flags.destroy', vxFlagId), {
            preserveScroll: true,
            onSuccess: () => vxFlags.splice(index, 1),
            //onError: () => ,
            onFinish: () => {
                rowBeingRemoved.value = false;
                deleteFlagForm.reset()
            },
        });
    }
};

const importFinished = () => {
    localStorage.setItem('currentGroupId', currentGroup.id);
    window.location.reload();
}

onMounted(() => {
    dt = flagsDatatable.value.dt;
    dt.on('click', 'td.editor-delete', function (e) {
        e.preventDefault();
        rowBeingRemoved.value = true;
    });

    if (localStorage.getItem('currentGroupId')) {
        const groupId = parseInt(localStorage.getItem('currentGroupId'));
        setCurrentGroup(groupId);
    } else {
        setCurrentGroup(props.flagsGroups[0].id);
    }
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
                                    <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Información del grupo</h2>
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
                                            <div class="sm:col-span-2">
                                                <label for="color" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Color</label>
                                                <input type="color"
                                                       name="color"
                                                       id="color"
                                                       @change="updateGroup"
                                                       v-model="updateGroupForm.color">
                                                <TiposSueloColoresConfig v-if="currentGroup.type === 2 && currentGroup?.config?.sites_colors"
                                                                         :colors="currentGroup.config.sites_colors" :group-id="currentGroup.id" />
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </section>

                            <section v-show="currentGroup.type === 2">
                                <FlagsImportLog
                                        v-if="tipoSueloSitiosImport"
                                        :tipo-suelo-sitios-import="tipoSueloSitiosImport"
                                        @import-finished="importFinished"
                                />
                            </section>

                            <section>
                                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Sitios del grupo</h2>
                                <div v-show="isLoading" class="text-stone-500">Cargando sitios del grupo...</div>
                                <div v-show="loadError" class="text-sm my-5 text-red-600">Ocurrió un error al cargar datos: {{ loadError }}</div>
                                <div v-show="!isLoading" class="relative overflow-x-auto overflow-y-auto shadow-md sm:rounded-lg p-2">
                                    <DataTable
                                            ref="flagsDatatable"
                                            class="display compact row-borders hover w-full"
                                            :columns="tableColumns"
                                            :options="flagsDataTableOptions"
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
                                                <th scope="col" class="px-6 py-3">
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

            <ConfirmationModal :show="rowBeingRemoved" @close="rowBeingRemoved = null">
                <template #title>
                    Sitios del grupo {{ currentGroup.name }}
                </template>

                <template #content>
                    ¿Deseas eliminar el sitio seleccionado?
                </template>

                <template #footer>
                    <SecondaryButton @click="rowBeingRemoved = null">
                        Cancelar
                    </SecondaryButton>

                    <PrimaryButton
                            class="ml-3"
                            :class="{ 'opacity-25': deleteFlagForm.processing }"
                            :disabled="deleteFlagForm.processing"
                            @click="deleteFlag"
                    >
                        Eliminar
                    </PrimaryButton>
                </template>
            </ConfirmationModal>
        </div>
    </AppLayout>
</template>

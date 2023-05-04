<script setup>
import AppLayout from "../Layouts/AppLayout.vue";
import {onMounted, reactive, ref} from "vue";

const props = defineProps({
    flagsGroups: {
        type: Array,
        default: [],
    },
});

const selectedGroup = ref(1);
let currentGroup = reactive(props.flagsGroups[0]);

const groupClasses = (groupId, index) => {
    const rounded = index === 0 ? 'rounded-t-lg' : '';
    return selectedGroup.value === groupId
        ? `block w-full px-4 py-2 text-white bg-violet-700 border-b border-gray-200 ${rounded} cursor-pointer dark:bg-gray-800 dark:border-gray-600 text-left font-bold`
        : 'block w-full px-4 py-2 border-b border-gray-200 cursor-pointer hover:bg-gray-100 hover:text-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-700 focus:text-violet-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white text-left';
};

const setCurrentGroup = (groupId) => {
    selectedGroup.value = groupId;
    currentGroup = reactive(props.flagsGroups.find(g => g.id === groupId));
};

onMounted(() => {
    setCurrentGroup(1);
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
                            <div class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <button type="button"
                                        v-for="(group, index) in flagsGroups"
                                        @click="setCurrentGroup(group.id)"
                                        :class="groupClasses(group.id, index)">
                                    {{ group.name }}
                                </button>
                                <button
                                   class="block w-full px-4 py-2 rounded-b-lg cursor-pointer hover:bg-gray-100 hover:text-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-700 focus:text-violet-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white text-left">
                                    + Agregar nuevo
                                </button>
                            </div>
                        </div>
                        <div v-if="currentGroup" class="basis-full md:basis-2/3 flex flex-col">
                            <section class="bg-white dark:bg-gray-900">
                                <div class="w-fit py-8 md:pt-0 md:pb-8">
                                    <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Editar grupo</h2>
                                    <form action="#">
                                        <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                                            <div class="sm:col-span-2">
                                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
                                                <input type="text"
                                                       name="name"
                                                       id="name"
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                       v-model="currentGroup.name"
                                                       placeholder="Nombre del grupo"
                                                       required="">
                                            </div>
                                            <div class="w-full">
                                                <label for="color" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Color</label>
                                                <input type="color"
                                                       name="color"
                                                       id="color"
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                       v-model="currentGroup.color"
                                                       placeholder="Product brand"
                                                       required="">
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <button type="button" class="text-white bg-violet-700 hover:bg-violet-800 focus:ring-4 focus:outline-none focus:ring-violet-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-violet-600 dark:hover:bg-violet-700 dark:focus:ring-violet-800">
                                                Actualizar
                                            </button>
                                            <button type="button"
                                                    title="Importar sitios a este grupo"
                                                    class="text-white bg-violet-700 hover:bg-violet-800 focus:ring-4 focus:outline-none focus:ring-violet-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-violet-600 dark:hover:bg-violet-700 dark:focus:ring-violet-800">
                                                Importar
                                            </button>
                                            <button type="button" class="text-red-600 inline-flex items-center hover:text-white border border-red-600 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">
                                                <svg class="w-5 h-5 mr-1 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                                Borrar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </section>
                            <section>
                                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Sitios del grupo</h2>
                                <div class="relative overflow-x-auto overflow-y-auto shadow-md sm:rounded-lg">
                                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                Descripcion
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Latitud
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Longitud
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="flag in currentGroup.flags"
                                                class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                                                <th scope="row" class="py-2 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    {{ flag.description }}
                                                </th>
                                                <td class="py-2 px-6">
                                                    {{ flag.latitude }}
                                                </td>
                                                <td class="py-2 px-6 text-right">
                                                    {{ flag.longitude }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
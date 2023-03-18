<script setup>
</script>

<template>
    <div class="flex flex-row space-x-4 items-center">
        <label class="uppercase font-bold">Sitio</label>
        <div>
            <input
                class="border focus:ring-vxproject-primary border-vxproject-primary inline-block w-32 h-7"
                type="number"
                placeholder="lat">
            <span class="font-bold"> - </span>
            <input
                class="border focus:ring-vxproject-primary border-vxproject-primary inline-block w-32 h-7"
                type="number"
                placeholder="lng">
        </div>
        <button class="uppercase text-vxproject-primary font-bold text-sm rounded-full border border-vxproject-primary px-3">Buscar</button>
    </div>
</template>
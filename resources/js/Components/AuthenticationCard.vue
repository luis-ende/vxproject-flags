<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center px-5 md:px-0 pt-6 sm:pt-0 bg-white">
        <div class="flex flex-col items-center mb-10">
            <slot name="logo" />
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white overflow-hidden vxproject-border shadow-none">
            <slot />
        </div>
    </div>
</template>

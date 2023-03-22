<script setup>

const emit = defineEmits(['groupsChange']);

defineProps({
    groups: {
        type: Array,
        default: [],
    },
});

const onGroupsSelected = (e) => {
    emit('groupsChange', parseInt(e.target.dataset.group), e.target.checked);
};

</script>

<template>
    <ul>
        <li v-for="(group, index) in groups" class="my-3 flex flex-row pl-14">
            <input type="checkbox" :id="'group_' + index" name="groups" @change="onGroupsSelected($event)"
                   class="w-5 h-5 text-vxproject-secondary border-vxproject-secondary focus:ring-vxproject-primary rounded-full"
                   :data-group="group.id">
            <label :for="'group_' + index" class="uppercase ml-3">{{ group.name }}</label>
        </li>
    </ul>
</template>
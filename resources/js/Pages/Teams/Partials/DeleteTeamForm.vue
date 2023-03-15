<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionSection from '@/Components/ActionSection.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    team: Object,
});

const confirmingTeamDeletion = ref(false);
const form = useForm({});

const confirmTeamDeletion = () => {
    confirmingTeamDeletion.value = true;
};

const deleteTeam = () => {
    form.delete(route('teams.destroy', props.team), {
        errorBag: 'deleteTeam',
    });
};
</script>

<template>
    <ActionSection>
        <template #title>
            Borrar grupo
        </template>

        <template #description>
          Borrar este grupo de manera permanente.
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600">
              Una vez que el grupo haya sido borrado, toda su información asociada será eliminada de forma permanente. Antes de borrar este grupo, asegúrate de haber descargado cualquier información que desees conservar.
            </div>

            <div class="mt-5">
                <DangerButton @click="confirmTeamDeletion">
                    Borrar grupo
                </DangerButton>
            </div>

            <!-- Delete Team Confirmation Modal -->
            <ConfirmationModal :show="confirmingTeamDeletion" @close="confirmingTeamDeletion = false">
                <template #title>
                    Borrar grupo
                </template>

                <template #content>
                  ¿Deseas borrar este grupo? Una vez que el grupo haya sido borrado, toda su información asociada será eliminada de forma permanente.
                </template>

                <template #footer>
                    <SecondaryButton @click="confirmingTeamDeletion = false">
                        Cancelar
                    </SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteTeam"
                    >
                        Borrar grupo
                    </DangerButton>
                </template>
            </ConfirmationModal>
        </template>
    </ActionSection>
</template>

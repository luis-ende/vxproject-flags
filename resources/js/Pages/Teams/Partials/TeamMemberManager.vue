<script setup>
import {computed, ref} from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import ActionSection from '@/Components/ActionSection.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import DialogModal from '@/Components/DialogModal.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    team: Object,
    availableRoles: Array,
    subscriptionTypes: Array,
    userPermissions: Object,
});

const addTeamMemberForm = useForm({
    email: '',
    role: 'subscriber',
    subscriptionType: 1,
});

const updatesubscriptionForm = useForm({
    subscriptionType: null,
});

const updateTeamMemberStatusForm = useForm({});
const removeTeamMemberForm = useForm({});

const currentlyManagingSubscription = ref(false);
const managingSuscriptionFor = ref(null);
const teamMemberBeingRemoved = ref(null);
const teamMemberChangingStatus = ref(null);

const addTeamMember = () => {
    addTeamMemberForm.post(route('team-members.store', props.team), {
        errorBag: 'addTeamMember',
        preserveScroll: true,
        onSuccess: () => addTeamMemberForm.reset(),
    });
};

const cancelTeamInvitation = (invitation) => {
    router.delete(route('team-invitations.destroy', invitation), {
        preserveScroll: true,
    });
};

const manageSubscription = (teamMember) => {
    managingSuscriptionFor.value = teamMember;
    updatesubscriptionForm.subscriptionType = teamMember.subscriber.subscription_type_id;
    currentlyManagingSubscription.value = true;
};

const updateSubscription = () => {
    updatesubscriptionForm.put(route('team-members.update', [props.team, managingSuscriptionFor.value]), {
        preserveScroll: true,
        onSuccess: () => currentlyManagingSubscription.value = false,
    });
};

const confirmTeamMemberRemoval = (teamMember) => {
    teamMemberBeingRemoved.value = teamMember;
};

const confirmTeamMemberStatusUpdate = (teamMember) => {
    teamMemberChangingStatus.value = teamMember;
};

const updateTeamMemberStatus = () => {
    updateTeamMemberStatusForm.put(route('team-members.update-status', [props.team, teamMemberChangingStatus.value]), {
        errorBag: 'updateTeamMemberStatus',
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => teamMemberChangingStatus.value = null,
    });
};

const removeTeamMember = () => {
    removeTeamMemberForm.delete(route('team-members.destroy', [props.team, teamMemberBeingRemoved.value]), {
        errorBag: 'removeTeamMember',
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => teamMemberBeingRemoved.value = null,
    });
};

const displayableSubscriptionType = (subscriptionType) => {
    if (!subscriptionType) {
        return null;
    }

    return props.subscriptionTypes.find(st => st.id === subscriptionType).name;
};

const suscriptores = computed(() => {
    return props.team.users.sort((a, b) => (a.name > b.name) ? 1 : -1);
})

</script>

<template>
    <div>
        <div v-if="userPermissions.canAddTeamMembers">
            <SectionBorder />

            <!-- Add Team Member -->
            <FormSection @submitted="addTeamMember">
                <template #title>
                    Agregar miembro al grupo
                </template>

                <template #description>
                    Agrega un nuevo miembro a tu grupo.
                </template>

                <template #form>
                    <div class="col-span-6">
                        <div class="max-w-xl text-sm text-gray-600">
                            Por favor proporciona la dirección de correo electrónico de la persona que deseas agregar a este grupo.
                        </div>
                    </div>

                    <!-- Member Email -->
                    <div class="col-span-6 sm:col-span-4">
                        <InputLabel for="email" value="Correo electrónico" />
                        <TextInput
                            id="email"
                            v-model="addTeamMemberForm.email"
                            type="email"
                            class="mt-1 block w-full"
                        />
                        <InputError :message="addTeamMemberForm.errors.email" class="mt-2" />
                    </div>

                    <div v-if="subscriptionTypes.length > 0" class="col-span-6 lg:col-span-4">
                        <InputLabel for="subscriptionTypes" value="Plan de suscripción" />
                        <InputError :message="addTeamMemberForm.errors.subscriptionType" class="mt-2" />

                        <!-- Plan de suscripción -->
                        <div class="relative z-0 mt-1 border border-gray-200 rounded-lg cursor-pointer">
                            <button
                                    v-for="(subscription, i) in subscriptionTypes"
                                    :key="subscription.id"
                                    type="button"
                                    class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-vxproject-secondary focus:ring-2 focus:ring-vxproject-secondary"
                                    :class="{'border-t border-gray-200 focus:border-none rounded-t-none': i > 0, 'rounded-b-none': i != Object.keys(subscriptionTypes).length - 1}"
                                    @click="addTeamMemberForm.subscriptionType = subscription.id"
                            >
                                <div :class="{'opacity-50': addTeamMemberForm.subscriptionType && addTeamMemberForm.subscriptionType != subscription.id}">
                                    <div class="flex items-center">
                                        <div class="text-sm text-gray-600" :class="{'font-semibold': addTeamMemberForm.subscriptionType == subscription.id}">
                                            {{ subscription.name }}
                                        </div>

                                        <svg v-if="addTeamMemberForm.subscriptionType == subscription.id" class="ml-2 h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>

                                    <div class="mt-2 text-xs text-gray-600 text-left">
                                        {{ subscription.description }}
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </template>

                <template #actions>
                    <ActionMessage :on="addTeamMemberForm.recentlySuccessful" class="mr-3">
                        Agregado.
                    </ActionMessage>

                    <PrimaryButton :class="{ 'opacity-25': addTeamMemberForm.processing }" :disabled="addTeamMemberForm.processing">
                        Agregar
                    </PrimaryButton>
                </template>
            </FormSection>
        </div>

        <div v-if="team.team_invitations.length > 0 && userPermissions.canAddTeamMembers">
            <SectionBorder />

            <!-- Team Member Invitations -->
            <ActionSection class="mt-10 sm:mt-0">
                <template #title>
                    Invitaciones al grupo pendientes
                </template>

                <template #description>
                    Estas personas han sido invitadas al grupo y se les ha enviado una invitación por correo electrónico. Pueden unirse al grupo al aceptar la invitación por correo electrónico.
                </template>

                <!-- Pending Team Member Invitation List -->
                <template #content>
                    <div class="space-y-6">
                        <div v-for="invitation in team.team_invitations" :key="invitation.id" class="flex items-center justify-between">
                            <div class="text-gray-600">
                                {{ invitation.email }}
                            </div>

                            <div class="flex items-center">
                                <!-- Cancel Team Invitation -->
                                <button
                                    v-if="userPermissions.canRemoveTeamMembers"
                                    class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
                                    @click="cancelTeamInvitation(invitation)"
                                >
                                    Cancelar
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </ActionSection>
        </div>

        <div v-if="team.users.length > 0">
            <SectionBorder />

            <!-- Manage Subscribers -->
            <ActionSection class="mt-10 sm:mt-0">
                <template #title>
                    Miembros del grupo
                </template>

                <template #description>
                    Todas estas personas son parte de este grupo.
                </template>

                <!-- Subscribers List -->
                <template #content>
                    <div class="space-y-6">
                        <div v-for="user in suscriptores" :key="user.id" class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img class="w-8 h-8 rounded-full" :src="user.profile_photo_url" :alt="user.name">
                                <div class="ml-4">
                                    {{ user.name }}
                                </div>
                                <div class="ml-2 text-xs text-gray-400">
                                    Expira: {{ user.subscriber?.expiration_date }}
                                </div>
                            </div>

                            <div class="flex items-center">
                                <!-- Manage Subscriber Type -->
                                <button class="ml-2 text-sm text-gray-400 underline"
                                        @click="manageSubscription(user)"
                                >
                                    {{ displayableSubscriptionType(user.subscriber?.subscription_type_id) }}
                                </button>

                                <!-- Enable / disable Subscriber -->
                                <button class="cursor-pointer ml-6 text-sm"
                                        :class="{ 'text-green-500': !user.active, 'text-red-500': user.active }"
                                        @click="confirmTeamMemberStatusUpdate(user)"
                                >
                                    {{ user.active ? 'Desactivar' : 'Activar' }}
                                </button>

                                <button
                                    class="cursor-pointer ml-6 text-sm text-red-500"
                                    @click="confirmTeamMemberRemoval(user)"
                                >
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </ActionSection>
        </div>

        <DialogModal :show="currentlyManagingSubscription" @close="currentlyManagingSubscription = false">
            <template #title>
                Cambiar plan de suscripción
            </template>

            <template #content>
                <div v-if="managingSuscriptionFor">
                    <div class="relative z-0 mt-1 border border-gray-200 rounded-lg cursor-pointer">
                        <button
                                v-for="(st, i) in subscriptionTypes"
                                :key="st.id"
                                type="button"
                                class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-vxproject-secondary focus:ring-2 focus:ring-vxproject-secondary"
                                :class="{'border-t border-gray-200 focus:border-none rounded-t-none': i > 0, 'rounded-b-none': i !== Object.keys(availableRoles).length - 1}"
                                @click="updatesubscriptionForm.subscriptionType = st.id"
                        >
                            <div :class="{'opacity-50': updatesubscriptionForm.subscriptionType && updatesubscriptionForm.subscriptionType !== st.id}">
                                <!-- Role Name -->
                                <div class="flex items-center">
                                    <div class="text-sm text-gray-600" :class="{'font-semibold': updatesubscriptionForm.subscriptionType === st.id}">
                                        {{ st.name }}
                                    </div>

                                    <svg v-if="updatesubscriptionForm.subscriptionType == st.id" class="ml-2 h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>

                                <!-- Role Description -->
                                <div class="mt-2 text-xs text-gray-600">
                                    {{ st.description }}
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="currentlyManagingSubscription = false">
                    Cancelar
                </SecondaryButton>

                <SecondaryButton
                        class="ml-3"
                        :class="{ 'opacity-25': updatesubscriptionForm.processing }"
                        :disabled="updatesubscriptionForm.processing"
                        @click="updateSubscription"
                >
                    Guardar
                </SecondaryButton>
            </template>
        </DialogModal>

        <!-- Modal activar/desactivar suscriptor  -->
        <ConfirmationModal :show="teamMemberChangingStatus !== null" @close="teamMemberChangingStatus = null">
            <template #title>
                {{ teamMemberChangingStatus.active ? 'Desactivar' : 'Activar' }} suscriptor
            </template>

            <template #content>
                ¿Deseas {{ teamMemberChangingStatus.active ? 'desactivar' : 'activar' }} a este suscriptor?
            </template>

            <template #footer>
                <SecondaryButton @click="teamMemberChangingStatus = null">
                    Cancelar
                </SecondaryButton>

                <PrimaryButton
                    class="ml-3"
                    :class="{ 'opacity-25': updateTeamMemberStatusForm.processing }"
                    :disabled="updateTeamMemberStatusForm.processing"
                    @click="updateTeamMemberStatus"
                >
                    {{ teamMemberChangingStatus.active ? 'Desactivar' : 'Activar' }}
                </PrimaryButton>
            </template>
        </ConfirmationModal>

        <!-- Remover suscriptor y elminar usuario -->
        <ConfirmationModal :show="teamMemberBeingRemoved !== null" @close="teamMemberBeingRemoved = null">
            <template #title>
                Eliminar miembro del grupo
            </template>

            <template #content>
                ¿Deseas eliminar a esta persona del grupo?
            </template>

            <template #footer>
                <SecondaryButton @click="teamMemberBeingRemoved = null">
                    Cancelar
                </SecondaryButton>

                <PrimaryButton
                        class="ml-3"
                        :class="{ 'opacity-25': removeTeamMemberForm.processing }"
                        :disabled="removeTeamMemberForm.processing"
                        @click="removeTeamMember"
                >
                    Eliminar
                </PrimaryButton>
            </template>
        </ConfirmationModal>
    </div>
</template>

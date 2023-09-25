<template>
    <button
        type="button"
        @click="openFormModal"
        class="px-4 py-2 border border-green-600 hover:border-green-700 text-green-600 hover:text-green-700 rounded-lg text-sm flex items-center hover:bg-green-50"
    >
        <PlusIcon class="h-4 w-4" />
        New
    </button>
    <TransitionRoot appear :show="formModalIsOpen" as="template">
        <Dialog as="div" @close="closeFormModal" class="relative z-10">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black bg-opacity-25" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div
                    class="flex min-h-full items-center justify-center p-4 text-center"
                >
                <TransitionChild
                    as="template"
                    enter="duration-300 ease-out"
                    enter-from="opacity-0 scale-95"
                    enter-to="opacity-100 scale-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100 scale-100"
                    leave-to="opacity-0 scale-95"
                >
                    <DialogPanel
                    class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                    >
                        <DialogTitle
                            as="h3"
                            class="text-lg font-medium leading-6 text-gray-900 mb-4"
                        >
                            Create Subcategory
                        </DialogTitle>

                        <form @submit.prevent="submitForm()">
                            <div class="grid gap-4 sm:gap-6 mb-5">
                                <div class="">
                                    <InputLabel
                                        for="name"
                                        value="Name"
                                    />
                                    <TextInput
                                        id="name"
                                        type="text"
                                        class="mt-1 block w-full h-10"
                                        v-model="form.name"
                                        required
                                        autofocus
                                        autocomplete="off"
                                        placeholder="Type name"
                                    />
                                    <InputError class="mt-1" :message="form.errors.name" />
                                </div>
                            </div>
                            <div class="grid gap-4 sm:gap-6 mb-5">
                                <div class="">
                                    <InputLabel
                                        for="description"
                                        value="Description"
                                    />
                                    <TextareaInput
                                        id="description"
                                        class="mt-1 block w-full"
                                        v-model="form.description"
                                        placeholder="Type description"
                                        rows="4"
                                    />
                                    <InputError class="mt-1" :message="form.errors.description" />
                                </div>
                            </div>
                            <div class="flex flex-col md:flex-row gap-3 md:gap-2">
                                <PrimaryButton :disabled="form.processing">
                                    Save
                                </PrimaryButton>
                                <SecondaryButton
                                    type="button"
                                    @click="closeFormModal()"
                                >
                                    Cancel
                                </SecondaryButton>
                            </div>
                        </form>
                    </DialogPanel>
                </TransitionChild>
            </div>
        </div>
        </Dialog>
    </TransitionRoot>
</template>
<script setup>
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from '@headlessui/vue'
import TextInput from '@/Components/TextInput.vue'
import TextareaInput from '@/Components/TextareaInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import {
    PlusIcon
} from '@heroicons/vue/24/outline'
import Swal from 'sweetalert2'

const emit = defineEmits(['fetchData'])

const props = defineProps({
    modelId: {
        type: String,
        default: '',
    },
    url: {
        type: String,
        required: true,
    },
})

const formModalIsOpen = ref(false)

const form = useForm({
    name: '',
    description: '',
    category_id: props.modelId,
})

let submitForm = () => {
    form.post(route(props.url + '.store'), {
        preserveScroll: true,
        onSuccess: () => {
            emit('fetchData')
            closeFormModal()
            form.reset()
            Swal.fire({
                title: 'Created successfully',
                // text: "Created successfully.",
                icon: 'success',
                confirmButtonColor: '#16A34A',
            }).then(() => {
                //
            })
        },
    })
}

let openFormModal = () => {
    formModalIsOpen.value = true
}

let closeFormModal = () => {
    formModalIsOpen.value = false
    // form.reset()
}
</script>
<style lang="">

</style>

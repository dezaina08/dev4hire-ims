<template>
    <Head :title="pageTitle" />

    <AuthenticatedLayout>
        <div class="py-12 grid px-4 sm:px-6 lg:px-8">
            <div class="mb-4">
                <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-y-4 sm:gap-y-0">
                    <div class="flex items-center justify-start text-gray-500">
                        <TagIcon class="h-10 w-10 transition duration-75 mr-3" />
                        <h1 class="text-3xl">{{ pageTitle }}</h1>
                    </div>
                </div>
                <div>
                    <Breadcrumb
                        :links="[
                            {
                                link: 'categories',
                                title: 'Categories'
                            },
                            {
                                link: 'categories/' + props.category.id + '/edit',
                                title: props.category.name
                            }
                        ]"
                        :current="pageTitle"
                    />
                </div>
            </div>
            <div class="grid lg:grid-cols-2 gap-y-4 lg:gap-y-0 lg:gap-x-4 mb-4">
                <Card>
                    <template #card-header>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Details
                        </h3>
                    </template>
                    <template #card-body>
                        <form @submit.prevent="submitForm()">
                            <div class="grid gap-4 sm:gap-6 mb-4">
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
                                        autocomplete="off"
                                        placeholder="Type category name"
                                    />
                                    <InputError class="mt-1" :message="form.errors.name" />
                                </div>
                            </div>
                            <div class="grid gap-4 sm:gap-6 mb-4">
                                <div class="">
                                    <InputLabel
                                        for="description"
                                        value="Description"
                                    />
                                    <TextareaInput
                                        id="description"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.description"
                                        placeholder="Type category description"
                                        rows="4"
                                    />
                                    <InputError class="mt-1" :message="form.errors.description" />
                                </div>
                            </div>
                            <div class="flex flex-col md:flex-row gap-3 md:gap-2">
                                <PrimaryButton :disabled="form.processing">
                                    Save
                                </PrimaryButton>
                                <DynamicLink
                                    :href="'/' + 'categories/' + props.category.id + '/edit'"
                                    type="secondary"
                                >
                                    Back
                                </DynamicLink>
                            </div>
                        </form>
                    </template>
                </Card>
            </div>
            <div class="grid gap-y-4 lg:gap-y-0 lg:gap-x-4 mb-4">
                <ProductsTable
                    :model-id="model.id.toString()"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { TagIcon } from '@heroicons/vue/24/solid'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import TextInput from '@/Components/TextInput.vue'
import TextareaInput from '@/Components/TextareaInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import DynamicLink from '@/Components/DynamicLink.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Swal from 'sweetalert2'
import { router } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'
import Card from '@/Components/Card.vue'
import ProductsTable from '@/Pages/Subcategory/EditPartials/ProductsTable.vue'

const moduleName = 'Subcategory'
const url = 'subcategories'
const pageTitle = 'Update Subcategory'

const props = defineProps({
    model: Object,
    category: Object,
});

const form = useForm({
    id: props.model.id,
    name: props.model.name,
    description: props.model.description,
    category_id: props.category.id,
})

let submitForm = () => {
    form.patch(route(url + '.update', props.model.id), {
        preserveScroll: true,
        onSuccess: () => {
            router.get('/' + 'categories/' + props.category.id + '/edit')
            Swal.fire({
                title: 'Updated successfully',
                // text: "Created successfully.",
                icon: 'success',
                confirmButtonColor: '#16A34A',
            }).then(() => {
                //
            })
        },
    })
}
</script>
<style lang="">

</style>

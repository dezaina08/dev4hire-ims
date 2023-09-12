<template>
    <Head :title="pageTitle" />

    <AuthenticatedLayout>
        <div class="py-12 grid px-4 sm:px-6 lg:px-8">
            <div class="mb-4">
                <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-y-4 sm:gap-y-0">
                    <div class="flex items-center justify-start text-gray-500">
                        <ShoppingBagIcon class="h-10 w-10 transition duration-75 mr-3" />
                        <h1 class="text-3xl">{{ pageTitle }}</h1>
                    </div>
                </div>
                <div>
                    <Breadcrumb
                        :links="[
                            {
                                link: url,
                                title: moduleName
                            }
                        ]"
                        :current="pageTitle"
                    />
                </div>
            </div>
            <form @submit.prevent="submitForm()" enctype="multipart/form-data">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-y-4 lg:gap-y-0 lg:gap-x-4">
                    <div class="col-span-1">
                        <div class="py-4 lg:py-6 p-4 bg-white rounded-lg shadow">
                            <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Image
                                </h3>
                            </div>
                            <div>
                                <div class="mb-2 flex flex-col items-center">
                                    <img :src="model.product_photo != null & model.product_photo != '' ? model.product_photo : '/product-no-image.png'" class="w-44 h-44 object-cover border border-gray-300 mb-2 rounded-md" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-2">
                        <div class="py-4 lg:py-6 p-4 bg-white rounded-lg shadow">
                            <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Details
                                </h3>
                            </div>
                            <div>
                                <div class="grid gap-4 md:grid-cols-2 sm:gap-6 mb-5">
                                    <div class="">
                                        <InputLabel
                                            for="name"
                                            value="Name"
                                        />
                                        <TextInput
                                            id="name"
                                            type="text"
                                            class="mt-1 block w-full h-10 bg-gray-100"
                                            v-model="model.name"
                                            required
                                            autofocus
                                            autocomplete="off"
                                            placeholder="Name"
                                            disabled
                                        />
                                    </div>
                                    <div class="">
                                        <InputLabel
                                            for="product_code"
                                            value="Product Code"
                                        />
                                        <TextInput
                                            id="product_code"
                                            type="text"
                                            class="mt-1 block w-full h-10 bg-gray-100"
                                            v-model="model.product_code"
                                            required
                                            autocomplete="off"
                                            placeholder="Product Code"
                                            disabled
                                        />
                                    </div>
                                </div>
                                <div class="grid gap-4 md:grid-cols-2 sm:gap-6 mb-5">
                                    <div class="">
                                        <InputLabel
                                            for="category_id"
                                            value="Category"
                                        />
                                        <TextInput
                                            id="category_id"
                                            type="text"
                                            class="mt-1 block w-full h-10 bg-gray-100"
                                            v-model="model.category.name"
                                            required
                                            autocomplete="off"
                                            placeholder="Category"
                                            disabled
                                        />
                                    </div>
                                    <div class="">
                                        <InputLabel
                                            for="unit_id"
                                            value="Unit"
                                        />
                                        <TextInput
                                            id="unit_id"
                                            type="text"
                                            class="mt-1 block w-full h-10 bg-gray-100"
                                            v-model="model.unit.name"
                                            required
                                            autocomplete="off"
                                            placeholder="Unit"
                                            disabled
                                        />
                                    </div>
                                </div>
                                <div class="grid gap-4 md:grid-cols-2 sm:gap-6 mb-5">
                                    <div class="">
                                        <InputLabel
                                            for="buying_price"
                                            value="Buying Price"
                                        />
                                        <TextInput
                                            id="buying_price"
                                            type="text"
                                            class="mt-1 block w-full h-10 bg-gray-100"
                                            v-model="model.buying_price"
                                            required
                                            autocomplete="off"
                                            placeholder="Buying Price"
                                            disabled
                                        />
                                    </div>
                                    <div class="">
                                        <InputLabel
                                            for="selling_price"
                                            value="Selling Price"
                                        />
                                        <TextInput
                                            id="selling_price"
                                            type="text"
                                            class="mt-1 block w-full h-10 bg-gray-100"
                                            v-model="model.selling_price"
                                            required
                                            autocomplete="off"
                                            placeholder="Selling Price"
                                            disabled
                                        />
                                    </div>
                                </div>
                                <div class="grid gap-4 md:grid-cols-2 sm:gap-6 mb-5">
                                    <div class="">
                                        <InputLabel
                                            for="stock"
                                            value="Stock"
                                        />
                                        <TextInput
                                            id="stock"
                                            type="text"
                                            class="mt-1 block w-full h-10 bg-gray-100"
                                            v-model="model.stock"
                                            required
                                            autocomplete="off"
                                            placeholder="Stock"
                                            disabled
                                        />
                                    </div>
                                </div>
                                <div class="flex flex-col md:flex-row gap-3 md:gap-2">
                                    <DynamicLink
                                        :href="'/' + url + '/' + model.id.toString() + '/edit'"
                                        type="primary"
                                    >
                                        Edit
                                    </DynamicLink>
                                    <DynamicLink
                                        :href="'/' + url"
                                        type="secondary"
                                    >
                                        Back
                                    </DynamicLink>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { ShoppingBagIcon } from '@heroicons/vue/24/solid'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import DynamicLink from '@/Components/DynamicLink.vue'

const moduleName = 'Products'
const url = 'products'
const pageTitle = 'View Product'

const props = defineProps({
    model: Object,
});

const model = props.model
// Convert Number to String
model.stock = model.stock.toString()
</script>
<style lang="">

</style>

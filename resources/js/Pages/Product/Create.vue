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
                                <input
                                    type="file"
                                    accept="image/*"
                                    @input="previewImage"
                                    id="photo"
                                    name="photo"
                                    hidden
                                >

                                <div class="mb-2 flex flex-col items-center">
                                    <img :src="preview ?? '/product-no-image.png'" class="w-44 h-44 object-cover border border-gray-300 mb-2 rounded-md" />
                                    <div v-if="!!form.photo" class="text-sm text-gray-500">
                                        <p class="mb-0">File name: {{ form.photo.name }}</p>
                                        <p class="mb-0">Size: {{ (form.photo.size/1024).toFixed(2) }}KB</p>
                                    </div>
                                </div>

                                <div class="flex justify-center gap-3">
                                    <button
                                        @click="browseFile()"
                                        type="button"
                                        class="h-8 bg-blue-500 hover:bg-blue-600 text-white rounded-md px-3 text-sm"
                                    >
                                        Browse File
                                    </button>
                                    <button
                                        v-if="preview"
                                        @click="resetFile()"
                                        type="button"
                                        class="h-8 bg-red-500 hover:bg-red-600 text-white rounded-md px-3 text-sm"
                                    >
                                        Remove
                                    </button>
                                </div>

                                <InputError class="mt-2" :message="form.errors.photo" />
                            </div>
                        </div>
                    </div>
                    <div class="col-span-2">
                        <div class="py-4 lg:py-6 p-4 bg-white rounded-lg shadow">
                            <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
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
                                            class="mt-1 block w-full h-10"
                                            v-model="form.name"
                                            required
                                            autofocus
                                            autocomplete="off"
                                            placeholder="Name"
                                        />
                                        <InputError class="mt-1" :message="form.errors.name" />
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
                                            v-model="form.product_code"
                                            required
                                            autocomplete="off"
                                            placeholder="Product Code"
                                            disabled
                                        />
                                        <InputError class="mt-1" :message="form.errors.product_code" />
                                    </div>
                                </div>
                                <div class="grid gap-4 md:grid-cols-2 sm:gap-6 mb-5">
                                    <div class="">
                                        <InputLabel
                                            value="Category"
                                        />
                                        <ListBox
                                            id="category_id"
                                            :items="categories"
                                            :model-value="form.category_id"
                                            v-on:update:model-value="form.category_id = $event.id"
                                        />
                                        <InputError class="mt-1" :message="form.errors.category_id" />
                                    </div>
                                    <div class="">
                                        <InputLabel
                                            value="Unit"
                                        />
                                        <ListBox
                                            id="unit_id"
                                            :items="units"
                                            :model-value="form.unit_id"
                                            v-on:update:model-value="form.unit_id = $event.id"
                                        />
                                        <InputError class="mt-1" :message="form.errors.unit_id" />
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
                                            class="mt-1 block w-full h-10"
                                            v-model="form.buying_price"
                                            required
                                            autocomplete="off"
                                            placeholder="Buying Price"
                                        />
                                        <InputError class="mt-1" :message="form.errors.buying_price" />
                                    </div>
                                    <div class="">
                                        <InputLabel
                                            for="selling_price"
                                            value="Selling Price"
                                        />
                                        <TextInput
                                            id="selling_price"
                                            type="text"
                                            class="mt-1 block w-full h-10"
                                            v-model="form.selling_price"
                                            required
                                            autocomplete="off"
                                            placeholder="Selling Price"
                                        />
                                        <InputError class="mt-1" :message="form.errors.selling_price" />
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
                                            type="number"
                                            class="mt-1 block w-full h-10"
                                            v-model="form.stock"
                                            required
                                            autocomplete="off"
                                            placeholder="Stock"
                                        />
                                        <InputError class="mt-1" :message="form.errors.stock" />
                                    </div>
                                </div>
                                <div class="flex flex-col md:flex-row gap-3 md:gap-2">
                                    <PrimaryButton :disabled="form.processing">
                                        Save
                                    </PrimaryButton>
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
import { Head, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { ShoppingBagIcon } from '@heroicons/vue/24/solid'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import DynamicLink from '@/Components/DynamicLink.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Swal from 'sweetalert2'
import { router } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'
import { ref, onMounted } from 'vue'
import ListBox from '@/Components/ListBox.vue'

const moduleName = 'Products'
const url = 'products'
const pageTitle = 'Create Product'

const props = defineProps({
    categories: Array,
    units: Array,
});


const form = useForm({
    photo: null,
    remove_photo: false,
    name: '',
    product_code: '',
    category_id: '',
    unit_id: '',
    buying_price: '',
    selling_price: '',
    stock: '',
})

let submitForm = () => {
    form.post(route(url + '.store'), {
        preserveScroll: true,
        onSuccess: () => {
            router.get('/' + url)
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

const preview = ref(null)

let previewImage = (event) => {

    let input = event.target;
    if (input.files.length) {
        let reader = new FileReader();
        reader.onload = (e) => {
            preview.value = e.target.result;
        }
        form.photo = input.files[0];
        reader.readAsDataURL(input.files[0]);
        form.remove_photo = false
    }
}

let browseFile = () => {
    document.getElementById("photo").click()
}

let resetFile = () => {
    form.photo = null
    preview.value = null
    form.remove_photo = true
}
</script>
<style lang="">

</style>

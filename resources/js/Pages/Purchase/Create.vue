<template>
    <Head :title="pageTitle" />

    <AuthenticatedLayout>
        <div class="pt-12 pb-12 grid px-4 sm:px-6 lg:px-8">
            <div class="mb-4">
                <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-y-4 sm:gap-y-0">
                    <div class="flex items-center justify-start text-gray-500">
                        <TruckIcon class="h-10 w-10 transition duration-75 mr-3" />
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
                <div class="grid lg:grid-cols-2 gap-y-4 lg:gap-y-0 lg:gap-x-4 mb-4">
                    <div class="">
                        <Card>
                            <template #card-header>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Purchase Details
                                </h3>
                            </template>
                            <template #card-body>
                                <div class="grid gap-4 mb-5">
                                    <div class="">
                                        <InputLabel
                                            for="purchase_date"
                                            value="Purchase Date"
                                        />
                                        <TextInput
                                            id="purchase_date"
                                            type="date"
                                            class="mt-1 block w-full h-10"
                                            v-model="form.purchase_date"
                                            required
                                            autofocus
                                            autocomplete="off"
                                            placeholder="Purchase Date"
                                        />
                                        <InputError class="mt-1" :message="form.errors.purchase_date" />
                                    </div>
                                    <div class="">
                                        <InputLabel
                                            for="supplier_id"
                                            value="Supplier"
                                        />
                                        <Combobox
                                            :items="suppliers"
                                            ajaxUrl="/search-suppliers?search="
                                            v-on:update:model-value="form.supplier_id = !!$event ? $event.id : ''"
                                        />
                                        <InputError class="mt-1" :message="form.errors.supplier_id" />
                                    </div>
                                </div>
                            </template>
                        </Card>
                    </div>
                    <div class="">
                        <Card>
                            <template #card-header>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Select Product
                                </h3>
                            </template>
                            <template #card-body>
                                <div class="flex flex-col gap-y-4">
                                    <div>
                                        <InputLabel
                                            for="category_id"
                                            value="Category"
                                        />
                                        <ListBox
                                            id="category_id"
                                            :items="categories"
                                            v-on:update:model-value="category_id = $event.id"
                                        />
                                    </div>
                                    <div>
                                        <InputLabel
                                            for="product_id"
                                            value="Product"
                                        />
                                        <ListBox
                                            id="product_id"
                                            :items="products"
                                            v-on:update:model-value="selectedProduct = $event"
                                            :reset-index="resetProductIndex"
                                            v-on:update:reset-index="resetProductIndex = $event"
                                        />
                                        <InputError class="mt-1" :message="productErrorMessage" />
                                    </div>
                                    <div>
                                        <button
                                            type="button"
                                            class="h-10 w-full flex uppercase items-center justify-center border-2 text-blue-500 border-blue-500 hover:bg-blue-600 px-4 rounded-md hover:text-white text-xs font-bold"
                                            @click="addProductToList()"
                                        >
                                            <PlusIcon class="block h-5 w-5 mr-1" aria-hidden="true" />
                                            Add to List
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </Card>
                    </div>
                </div>
                <div class="grid gap-y-4 lg:gap-y-0 lg:gap-x-4">
                    <div class="">
                        <Card>
                            <template #card-header>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Products List
                                </h3>
                            </template>
                            <template #card-body>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm text-left">
                                        <thead class="text-gray-700 uppercase bg-gray-200">
                                            <tr>
                                                <th class="px-4 py-3">
                                                    #
                                                </th>
                                                <th class="px-4 py-3">
                                                    Product Name
                                                </th>
                                                <th class="px-4 py-3">
                                                    Quantity
                                                </th>
                                                <th class="px-4 py-3">
                                                    Price
                                                </th>
                                                <th class="px-4 py-3">
                                                    Total
                                                </th>
                                                <th class="px-4 py-3">
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-if="form.purchase_items.length"
                                                v-for="(purchase_item, index) in form.purchase_items"
                                                :key="index"
                                                class="border-b hover:bg-gray-100"
                                            >
                                                <td class="px-4 py-3 text-gray-700">
                                                    {{ index + 1 }}
                                                </td>
                                                <td class="px-4 py-3 text-gray-700 font-bold">
                                                    {{ purchase_item.name }}s
                                                </td>
                                                <td class="px-4 py-3 text-gray-700">
                                                    <TextInput
                                                        type="number"
                                                        min="1"
                                                        class="mt-1 block w-full h-10"
                                                        v-model="purchase_item.quantity"
                                                        required
                                                        autocomplete="off"
                                                        placeholder="Quantity"
                                                        @input="calculateTotal(purchase_item.id)"
                                                    />
                                                </td>
                                                <td class="px-4 py-3 text-gray-700">
                                                    <TextInput
                                                        type="number"
                                                        min="1"
                                                        class="mt-1 block w-full h-10"
                                                        v-model="purchase_item.unit_cost"
                                                        required
                                                        autocomplete="off"
                                                        step=".01"
                                                        placeholder="Price"
                                                        @input="calculateTotal(purchase_item.id)"
                                                    />
                                                </td>
                                                <td class="px-4 py-3 text-gray-700">
                                                    <span class="mt-1 w-full h-10 bg-gray-100 text-right py-2 px-3 rounded-md border-gray-300 border flex items-center justify-end">
                                                        {{ purchase_item.total }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-gray-700">
                                                    <div class="flex justify-center">
                                                        <a
                                                            href="#"
                                                            class="text-red-400 hover:text-white transition duration-200 ease-in-out border border-red-400 hover:border-red-600 rounded-md h-10 w-10 hover:bg-red-500 flex items-center justify-center"
                                                            title="Delete"
                                                            @click="removeProductFromList(purchase_item.id)"
                                                        >
                                                            <TrashIcon class="block h-5 w-5" aria-hidden="true" />
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr
                                                v-else
                                                class="bg-white border-b"
                                            >
                                                <td
                                                    colspan="6"
                                                    class="text-sm text-gray-900 font-light px-6 py-3 whitespace-nowrap text-center"
                                                >
                                                    No product
                                                </td>
                                            </tr>
                                            <tr
                                                v-if="form.purchase_items.length"
                                            >
                                                <td colspan="3"></td>
                                                <td class="text-right px-4 py-3 text-gray-700 font-bold text-lg">
                                                    Total Amount
                                                </td>
                                                <td class="px-4 py-3 text-gray-700">
                                                    <span class="w-full h-10 bg-gray-100 text-right py-2 px-3 rounded-md border-gray-300 border flex items-center justify-end">
                                                        {{ totalCost }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </template>
                        </Card>
                    </div>
                </div>
                <div class="pt-4 border-t">
                    <div class="flex flex-col md:flex-row justify-between gap-3 md:gap-2">
                        <DynamicLink
                            :href="'/' + url"
                            type="secondary"
                        >
                            Back
                        </DynamicLink>
                        <button
                            v-if="form.purchase_items.length"
                            :class="[form.purchase_items.length ? '' : 'backdrop-opacity-10']"
                            type="submit"
                            class="bg-green-500 hover:bg-green-600 h-10 px-3 rounded-md text-white">
                            Purchase
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import {
    TruckIcon,
    PlusIcon,
    TrashIcon,
} from '@heroicons/vue/24/solid'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import Swal from 'sweetalert2'
import { router } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'
import { ref, watch } from 'vue'
import ListBox from '@/Components/ListBox.vue'
import Combobox from '@/Components/Combobox.vue'
import Card from '@/Components/Card.vue'
import DynamicLink from '@/Components/DynamicLink.vue'

const moduleName = 'Purchases'
const url = 'purchases'
const pageTitle = 'Create Purchase'

const props = defineProps({
    suppliers: Array,
    categories: Array,
});

const form = useForm({
    purchase_date: '',
    supplier_id: '',
    category_id: '',
    purchase_items: [],
})

const totalCost = ref('0.00')
const productErrorMessage = ref(null)

// Use for watcher, update form.category_id when value changes
const category_id = ref('')

// Products by category
const products = ref([])

// Currently selected product from listbox
const selectedProduct = ref(null)

// Resets product items when category changes
const resetProductIndex = ref(false)

watch(category_id, (newValue, oldValue) => {
    getProductsByCategory(newValue)
    form.category_id = newValue
    resetProductIndex.value = true
    selectedProduct.value = null
    productErrorMessage.value = null
})

watch(selectedProduct, (newValue, oldValue) => {
    productErrorMessage.value = null
})

// Calculate total
let calculateTotal = (id) => {
    // Total of every product items
    let productItem = form.purchase_items.filter((item) => item.id == id)[0]
    if (productItem.quantity != '' && productItem.unit_cost != '') {
        productItem.total = (parseInt(productItem.quantity) * parseFloat(productItem.unit_cost)).toFixed(2).toString()
    } else {
        productItem.total = (0).toFixed(2).toString()
    }

    // Total cost
    totalCost.value = form.purchase_items.reduce((accumulator, currentValue) => {
        return accumulator + parseFloat(currentValue.total)
    }, 0).toFixed(2).toString()
}

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

let getProductsByCategory = (category_id) => {
    if (category_id != null) {
        axios.get('/products-list/' + category_id)
            .then(function (response) {
                products.value = response.data
            })
    } else {
        selectedProduct.value = null
        products.value = []
    }
}

let addProductToList = () => {
    if (selectedProduct.value != null && selectedProduct.value.id != null) {
        // Check if product already in the list
        let idExistArray = form.purchase_items.filter((item) => item.id == selectedProduct.value.id)
        if (!idExistArray.length) {

            // Add fields
            selectedProduct.value.quantity = '1'
            selectedProduct.value.unit_cost = ''
            selectedProduct.value.total = '0.00'

            form.purchase_items.push(selectedProduct.value)
        } else {
            productErrorMessage.value = "Product already in the list"
        }
    } else {
        productErrorMessage.value = "Select a product"
    }
}

let removeProductFromList = (id) => {
    Swal.fire({
        title: 'Remove product from list?',
        // text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Remove'
    }).then((result) => {
        if (result.isConfirmed) {
            // Get index of the product and remove it from the list
            let index = form.purchase_items.findIndex(item => item.id == parseInt(id))
            form.purchase_items.splice(index, 1)
        }
    })
    productErrorMessage.value = null
}
</script>

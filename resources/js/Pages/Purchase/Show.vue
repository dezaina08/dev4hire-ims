<template>
    <Head :title="pageTitle" />

    <AuthenticatedLayout>
        <div class="py-12 grid px-4 sm:px-6 lg:px-8">
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
            <div class="grid lg:grid-cols-2 gap-y-4 lg:gap-y-0 lg:gap-x-4 mb-4">
                <Card>
                    <template #card-header>
                        <h3 class="text-lg font-semibold text-gray-900">
                        Purchase Details
                        </h3>
                    </template>
                    <template #card-body>
                        <div class="grid gap-4 md:grid-cols-2 sm:gap-6 mb-5">
                            <div class="">
                                <InputLabel
                                    for="purchase_number"
                                    value="Purchase No."
                                />
                                <TextInput
                                    id="purchase_number"
                                    type="text"
                                    class="mt-1 block w-full h-10 bg-gray-100"
                                    v-model="model.purchase_number"
                                    required
                                    autofocus
                                    autocomplete="off"
                                    placeholder="Purchase No."
                                    disabled
                                />
                            </div>
                            <div class="">
                                <InputLabel
                                    for="supplier"
                                    value="Supplier"
                                />
                                <TextInput
                                    id="supplier"
                                    type="text"
                                    class="mt-1 block w-full h-10 bg-gray-100"
                                    v-model="model.supplier.name"
                                    required
                                    autocomplete="off"
                                    placeholder="Supplier"
                                    disabled
                                />
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2 sm:gap-6 mb-5">
                            <div class="">
                                <InputLabel
                                    for="purchase_date"
                                    value="Purchase Date"
                                />
                                <TextInput
                                    id="purchase_date"
                                    type="text"
                                    class="mt-1 block w-full h-10 bg-gray-100"
                                    v-model="model.purchase_date"
                                    required
                                    autocomplete="off"
                                    placeholder="Purchase Date"
                                    disabled
                                />
                            </div>
                            <div class="">
                                <InputLabel
                                    for="total"
                                    value="Total Amount"
                                />
                                <TextInput
                                    id="total"
                                    type="text"
                                    class="mt-1 block w-full h-10 bg-gray-100"
                                    v-model="model.total"
                                    required
                                    autocomplete="off"
                                    placeholder="Total Amount"
                                    disabled
                                />
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2 sm:gap-6 mb-5">
                            <div class="">
                                <InputLabel
                                    value="Status"
                                />
                                <div class="flex">
                                    <div
                                        class="mt-1 px-4 rounded-lg text-white text-sm inline-lg h-10 flex items-center"
                                        :class="[model.is_approved ? 'bg-green-500' : 'bg-yellow-500']"
                                    >
                                        {{ model.is_approved ? 'Appoved' : 'Pending' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </Card>
                <Card>
                    <template #card-header>
                        <div class="flex items-center justify-between w-full">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Supplier Details
                            </h3>
                            <div>
                                <a
                                    :href="'/suppliers/' + model.supplier_id + '/edit'"
                                    target="_blank"
                                    class="text-sm bg-green-500 text-white py-1 px-3 rounded-lg hover:bg-green-600 flex items-center gap-x-1"
                                    title="View Supplier"
                                >
                                    <EyeIcon class="w-4 h-4" />
                                    <span>View</span>
                                </a>
                            </div>
                        </div>
                    </template>
                    <template #card-body>
                        <div class="grid gap-4 md:grid-cols-2 sm:gap-6 mb-5">
                            <div class="">
                                <InputLabel
                                    for="supplier_name"
                                    value="Name"
                                />
                                <TextInput
                                    id="supplier_name"
                                    type="text"
                                    class="mt-1 block w-full h-10 bg-gray-100"
                                    v-model="model.supplier.name"
                                    required
                                    autofocus
                                    autocomplete="off"
                                    placeholder=""
                                    disabled
                                />
                            </div>
                            <div class="">
                                <InputLabel
                                    for="email"
                                    value="Email"
                                />
                                <TextInput
                                    id="email"
                                    type="text"
                                    class="mt-1 block w-full h-10 bg-gray-100"
                                    v-model="model.supplier.email"
                                    required
                                    autocomplete="off"
                                    placeholder=""
                                    disabled
                                />
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2 sm:gap-6 mb-5">
                            <div class="">
                                <InputLabel
                                    for="supplier_phone_number"
                                    value="Phone No."
                                />
                                <TextInput
                                    id="supplier_phone_number"
                                    type="text"
                                    class="mt-1 block w-full h-10 bg-gray-100"
                                    v-model="model.supplier.phone_number"
                                    required
                                    autofocus
                                    autocomplete="off"
                                    placeholder=""
                                    disabled
                                />
                            </div>
                            <div class="">
                                <InputLabel
                                    for="shop_name"
                                    value="Shop Name"
                                />
                                <TextInput
                                    id="shop_name"
                                    type="text"
                                    class="mt-1 block w-full h-10 bg-gray-100"
                                    v-model="model.supplier.shop_name"
                                    required
                                    autocomplete="off"
                                    placeholder=""
                                    disabled
                                />
                            </div>
                        </div>
                        <div class="grid sm:gap-6">
                            <div class="">
                                <InputLabel
                                    for="supplier_address"
                                    value="Address"
                                />
                                <TextareaInput
                                    id="supplier_address"
                                    type="text"
                                    class="mt-1 block w-full bg-gray-100"
                                    v-model="model.supplier.address"
                                    required
                                    autofocus
                                    autocomplete="off"
                                    placeholder=""
                                    disabled
                                    rows="3"
                                />
                            </div>
                        </div>
                    </template>
                </Card>
            </div>
            <div class="grid gap-y-4 lg:gap-y-0 lg:gap-x-4">
                <Card>
                    <template #card-header>
                        <h3 class="text-lg font-semibold text-gray-900">
                        Products List ({{ model.purchase_items.length }})
                        </h3>
                    </template>
                    <template #card-body>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="text-gray-700 uppercase bg-gray-200">
                                    <tr>
                                        <th class="px-4 py-3">
                                            Product
                                        </th>
                                        <th class="px-4 py-3">
                                            Code
                                        </th>
                                        <th class="px-4 py-3">
                                            Current Stock
                                        </th>
                                        <th class="px-4 py-3">
                                            Quantity
                                        </th>
                                        <th class="px-4 py-3 text-end">
                                            Unit Cost
                                        </th>
                                        <th class="px-4 py-3 text-end">
                                            Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="purchase_item in model.purchase_items"
                                        :key="purchase_item.id"
                                        class="border-b hover:bg-gray-100"
                                    >
                                        <td class="px-4 py-3 text-gray-700">
                                            <a
                                                :href="'/products/' + purchase_item.product.id"
                                                class="hover:underline"
                                                target="_blank"
                                            >
                                                {{ purchase_item.product.name }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-gray-700">
                                            {{ purchase_item.product.product_code }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-700">
                                            {{ purchase_item.product.stock }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-700">
                                            {{ purchase_item.quantity }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-700 text-end">
                                            {{ purchase_item.unit_cost }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-700 text-end">
                                            {{ purchase_item.total }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </template>
                </Card>
            </div>
            <div class="pt-4 border-t">
                <div class="flex flex-col md:flex-row justify-between gap-3 md:gap-2">
                    <DynamicLink
                        :href="'/' + url"
                        type="secondary"
                    >
                        Back
                    </DynamicLink>
                    <a
                        v-if="!model.is_approved"
                        href="#"
                        class="bg-blue-500 hover:bg-blue-600 h-10 text-white px-4 rounded-md flex items-center"
                        @click="confirmApprove()"
                    >
                        <div class="flex items-center gap-x-1">
                            <CheckCircleIcon class="w-6 h-6" />
                            <span>Approve</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import { Head, router, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import {
    TruckIcon,
} from '@heroicons/vue/24/solid'
import {
    EyeIcon,
    CheckCircleIcon,
} from '@heroicons/vue/24/outline'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import DynamicLink from '@/Components/DynamicLink.vue'
import Card from '@/Components/Card.vue'
import TextareaInput from '@/Components/TextareaInput.vue'
import Swal from 'sweetalert2'

const moduleName = 'Purchases'
const url = 'purchases'
const pageTitle = 'View Purchase'

const props = defineProps({
    model: Object,
});

const model = props.model
// Convert Number to String
// model.is_approved = model.is_approved.toString()

const form = useForm({
    //
})

let confirmApprove = () => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#22C55E',
        confirmButtonText: 'Approve'
    }).then((result) => {
        if (result.isConfirmed) {
            approve()
        }
    })
}

let approve = () => {
    form.patch(route(url + '.update', props.model.id), {
        preserveScroll: true,
        onSuccess: () => {
            router.get('/' + url)
            Swal.fire({
                title: 'Approved',
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

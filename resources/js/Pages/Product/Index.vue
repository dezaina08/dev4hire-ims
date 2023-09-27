<template>
    <Head :title="pageTitle" />

    <AuthenticatedLayout>
        <div class="py-12 px-4 sm:px-6 lg:px-8 grid">
            <div class="mb-4">
                <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-y-4 sm:gap-y-0">
                    <div class="flex items-center justify-start text-gray-500">
                        <ShoppingBagIcon class="h-10 w-10 transition duration-75 mr-3" />
                        <h1 class="text-3xl">{{ pageTitle }}</h1>
                    </div>
                    <div class="flex gap-3">
                        <DynamicLink
                            :href="url + '/create'"
                            type="success"
                            class="h-10 w-full sm:w-auto"
                        >
                            <PlusIcon class="block h-5 w-5 mr-2" aria-hidden="true" />
                            New
                        </DynamicLink>
                    </div>
                </div>
                <div>
                    <Breadcrumb
                        :current="pageTitle"
                    />
                </div>
            </div>
            <div class="flex flex-col lg:flex-row justify-end md:mb-4 gap-y-2">
                <div
                    class="flex mb-4 lg:mb-0 flex-col lg:flex-row gap-x-4 gap-y-2"
                >
                    <Combobox
                        id="category_id"
                        ajaxUrl="/search-category?search="
                        v-on:update:model-value="updateCategoryId($event)"
                        inputClass="h-12 m-0"
                        required
                        placeholder="Search category"
                        :defaultSelected="props.category"
                    />
                    <Combobox
                        id="subcategory_id"
                        :ajaxUrl="'/search-subcategory?category_id=' + props.category?.id + '&search='"
                        v-on:update:model-value="updateSubcategoryId($event)"
                        inputClass="h-12"
                        required
                        placeholder="Search subcategory"
                        :defaultSelected="props.subcategory"
                        :disabled="!props.category?.id"
                    />
                    <div class="flex rounded-lg shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-inset focus-within:ring-blue-600 bg-white h-12 w-full md:w-auto">
                        <span class="flex select-none items-center pl-3 text-gray-500 text-sm">Created From: </span>
                        <input
                            type="date"
                            id="created_from"
                            autocomplete="off"
                            class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                            placeholder="From"
                            v-model="dateFrom"
                        >
                    </div>
                    <div class="flex rounded-lg shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-inset focus-within:ring-blue-600 bg-white h-12 w-full md:w-auto">
                        <span class="flex select-none items-center pl-3 text-gray-500 text-sm">Until: </span>
                        <input
                            type="date"
                            id="created_until"
                            autocomplete="off"
                            class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                            placeholder="Until"
                            v-model="dateUntil"
                        >
                    </div>
                </div>
            </div>
            <Table
                :url="url"
                :response="props.response"
                :table-header="tableHeader"
                :search="props.search"
                :order="props.order"
                :additionalArgumentProp="dateFromArgument + dateUntilArgument + categoryIdArgument + subcategoryIdArgument"
            >
                <template #tr>
                    <tr
                        v-if="props.response.data.length"
                        v-for="item in props.response.data"
                        :key="item.id"
                        class="border-b"
                    >
                        <td class="px-4 py-3 font-medium text-gray-700">
                            {{ item.name }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-700">
                            {{ item.product_code }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-700">
                            {{ item.stock }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-700">
                            {{ item.buying_price }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-700">
                            {{ item.selling_price }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-700">
                            {{ item.unit.name }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-700">
                            {{ item.category.name }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-700">
                            {{ item.subcategory.name }}
                        </td>
                        <!-- Actions -->
                        <td class="text-sm text-gray-700 font-light px-4 py-3">
                            <div class="flex gap-2">
                                <Link
                                    :href="url + '/' + item.id"
                                    class="text-green-400 hover:text-white transition duration-200 ease-in-out border border-green-400 hover:border-green-600 rounded-md py-1 px-1 hover:bg-green-500"
                                    title="View"
                                >
                                    <EyeIcon class="block h-5 w-5" aria-hidden="true" />
                                </Link>
                                <Link
                                    :href="url + '/' + item.id + '/edit'"
                                    class="text-blue-400 hover:text-white transition duration-200 ease-in-out border border-blue-400 hover:border-blue-600 rounded-md py-1 px-1 hover:bg-blue-500"
                                    title="Edit"
                                >
                                    <PencilSquareIcon class="block h-5 w-5" aria-hidden="true" />
                                </Link>
                                <button
                                    type="button"
                                    class="text-red-400 hover:text-white transition duration-200 ease-in-out border border-red-400 hover:border-red-600 rounded-md py-1 px-1 hover:bg-red-500"
                                    title="Delete"
                                    @click="deleteItem.confirmDelete(url, item.id)"
                                >
                                    <TrashIcon class="block h-5 w-5" aria-hidden="true" />
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr
                        v-else
                        class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100"
                    >
                        <td
                            :colspan="tableHeader.length"
                            class="text-sm text-gray-900 font-light px-6 py-3 whitespace-nowrap text-center"
                        >
                            No records
                        </td>
                    </tr>
                </template>
            </Table>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import {
    EyeIcon,
    PencilSquareIcon,
    TrashIcon,
    PlusIcon
} from '@heroicons/vue/24/outline'
import { ShoppingBagIcon } from '@heroicons/vue/24/solid'
import { ref, computed } from 'vue'
import DynamicLink from '@/Components/DynamicLink.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import Table from '@/Components/Table/Table.vue'
import { useDeleteItemStore } from '@/stores/deleteItem'
import Combobox from '@/Components/Combobox.vue'

const url = 'products'
const pageTitle = 'Products'
const deleteItem = useDeleteItemStore()

const props = defineProps({
    response: Object,
    search: {
        type: String,
        default: '',
    },
    order: {
        type: Object,
        default: {
            orderBy: '',
            orderType: ''
        }
    },
    dateFrom: {
        type: String,
        default: '',
    },
    dateUntil: {
        type: String,
        default: '',
    },
    category: {
        type: Object,
    },
    subcategory: {
        type: Object,
    }
})

// column = database column name, used for sortBy, null to disable sorting
const tableHeader = ref([
    {
        title: 'Name',
        class: 'px-4 py-3',
        column: 'name'
    },
    {
        title: 'Product Code',
        class: 'px-4 py-3',
        column: 'product_code'
    },
    {
        title: 'Stock',
        class: 'px-4 py-3',
        column: 'stock'
    },

    {
        title: 'Buying Price',
        class: 'px-4 py-3',
        column: 'buying_price'
    },
    {
        title: 'Selling Price',
        class: 'px-4 py-3',
        column: 'selling_price'
    },
    {
        title: 'Unit',
        class: 'px-4 py-3',
        column: 'unit_id'
    },
    {
        title: 'Category',
        class: 'px-4 py-3',
        column: 'category_id'
    },
    {
        title: 'Subcategory',
        class: 'px-4 py-3',
        column: 'subcategory_id'
    },
    {
        title: 'Actions',
        class: 'px-4 py-3',
        column: null
    },
])

// Deep Copy
const dateFrom = props.dateFrom != ''
    ? ref(JSON.parse(JSON.stringify(props.dateFrom)))
    : ref('')

const dateUntil = props.dateUntil != ''
    ? ref(JSON.parse(JSON.stringify(props.dateUntil)))
    : ref('')

const categoryId = props.category != ''
    ? ref(JSON.parse(JSON.stringify(props.category.id)))
    : ref('')

const subcategoryId = props.subcategory != ''
    ? ref(JSON.parse(JSON.stringify(props.subcategory.id)))
    : ref('')

let dateFromArgument = computed(() => {
    return dateFrom.value != ''
        ? 'dateFrom=' + dateFrom.value
        : ''
})

let dateUntilArgument = computed(() => {
    return dateUntil.value != ''
        ? (dateFromArgument.value != ''
            ? '&'
            : '') + 'dateUntil=' + dateUntil.value
        : ''
})

let categoryIdArgument = computed(() => {
    return categoryId.value != ''
        ? (dateFromArgument.value != ''
            ? '&'
            : '') + 'categoryId=' + categoryId.value
        : ''
})

let subcategoryIdArgument = computed(() => {
    return subcategoryId.value != ''
        ? (categoryIdArgument.value != ''
            ? '&'
            : '') + 'subcategoryId=' + subcategoryId.value
        : ''
})

let updateCategoryId = (event) => {
    categoryId.value = !!event ? event.id : ''
    subcategoryId.value = ''
}

let updateSubcategoryId = (event) => {
    subcategoryId.value = !!event ? event.id : ''
}
</script>
<style lang="">

</style>

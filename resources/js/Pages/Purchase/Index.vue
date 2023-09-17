<template>
    <Head :title="pageTitle" />

    <AuthenticatedLayout>
        <div class="py-12 px-4 sm:px-6 lg:px-8 grid">
            <div class="mb-4">
                <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-y-4 sm:gap-y-0">
                    <div class="flex items-center justify-start text-gray-500">
                        <TruckIcon class="h-10 w-10 transition duration-75 mr-3" />
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
            <div class="flex flex-col lg:flex-row justify-between mb-4 gap-y-2">
                <div class="rounded-lg lg:flex justify-center items-center shadow-sm ring-1 ring-gray-200 block">
                    <TabGroup
                        :defaultIndex="statusIndex"
                        @change="changeStatus"
                    >
                        <TabList
                            class="flex space-x-3 rounded-lg h-12 bg-white p-1.5"
                        >
                            <Tab
                                v-for="item in tabItems"
                                as="template"
                                :key="item"
                                v-slot="{ selected }"
                            >
                                <button
                                    :class="[
                                    'w-full rounded-lg text-sm font-medium leading-5',
                                    'focus:outline-none px-5 py-2 flex items-center',
                                    selected
                                        ? 'shadow ' + item.class
                                        : 'hover:bg-gray-50 hover:text-whites text-gray-700',
                                    ]"
                                >
                                    {{ item.title }}
                                </button>
                            </Tab>
                        </TabList>
                    </TabGroup>
                </div>
                <div
                    class="flex mb-4 lg:mb-0 flex-col lg:flex-row gap-x-4 gap-y-2"
                >
                    <div class="flex rounded-lg shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-inset focus-within:ring-blue-600 bg-white h-12 w-full md:w-auto">
                        <span class="flex select-none items-center pl-3 text-gray-500 text-sm">From: </span>
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
                :dateFrom="dateFrom"
                :dateUntil="dateUntil"
                :additionalArgumentProp="dateFromArgument + dateUntilArgument + statusArgument"
            >
                <template #tr>
                    <tr
                        v-if="props.response.data.length"
                        v-for="item in props.response.data"
                        :key="item.id"
                        class="border-b"
                    >
                        <td class="px-4 py-3 text-gray-700">
                            {{ item.id }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-700">
                            {{ item.purchase_number }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-700">
                            <a
                                :href="'/suppliers/' + item.supplier_id + '/edit'"
                                target="_blank"
                                class="hover:underline"
                            >
                                {{ item.supplier.name }}
                            </a>
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-700">
                            {{ item.purchase_date }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-700">
                            <span
                                class="px-2 py-1 rounded-md text-xs font-bold"
                                :class="[item.is_approved
                                ? 'border border-green-300 bg-green-100 text-green-500'
                                : 'border border-amber-300 bg-amber-100 text-amber-600']"
                            >
                                {{ item.is_approved ? 'Appoved' : 'Pending' }}
                            </span>
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
                                <!-- <Link
                                    :href="url + '/' + item.id + '/edit'"
                                    class="text-blue-400 hover:text-white transition duration-200 ease-in-out border border-blue-400 hover:border-blue-600 rounded-md py-1 px-1 hover:bg-blue-500"
                                    title="Edit"
                                >
                                    <PencilSquareIcon class="block h-5 w-5" aria-hidden="true" />
                                </Link> -->
                                <a
                                    href="#"
                                    class="text-red-400 hover:text-white transition duration-200 ease-in-out border border-red-400 hover:border-red-600 rounded-md py-1 px-1 hover:bg-red-500"
                                    title="Delete"
                                    @click="deleteItem.confirmDelete(url, item.id)"
                                >
                                    <TrashIcon class="block h-5 w-5" aria-hidden="true" />
                                </a>
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
import { TruckIcon } from '@heroicons/vue/24/solid'
import { ref, computed } from 'vue'
import DynamicLink from '@/Components/DynamicLink.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import Table from '@/Components/Table/Table.vue'
import { useDeleteItemStore } from '@/stores/deleteItem'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'

const url = 'purchases'
const pageTitle = 'Purchases'
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
        default: null,
    },
    dateUntil: {
        type: String,
        default: null,
    },
    status: {
        type: String,
        default: '0',
    }
})

// column = database column name, used for sortBy, null to disable sorting
const tableHeader = ref([
    {
        title: 'ID',
        class: 'px-4 py-3',
        column: 'id'
    },
    {
        title: 'Purchase No.',
        class: 'px-4 py-3',
        column: 'purchase_number'
    },
    {
        title: 'Supplier',
        class: 'px-4 py-3',
        column: 'supplier_id'
    },
    {
        title: 'Purchase Date',
        class: 'px-4 py-3',
        column: 'purchase_date'
    },
    {
        title: 'Status',
        class: 'px-4 py-3',
        column: 'is_approved'
    },
    {
        title: 'Actions',
        class: 'px-4 py-3',
        column: null
    },
])


const tabItems = ref([
    {
        title: "All",
        class: "bg-blue-100 text-blue-600"
    },
    {
        title: "Pending",
        class: "bg-amber-100 text-amber-600"
    },
    {
        title: "Approved",
        class: "bg-green-100 text-green-600"
    },
])

// Deep Copy
const dateFrom = props.dateFrom != ''
    ? ref(JSON.parse(JSON.stringify(props.dateFrom)))
    : ref('')

const dateUntil = props.dateUntil != ''
    ? ref(JSON.parse(JSON.stringify(props.dateUntil)))
    : ref('')

const status = props.status != ''
    ? ref(JSON.parse(JSON.stringify(props.status)))
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

let statusArgument = computed(() => {
    return status.value != ''
        ? (dateFromArgument.value != '' || dateUntilArgument.value != ''
            ? '&'
            : '') + 'status=' + status.value
        : ''
})

let statusIndex = computed(() => {
    if (status.value == '1') {
        return 2
    } else if (status.value == '0') {
        return 1
    } else {
        return 0
    }
})

let changeStatus = (index) => {
    switch (index) {
        case 0:
            status.value = ''
            break;
        case 1:
            status.value = '0'
            break;
        case 2:
            status.value = '1'
            break;

        default:
            status.value = ''
            break;
    }
}
</script>
<style lang="">

</style>

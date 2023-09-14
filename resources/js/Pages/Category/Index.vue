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
            <Table
                :url="url"
                :response="props.response"
                :table-header="tableHeader"
                :search="props.search"
                :order="props.order"
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
                        <td class="px-4 py-3 font-medium text-gray-700 whitespace-nowrap">
                            {{ item.name }}
                        </td>
                        <!-- Actions -->
                        <td class="text-sm text-gray-700 font-light px-4 py-3 whitespace-nowrap">
                            <div class="flex gap-2">
                                <!-- <Link
                                    :href="url + '/' + item.id"
                                    class="text-green-400 hover:text-white transition duration-200 ease-in-out border border-green-400 hover:border-green-600 rounded-md py-1 px-1 hover:bg-green-500"
                                    title="View"
                                >
                                    <EyeIcon class="block h-5 w-5" aria-hidden="true" />
                                </Link> -->
                                <Link
                                    :href="url + '/' + item.id + '/edit'"
                                    class="text-blue-400 hover:text-white transition duration-200 ease-in-out border border-blue-400 hover:border-blue-600 rounded-md py-1 px-1 hover:bg-blue-500"
                                    title="Edit"
                                >
                                    <PencilSquareIcon class="block h-5 w-5" aria-hidden="true" />
                                </Link>
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
import { TagIcon } from '@heroicons/vue/24/solid'
import { ref } from 'vue'
import DynamicLink from '@/Components/DynamicLink.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import Table from '@/Components/Table/Table.vue'
import { useDeleteItemStore } from '@/stores/deleteItem'

const url = 'categories'
const pageTitle = 'Categories'
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
})

// column = database column name, used for sortBy, null to disable sorting
const tableHeader = ref([
    {
        title: 'ID',
        class: 'px-4 py-3',
        column: 'id'
    },
    {
        title: 'Name',
        class: 'px-4 py-3',
        column: 'name'
    },
    {
        title: 'Actions',
        class: 'px-4 py-3',
        column: null
    },
])
</script>
<style lang="">

</style>

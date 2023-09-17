<template>
    <div class="space-y-4 bg-white relative shadow-md rounded-lg overflow-hidden z-10">
        <div class="flex justify-between gap-y-4 px-4 pt-5 gap-x-10">
            <div class="flex items-center justify-center gap-x-2">
                <label for="per_page" class="block text-sm font-medium text-gray-700 dark:text-white whitespace-nowrap">Per Page</label>
                <select
                    v-model="currentPerPage"
                    id="per_page"
                    name="per_page"
                    autocomplete="per_page"
                    class="block w-full rounded-md border border-gray-300 bg-white dark:bg-gray-700 h-10 pl-3 pr-8 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm text-gray-700 dark:text-white"
                >
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div class="flex">
                <div class="flex items-center rounded-lg shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-inset focus-within:ring-blue-600 bg-white h-10 w-full md:w-auto pr-7 relative">
                    <span class="flex select-none items-center pl-3 text-gray-500 sm:text-sm">
                        <MagnifyingGlassIcon
                            class="h-6 w-6"
                        />
                    </span>
                    <input
                        type="text"
                        id="search"
                        autocomplete="off"
                        class="block flex-1 border-0 bg-transparent py-1.5 pl-2 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                        placeholder="Search"
                        v-model="search"
                        @keyup.enter="searchRecords()"
                    >
                    <button
                        v-if="search"
                        class="w-6 h-6 flex items-center justify-center absolute right-2"
                        @click="clearSearch()"
                    >
                        <XMarkIcon
                            class="h-4 w-4"
                        />
                    </button>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto mb-4">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th
                            v-for="header in tableHeader"
                            :key="header.title"
                            :class="header.class"
                            scope="col"
                        >
                            <button
                                type="button"
                                class="flex items-center gap-x-2"
                                @click="setOrderBy(header.column)"
                                :disabled="header.column == null"
                                :class="[header.column == orderBy ? 'text-blue-600 hover:text-blue-700' : 'hover:text-gray-900']"
                                :title="'Sort by ' + header.title"
                            >
                                {{ header.title }}
                                <ChevronUpDownIcon
                                    v-if="header.column != null"
                                    class="h-5 w-5"
                                />
                            </button>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <slot name="tr">
                    </slot>
                </tbody>
            </table>
        </div>
        <PaginationComponent
            class="px-4 pb-4"
            :totalPages="props.response.last_page ?? 0"
            :perPage="parseInt(currentPerPage) ?? 0"
            :currentPage="props.response.current_page ?? 0"
            :from="props.response.from ?? 0"
            :to="props.response.to ?? 0"
            :total="props.response.total ?? 0"
            :url="props.url"
            :search="search"
            :orderBy="orderBy"
            :orderType="orderType"
            :additionalArgumentProp="additionalArgumentProp"
        />
    </div>
</template>
<script setup>
import PaginationComponent from '@/Components/Table/Pagination.vue'
import {
    MagnifyingGlassIcon,
    ChevronUpDownIcon,
    XMarkIcon,
} from '@heroicons/vue/24/outline'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    response: Object,
    search: {
        type: String,
        default: '',
    },
    url: {
        type: String,
        default: ''
    },
    tableHeader: Object,
    order: {
        type: Object,
        default: {
            orderBy: '',
            orderType: ''
        }
    },
    additionalArgumentProp: {
        type: String,
        default: '',
    }
})

const currentPerPage = ref(JSON.parse(JSON.stringify(props.response.per_page ?? 10)))

const search = props.search != ''
    ? ref(JSON.parse(JSON.stringify(props.search)))
    : ref('')

const orderBy = props.order.orderBy != ''
    ? ref(JSON.parse(JSON.stringify(props.order.orderBy)))
    : ref('')

const orderType = props.order.orderType != ''
    ? ref(JSON.parse(JSON.stringify(props.order.orderType)))
    : ref('')

const setOrderBy = (column) => {
    if (orderBy.value != column) {
        orderBy.value = column
        orderType.value = ''
    } else {
        if (orderType.value == 'desc') {
            orderType.value = 'asc'
        } else {
            // reset values
            orderType.value = ''
            orderBy.value = ''
        }
    }
}

const searchRecords = () => {
    router.get(search.value ? '/' + props.url + '/?search=' + search.value : '/' + props.url)
}

const clearSearch = () => {
    search.value = ''
    searchRecords()
}
</script>
<style lang="scss">

</style>

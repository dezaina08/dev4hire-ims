<template>
    <div class="flex flex-col md:flex-row items-center justify-between gap-y-3">
        <div class="flex gap-x-6 flex-col md:flex-row gap-y-3">
            <ul class="flex items-center">
                <li class="flex items-center">
                    <button
                        type="button"
                        @click="onClickFirstPage"
                        :disabled="isInFirstPage"
                        :class="[isInFirstPage ? 'pointer-events-none text-gray-500' : 'text-gray-700', 'relative inline-flex items-center rounded-l-md border border-gray-300 bg-white dark:bg-gray-700 px-3 h-10 text-sm font-medium hover:bg-gray-100 focus:z-20']"
                        title="Last"
                    >
                        <ChevronDoubleLeftIcon
                            class="block h-4 w-4"
                            aria-hidden="true"
                        />
                    </button>
                </li>

                <li class="flex items-center">
                    <button
                        type="button"
                        @click="onClickPreviousPage"
                        :disabled="isInFirstPage"
                        :class="[isInFirstPage ? 'pointer-events-none text-gray-500' : 'text-gray-700', 'relative inline-flex items-center border border-gray-300 bg-white dark:bg-gray-700 px-3 h-10 text-sm font-medium hover:bg-gray-100 focus:z-20']"
                        title="Previous"
                    >
                        <ChevronLeftIcon
                            class="block h-4 w-4"
                            aria-hidden="true"
                        />
                    </button>
                </li>

                <!-- Visible Buttons Start -->

                <li
                    v-for="page in pages"
                    :key="page.name"
                    >
                    <button
                        type="button"
                        :disabled="page.isDisabled"
                        @click="onClickPage(page.name)"
                        :class="[page.isDisabled ? 'border-blue-500 bg-blue-50 text-blue-600 z-20' : 'bg-white dark:bg-gray-700 text-gray-700 hover:bg-gray-100 border-gray-300', 'relative inline-flex items-center border px-4 h-10 text-sm font-medium m-0']"
                    >
                        {{ page.name }}
                    </button>
                </li>

                <!-- Visible Buttons End -->

                <li class="lex items-center">
                    <button
                        type="button"
                        @click="onClickNextPage"
                        :disabled="isInLastPage"
                        :class="[isInLastPage ? 'pointer-events-none text-gray-500' : 'text-gray-700', 'relative inline-flex items-center border border-gray-300 bg-white dark:bg-gray-700 px-3 h-10 text-sm font-medium hover:bg-gray-100 focus:z-20']"
                        title="Next"
                    >
                        <ChevronRightIcon
                            class="block h-4 w-4"
                            aria-hidden="true"
                        />
                    </button>
                </li>

                <li class="flex items-center">
                    <button
                        type="button"
                        @click="onClickLastPage"
                        :disabled="isInLastPage"
                        :class="[isInLastPage ? 'pointer-events-none text-gray-500' : 'text-gray-700', 'relative inline-flex items-center rounded-r-md border border-gray-300 bg-white dark:bg-gray-700 px-3 h-10 text-sm font-medium hover:bg-gray-100 focus:z-20']"
                        title="Last"
                    >
                        <ChevronDoubleRightIcon
                            class="block h-4 w-4"
                            aria-hidden="true"
                        />
                    </button>
                </li>
            </ul>
        </div>
        <div>
            <p class="text-sm text-gray-700 dark:text-white">
                Showing
                {{ ' ' }}
                <span class="font-medium">
                    {{ from }}
                </span>
                {{ ' ' }}
                to
                {{ ' ' }}
                <span class="font-medium">
                    {{ to }}
                </span>
                {{ ' ' }}
                of
                {{ ' ' }}
                <span class="font-medium">
                    {{ total }}
                </span>
                {{ ' ' }}
                results
            </p>
        </div>
    </div>
</template>

<script setup>
import {
    ChevronDoubleLeftIcon,
    ChevronLeftIcon,
    ChevronDoubleRightIcon,
    ChevronRightIcon
} from '@heroicons/vue/24/outline'
import { router } from '@inertiajs/vue3'
import { ref, computed, watch, onMounted } from 'vue'

const props = defineProps({
    maxVisibleButtons: {
        type: Number,
        required: false,
        default: 7
    },
    totalPages: {
        type: Number,
        required: true
    },
    perPage: {
        type: Number,
        required: true
    },
    currentPage: {
        type: Number,
        required: true
    },
    from: {
        type: Number,
        required: true
    },
    to: {
        type: Number,
        required: true
    },
    total: {
        type: Number,
        required: true
    },
    url: {
        type: String,
        required: true,
    },
    search: {
        type: String,
        default: '',
    },
    orderBy: {
        type: String,
        default: ''
    },
    orderType: {
        type: String,
        default: ''
    },
    additionalArgumentProp: {
        type: String,
        default: '',
    }
})

const queryString = ref('')

let startPage = computed(() => {
    // When on the first page
    if (props.currentPage === 1) {
        return 1;
    }

    // When on the last page
    if (props.currentPage === props.totalPages) {
        const start = props.totalPages - (props.maxVisibleButtons - 1);

        if (start === 0) {
            return 1;
        } else {
            return start;
        }
    }

    // When inbetween
    // return props.currentPage - 1

    // When inbetween
    let tempCurrentPage = props.currentPage

    if (tempCurrentPage - 3 < 1) {
        return props.currentPage - 1
    } else {
        return props.currentPage - 3;
    }
})

let pages = computed(() => {
    const range = [];

    for (
        let i = startPage.value;
        i <= Math.min(startPage.value + props.maxVisibleButtons - 1, props.totalPages);
        i++
    ) {
        range.push({
            name: i,
            isDisabled: i === props.currentPage
        });
    }

    return range;
})

let isInFirstPage = computed(() => {
    return props.currentPage === 1;
})

let isInLastPage = computed(() => {
    return props.currentPage === props.totalPages;
})

watch(() => JSON.parse(JSON.stringify(props.perPage)), (newValue, oldValue) => {
    queryTable(false, true)
})

watch(() => JSON.parse(JSON.stringify(props.orderBy)), (newValue, oldValue) => {
    queryTable(false, false)
})

watch(() => JSON.parse(JSON.stringify(props.orderType)), (newValue, oldValue) => {
    queryTable(false, false)
})

watch(() => JSON.parse(JSON.stringify(props.additionalArgumentProp)), (newValue, oldValue) => {
    queryTable(false, false)
})

let onClickFirstPage = () => {
    queryTable(1)
}
let onClickPreviousPage = () => {
    queryTable(props.currentPage - 1)
}
let onClickPage = (page) => {
    queryTable(page)
}
let onClickNextPage = () => {
    queryTable(props.currentPage + 1)
}
let onClickLastPage = () => {
    queryTable(props.totalPages)
}

let queryTable = (page = false, perPageChanged = false) => {

    if (!page) {
        page = props.currentPage
    }

    if (perPageChanged) {
        page = 1
    }

    // logic to add '&' before argument name
    let pageArgument = (page != 1
        ? 'page=' + page
        : '')
    let perPageArgument = (props.perPage != 10
        ? (pageArgument !=''
            ? '&'
            : '') + 'perPage=' + props.perPage
        : '')
    let searchArgument = (props.search
        ? (pageArgument !='' || perPageArgument !=''
            ? '&'
            : '') + 'search=' + props.search
        : '')
    let orderByArgument = (props.orderBy != 'id' & props.orderBy != ''
        ? (pageArgument != '' || perPageArgument !='' || searchArgument != ''
            ? '&'
            : '') + 'orderBy=' + props.orderBy
        : '')
    let orderTypeArgument = (props.orderType != 'desc' & props.orderType != ''
        ? (pageArgument != '' || perPageArgument !='' || searchArgument != '' || orderByArgument != ''
            ? '&'
            : '') + 'orderType=' + props.orderType
        : '')
    let additionalArgument = (props.additionalArgumentProp != ''
        ? (pageArgument != '' || perPageArgument !='' || searchArgument != '' || orderByArgument != '' || orderTypeArgument != ''
            ? '&'
            : '') + props.additionalArgumentProp
        : '')

    queryString.value = '?' +
        pageArgument +
        perPageArgument +
        searchArgument +
        orderByArgument +
        orderTypeArgument +
        additionalArgument

    let query = props.url + queryString.value

    router.get(
        query,
        {},
        { preserveScroll: true }
    )
}
</script>

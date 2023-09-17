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

<script>
import {
    ChevronDoubleLeftIcon,
    ChevronLeftIcon,
    ChevronDoubleRightIcon,
    ChevronRightIcon
} from '@heroicons/vue/24/outline'

export default {
    components: {
        ChevronDoubleLeftIcon,
        ChevronLeftIcon,
        ChevronDoubleRightIcon,
        ChevronRightIcon,
    },
    props: {
        maxVisibleButtons: {
            type: Number,
            required: false,
            default: 3
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
    },
    computed: {
        startPage() {
            // When on the first page
            if (this.currentPage === 1) {
                return 1;
            }

            // When on the last page
            if (this.currentPage === this.totalPages) {
                const start = this.totalPages - (this.maxVisibleButtons - 1);

                if (start === 0) {
                    return 1;
                } else {
                    return start;
                }
            }

            // When inbetween
            return this.currentPage - 1

            // When inbetween
            // let tempCurrentPage = this.currentPage

            // if (tempCurrentPage - 2 < 1) {
            //     return this.currentPage - 1
            // } else {
            //     return this.currentPage - 2;
            // }
        },
        pages() {
            const range = [];

            for (
                let i = this.startPage;
                i <= Math.min(this.startPage + this.maxVisibleButtons - 1, this.totalPages);
                i++
            ) {
                range.push({
                    name: i,
                    isDisabled: i === this.currentPage
                });
            }

            return range;
        },
        isInFirstPage() {
            return this.currentPage === 1;
        },
        isInLastPage() {
            return this.currentPage === this.totalPages;
        },
    },
    data() {
        return {
            queryString: '',
        }
    },
    watch: {
        perPage: function(newVal, oldVal) {
            this.queryTable(false, true)
        },
        orderBy: function(newVal, oldVal) {
            this.queryTable(false, false)
        },
        orderType: function(newVal, oldVal) {
            this.queryTable(false, false)
        },
        additionalArgumentProp: function(newVal, oldVal) {
            this.queryTable(false, false)
        },
    },
    methods: {
        onClickFirstPage() {
            this.queryTable(1)
        },
        onClickPreviousPage() {
            this.queryTable(this.currentPage - 1)
        },
        onClickPage(page) {
            this.queryTable(page)
        },
        onClickNextPage() {
            this.queryTable(this.currentPage + 1)
        },
        onClickLastPage() {
            this.queryTable(this.totalPages)
        },
        queryTable(page = false, perPageChanged = false) {

            if (!page) {
                page = this.currentPage
            }

            if (perPageChanged) {
                page = 1
            }

            // logic to add '&' before argument name
            let pageArgument = (page != 1
                ? 'page=' + page
                : '')
            let perPageArgument = (this.perPage != 10
                ? (pageArgument !=''
                    ? '&'
                    : '') + 'perPage=' + this.perPage
                : '')
            let searchArgument = (this.search
                ? (pageArgument !='' || perPageArgument !=''
                    ? '&'
                    : '') + 'search=' + this.search
                : '')
            let orderByArgument = (this.orderBy != 'id' & this.orderBy != ''
                ? (pageArgument != '' || perPageArgument !='' || searchArgument != ''
                    ? '&'
                    : '') + 'orderBy=' + this.orderBy
                : '')
            let orderTypeArgument = (this.orderType != 'desc' & this.orderType != ''
                ? (pageArgument != '' || perPageArgument !='' || searchArgument != '' || orderByArgument != ''
                    ? '&'
                    : '') + 'orderType=' + this.orderType
                : '')
            let additionalArgument = (this.additionalArgumentProp != ''
                ? (pageArgument != '' || perPageArgument !='' || searchArgument != '' || orderByArgument != '' || orderTypeArgument != ''
                    ? '&'
                    : '') + this.additionalArgumentProp
                : '')

            this.queryString = '?' +
                pageArgument +
                perPageArgument +
                searchArgument +
                orderByArgument +
                orderTypeArgument +
                additionalArgument

            let query = this.url + this.queryString

            this.$inertia.get(
                query,
                {},
                { preserveScroll: true }
            )
        },
    }
}
</script>

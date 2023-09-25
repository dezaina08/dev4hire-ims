<template>
    <Card>
        <template #card-header>
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">
                    Product List
                </h3>
            </div>
        </template>
        <template #card-body>
            <StatelessTable
                :url="url"
                :response="response"
                :table-header="tableHeader"
                :search="search"
                :order="order"
                v-on:updateTable="fetchData($event)"
            >
                <template #tr>
                    <tr
                        v-if="response?.data.length"
                        v-for="item in response.data"
                        :key="item.id"
                        class="border-b"
                    >
                        <td class="px-4 py-3 text-gray-700">
                            {{ item.id }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-700">
                            {{ item.name }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-700">
                            {{ item.product_code }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-700">
                            {{ item.category.name }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-700">
                            {{ item.subcategory.name }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-700">
                            {{ item.unit.name }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-700">
                            {{ item.buying_price }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-700">
                            {{ item.selling_price }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-700">
                            {{ item.stock }}
                        </td>
                        <td class="text-sm text-gray-700 font-light px-4 py-3 whitespace-nowrap">
                            <div class="flex gap-2">
                                <a
                                    target="_blank"
                                    :href="'/products/' + item.id + '/edit'"
                                    class="text-blue-400 hover:text-white transition duration-200 ease-in-out border border-blue-400 hover:border-blue-600 rounded-md py-1 px-1 hover:bg-blue-500"
                                    title="Edit"
                                >
                                    <PencilSquareIcon class="block h-5 w-5" aria-hidden="true" />
                                </a>
                                <button
                                    type="button"
                                    href="#"
                                    class="text-red-400 hover:text-white transition duration-200 ease-in-out border border-red-400 hover:border-red-600 rounded-md py-1 px-1 hover:bg-red-500"
                                    title="Delete"
                                    @click="confirmDelete(item.id)"
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
            </StatelessTable>
        </template>
    </Card>
</template>
<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import {
    EyeIcon,
    PencilSquareIcon,
    TrashIcon,
    PlusIcon
} from '@heroicons/vue/24/outline'
import StatelessTable from '@/Components/Table/StatelessTable.vue'
import { ref, onMounted } from 'vue'
import Swal from 'sweetalert2'
import Card from '@/Components/Card.vue'
import FormModal from '@/Pages/Category/EditPartials/FormModal.vue'

const url = ref('products-by-category')
const response = ref(null)
const search = ref('')
const order = ref({
    orderBy: 'id',
    orderType: 'desc'
})
const currentUrl = ref('')

const props = defineProps({
    modelId: {
        type: String,
        default: '',
    }
})

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
        title: 'Product Code',
        class: 'px-4 py-3',
        column: 'product_code'
    },
    {
        title: 'Category',
        class: 'px-4 py-3',
        column: null
    },
    {
        title: 'Subcategory',
        class: 'px-4 py-3',
        column: null
    },
    {
        title: 'Unit',
        class: 'px-4 py-3',
        column: null
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
        title: 'Stock',
        class: 'px-4 py-3',
        column: 'stock'
    },
    {
        title: 'Actions',
        class: 'px-4 py-3',
        column: null
    },
])

const deleteForm = useForm({})

let fetchData = (link = '/' + url.value) => {
    currentUrl.value = link
    axios.get(
        link,
        {
            params: {
                model_id: props.modelId,
            }
        }
    )
    .then((res) => {
        response.value = res.data.response
        search.value = res.data.search
        order.value = res.data.order
    })
    .catch(function (error) {
        console.log(error)
    })
}

let confirmDelete = (id) => {

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteItem(id)
        }
    })
}

let deleteItem = (id) => {

    let id_array = Array.isArray(id)
                ? Object.keys(id).map(index => id[index].id)
                : [id]

    deleteForm.transform(() => ({
            id_array: id_array,
        }))
        .delete(
        route(url.value + '.destroy', id),
        {
            preserveScroll: true,
            onSuccess: () => {
                fetchData(currentUrl.value)
                Swal.fire({
                    title: 'Deleted successfully',
                    // text: "Deleted successfully.",
                    icon: 'success',
                    confirmButtonColor: '#16A34A',
                })
            },
            onError: (error) => {
                Swal.fire({
                    title: 'Something went wrong',
                    text: "Please refresh the page.",
                    icon: 'error',
                    confirmButtonColor: '#d33',
                })
            },
        }
    )
}

onMounted(() => {
    fetchData()
})
</script>

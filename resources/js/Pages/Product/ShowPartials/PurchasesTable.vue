<template>
    <Card>
        <template #card-header>
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">
                    Purchase History
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
                            {{ item.purchase.purchase_number }}
                        </td>
                        <td class="px-4 py-3 text-gray-700">
                            {{ item.purchase.supplier.name }}
                        </td>
                        <td class="px-4 py-3 text-gray-700">
                            {{ item.unit_cost }}
                        </td>
                        <td class="px-4 py-3 text-gray-700">
                            {{ item.quantity}}
                        </td>
                        <td class="px-4 py-3 text-gray-700">
                            {{ item.total }}
                        </td>
                        <td class="px-4 py-3 text-gray-700">
                            {{ item.purchase.purchase_date }}
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
import { Link } from '@inertiajs/vue3'
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

const url = ref('purchases-by-product')
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
        title: 'Purchase Number',
        class: 'px-4 py-3',
        column: 'purchase_number'
    },
    {
        title: 'Supplier',
        class: 'px-4 py-3',
        column: 'supplier'
    },
    {
        title: 'Unit Cost',
        class: 'px-4 py-3',
        column: 'unit_cost'
    },
    {
        title: 'Quantity',
        class: 'px-4 py-3',
        column: 'quantity'
    },
    {
        title: 'Total',
        class: 'px-4 py-3',
        column: 'total'
    },
    {
        title: 'Purchase Date',
        class: 'px-4 py-3',
        column: null
    },
])

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

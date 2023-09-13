import { defineStore } from 'pinia'
import Swal from 'sweetalert2'
import { useForm } from '@inertiajs/vue3'

export const useDeleteItemStore = defineStore('deleteItem', {
    state: () => ({
        deleteForm: useForm({}),
    }),
    actions: {
        confirmDelete(url, id) {

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.deleteItem(url, id)
                }
            })
        },
        deleteItem(url, id) {

            let id_array = Array.isArray(id)
                        ? Object.keys(id).map(index => id[index].id)
                        : [id]

            this.deleteForm.transform(() => ({
                    id_array: id_array,
                }))
                .delete(
                route(url + '.destroy', id),
                {
                    preserveScroll: true,
                    onSuccess: () => {
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
    },
})

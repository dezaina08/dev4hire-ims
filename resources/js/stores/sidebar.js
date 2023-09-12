import { defineStore } from 'pinia'

export const useSidebarStore = defineStore('sidebar', {
    state: () => ({
        status: false,
    }),
    actions: {
        open() {
            this.status = true
        },
        close() {
            this.status = false
        },
    },
})

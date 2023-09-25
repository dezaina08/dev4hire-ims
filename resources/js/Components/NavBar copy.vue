<template>
    <div class="relative">
        <div class="h-16 w-full"></div>
        <nav class="fixed left-0 right-0 top-0 bg-white border-b border-gray-200 px-4 z-20 h-16 flex items-center shadow-md">
            <div class="flex flex-wrap justify-between items-center w-full">
                <div class="flex justify-start items-center">
                    <button
                        data-drawer-target="drawer-navigation"
                        data-drawer-toggle="drawer-navigation"
                        aria-controls="drawer-navigation"
                        class="p-2 mr-2 text-gray-600 rounded-lg cursor-pointer md:hidden hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 focus:ring-2 focus:ring-gray-100 toggle-sidebar"
                        @click="sidebar.open()"
                    >
                        <Bars3CenterLeftIcon
                            class="w-7 h-7"
                        />
                        <span class="sr-only">Toggle sidebar</span>
                    </button>
                    <a href="/" class="flex items-center justify-between mr-4">
                        <img src="https://flowbite.s3.amazonaws.com/logo.svg" class="mr-3 h-8" alt="Flowbite Logo" />
                        <span class="self-center text-2xl font-semibold whitespace-nowrap">Flowbite</span>
                    </a>
                </div>
                <div class="flex items-center lg:order-2">
                    <Menu as="div" class="relative inline-block text-left">
                        <div>
                            <MenuButton
                            class="inline-flex w-full justify-center items-center gap-x-3 rounded-md px-4 py-2 text-sm font-medium focus:outline-none text-gray-600 hover:bg-gray-50"
                            >
                                {{ usePage().props.auth.user.name }}
                                <img
                                    v-if="usePage().props.auth.user.profile_photo"
                                    :src="usePage().props.auth.user.profile_photo"
                                    alt="user photo"
                                    class="w-8 h-8 object-cover border-2 border-gray-300 rounded-full"
                                />
                                <div
                                    v-else
                                    class="bg-green-200 text-green-700 w-8 h-8 rounded-full flex justify-center items-center border-2 border-gray-300"
                                >
                                    {{ usePage().props.auth.user.name[0] }}
                                </div>
                            </MenuButton>
                        </div>

                        <transition
                            enter-active-class="transition duration-100 ease-out"
                            enter-from-class="transform scale-95 opacity-0"
                            enter-to-class="transform scale-100 opacity-100"
                            leave-active-class="transition duration-75 ease-in"
                            leave-from-class="transform scale-100 opacity-100"
                            leave-to-class="transform scale-95 opacity-0"
                        >
                            <MenuItems
                                class="absolute right-0 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                            >
                                <div class="py-3 px-4">
                                    <span class="block text-sm font-semibold text-gray-600">
                                        {{ usePage().props.auth.user.username }}
                                    </span
                                    >
                                    <span class="block text-sm text-gray-600 truncate">
                                        {{ usePage().props.auth.user.email }}
                                    </span
                                    >
                                    </div>
                                <div class="px-1 py-1">
                                    <MenuItem v-slot="{ active }">
                                        <Link
                                            :href="route('profile.edit')"
                                            class="block py-2 px-4 text-sm hover:bg-gray-100 text-gray-600"
                                        >
                                            My profile
                                        </Link>
                                    </MenuItem>
                                </div>

                                <div class="px-1 py-1">
                                    <MenuItem v-slot="{ active }">
                                        <Link
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                            class="block py-2 px-4 text-sm hover:bg-gray-100 w-full text-start text-gray-600"
                                        >
                                            Logout
                                        </Link>
                                    </MenuItem>
                                </div>
                            </MenuItems>
                        </transition>
                    </Menu>
                </div>
            </div>
        </nav>
    </div>
</template>
<script setup>
    import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
    import { Link, usePage } from '@inertiajs/vue3';
    import { useSidebarStore } from '../stores/sidebar'
    import { Bars3CenterLeftIcon } from '@heroicons/vue/24/solid'

    const sidebar = useSidebarStore()
</script>

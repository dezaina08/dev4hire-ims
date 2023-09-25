<template>
    <Link
        :href="href"
        :type="type"
        :class="classes[type]"
    >
        <slot />
    </Link>
</template>

<script setup>
    import { Link } from '@inertiajs/vue3';

    defineProps({
        href: {
            type: String,
            default: '/',
        },
        type: {
            type: String,
            default: 'primary',
        },
    });

    const classes = {
        primary: 'flex justify-center items-center px-4 h-10 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 focus:bg-blue-800 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150',

        success: 'flex justify-center items-center px-4 h-10 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150',

        'success-outlined': 'flex justify-center items-center px-4 h-10 bg-green-600s border-2 border-green-600 rounded-md font-semibold text-xs text-green-600 uppercase tracking-widest hover:bg-green-600 hover:text-white active:text-white focus:text-white focus:bg-green-600 active:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150',

        secondary: 'flex justify-center items-center px-4 h-10 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 hover:bg-gray-50',
    }
</script>

<template>
    <div>
        <div class="min-h-screen relative w-full">
            <!-- NavBar -->
            <NavBar />

            <div class="flex">
                <!-- Sidebar -->
                <SideBar />

                <!-- Page Content -->
                <main class="w-full">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>
<script setup>
    import NavBar from '@/Components/NavBar.vue'
    import SideBar from '@/Components/SideBar.vue'
</script>
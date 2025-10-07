<script setup>
import { getCurrentInstance, onMounted, onUnmounted, ref, watch } from "vue";
import { Head, usePage } from "@inertiajs/inertia-vue3";
import Toggler from "@/Components/DashboardLayout/SidebarToggler.vue";
import TopbarDropdown from "@/Components/DashboardLayout/TopbarDropdown.vue";
import Sidebar from "@/Components/DashboardLayout/Sidebar.vue";

const { title } = defineProps({
    title: String,
});

const { $config } = usePage().props.value;
const open = ref(Boolean(Number(localStorage.getItem("sidebar_open"))));

watch(open, () => {
    localStorage.setItem("sidebar_open", Number(open.value));
});

const q = (e) => {
    if (e.key === "q" && !(e.target instanceof HTMLInputElement)) {
        open.value = !open.value;
    }
};

onMounted(() => document.addEventListener("keyup", q));
onUnmounted(() => document.removeEventListener("keyup", q));
</script>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: all 300ms ease-in-out;
    opacity: 1;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.max-h-sidebar {
    max-height: calc(100vh - 3.5rem);
}
</style>

<template>
    <div
        class="w-full h-full min-h-screen font-sans bg-gray-300 dark:bg-gray-900"
    >
        <div
            class="sticky top-0 left-0 z-20 flex justify-between flex-none w-full h-14"
            :class="
                themes()
                    .get(
                        'topbar',
                        'bg-cyan-500 text-gray-700 hover:bg-cyan-600 hover:text-gray-800 transition-all ease-in-out duration-150'
                    )
                    .replace(/hover:(bg|text)-(.*?)-(\d+)/, '')
            "
        >
            <div
                class="flex items-center justify-between pl-0 w-14 md:w-64 md:pl-6"
            >
                <h1 class="hidden text-2xl font-bold sm:inline">
                    {{ $config.app.name }}
                </h1>

                <Toggler @toggle="open = !open" />
            </div>

            <div class="flex items-center justify-center w-full md:hidden">
                <h1 class="text-2xl font-semibold text-center">
                    {{ $config.app.name }}
                </h1>
            </div>

            <TopbarDropdown />
        </div>

        <div
            class="transition-all duration-300"
            :class="{
                'pl-14 md:pl-64': open,
                'pl-14': !open,
            }"
        >
            <main class="p-6">
                <slot />
            </main>
        </div>

        <div
            class="fixed left-0 z-20 flex flex-col h-screen transition-all duration-300 bg-gray-700 top-14 max-h-sidebar"
            :class="{
                'w-64': open,
                'w-14': !open,
            }"
        >
            <Sidebar :open="open" />
        </div>
    </div>

    <div class="hidden w-full h-full font-sans bg-gray-300 dark:bg-gray-900">
        <Head :title="title" />

        <div
            ref="sidebar"
            class="fixed top-0 left-0 z-20 flex flex-col flex-none h-full min-h-screen transition-all duration-300 ease-in-out sm:static"
            :class="`${themes()
                .get(
                    'sidebar',
                    'bg-gray-700 text-gray-200 hover:bg-gray-800 hover:text-gray-100 transition-all ease-in-out duration-100'
                )
                .replace(/hover:(bg|text)-(.*?)-(\d+)/)} ${
                open ? 'w-full sm:w-60' : 'w-0'
            }`"
        >
            <div
                v-if="open"
                class="flex items-center justify-between flex-none w-full px-2 h-14"
                :class="
                    themes()
                        .get(
                            'topbar',
                            'bg-cyan-500 text-gray-700 hover:bg-cyan-600 hover:text-gray-800 transition-all ease-in-out duration-150'
                        )
                        .replace(/hover:(bg|text)-(.*?)-(\d+)/, '')
                "
            >
                <Toggler @toggle="open = !open" class="sm:hidden" />

                <h1 class="w-full text-2xl font-bold text-center">Template</h1>

                <div class="flex-none sm:hidden">
                    <TopbarDropdown />
                </div>
            </div>

            <Sidebar v-if="open" />
        </div>

        <div
            class="relative w-full h-full max-h-screen min-h-screen overflow-auto"
        >
            <div
                class="sticky top-0 left-0 z-20 flex justify-between flex-none w-full px-2 h-14"
                :class="
                    themes()
                        .get(
                            'topbar',
                            'bg-cyan-500 text-gray-700 hover:bg-cyan-600 hover:text-gray-800 transition-all ease-in-out duration-150'
                        )
                        .replace(/hover:(bg|text)-(.*?)-(\d+)/, '')
                "
            >
                <Toggler @toggle="open = !open" />

                <TopbarDropdown />
            </div>

            <main class="p-6">
                <slot />
            </main>
        </div>
    </div>
</template>

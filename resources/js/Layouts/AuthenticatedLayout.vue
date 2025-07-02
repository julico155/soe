<script setup>
// Importa el composable del tema
import { useTheme } from '@/Composables/useTheme';
const { theme, toggleTheme } = useTheme(); // 'theme' ahora es reactivo y refleja el tema actual

import { ref, computed } from 'vue'; // Importa computed para el texto del botón
// import ApplicationLogo from '@/Components/ApplicationLogo.vue'; // Eliminamos esta importación
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3'; // Importa usePage

const showingNavigationDropdown = ref(false);

// Accede a las props de la página, incluyendo la información del usuario
const page = usePage();

// Propiedad computada para verificar si el usuario es administrativo
const isAdmin = computed(() => page.props.auth.user && page.props.auth.user.tipo === 'administrativo');

// Propiedad computada para verificar si el usuario es docente
// Se mantiene por si se usa en otros lugares, pero no afecta la navegación aquí.
const isDocente = computed(() => page.props.auth.user && page.props.auth.user.tipo === 'docente');

// Formulario para la búsqueda global
const searchForm = useForm({
    query: '',
});

const submitSearch = () => {
    searchForm.get(route('global.search'), {
        preserveState: true,
        preserveScroll: true,
    });
};

// Propiedad computada para el texto del botón de tema (solo Claro/Oscuro)
const nextThemeText = computed(() => {
    if (theme.value === 'light') {
        return 'Oscuro';
    } else { // theme.value === 'dark'
        return 'Claro';
    }
});
</script>

<template>
    <div>
        <!-- Usamos bg-gray-100 y dark:bg-gray-900 para que cambien según el tema. -->
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <nav
                class="border-b border-gray-100 bg-white dark:bg-gray-800 dark:border-gray-700"
            >
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <!-- Logo (anteriormente ApplicationLogo, ahora logo2.png) -->
                            <div class="flex shrink-0 items-center">
                                <Link :href="route('dashboard')">
                                    <img
                                        src="/images/logo2.png"
                                        alt="Logo de la Aplicación"
                                        class="block h-9 w-auto"
                                    />
                                </Link>
                            </div>

                            <!-- Navigation Links (Desktop) -->
                            <div
                                class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                            >
                                <NavLink
                                    :href="route('dashboard')"
                                    :active="route().current('dashboard')"
                                    class="text-gray-800 dark:text-gray-200"
                                    active-class="border-indigo-400 dark:border-indigo-600 text-gray-900 dark:text-gray-100 bg-indigo-50 dark:bg-gray-700"
                                    inactive-class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700"
                                >
                                    Dashboard
                                </NavLink>
                                <!-- Solo para administrativos -->
                                <NavLink v-if="isAdmin" :href="route('programas.index')" :active="route().current('programas.index')"
                                    class="text-gray-800 dark:text-gray-200"
                                    active-class="border-indigo-400 dark:border-indigo-600 text-gray-900 dark:text-gray-100 bg-indigo-50 dark:bg-gray-700"
                                    inactive-class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700"
                                >
                                    Programas
                                </NavLink>
                                <NavLink v-if="isAdmin" :href="route('modulos.index')" :active="route().current('modulos.index')"
                                    class="text-gray-800 dark:text-gray-200"
                                    active-class="border-indigo-400 dark:border-indigo-600 text-gray-900 dark:text-gray-100 bg-indigo-50 dark:bg-gray-700"
                                    inactive-class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700"
                                >
                                    Módulos
                                </NavLink>
                                <NavLink v-if="isAdmin" :href="route('docentes.index')" :active="route().current('docentes.index')"
                                    class="text-gray-800 dark:text-gray-200"
                                    active-class="border-indigo-400 dark:border-indigo-600 text-gray-900 dark:text-gray-100 bg-indigo-50 dark:bg-gray-700"
                                    inactive-class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700"
                                >
                                    Docentes
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <!-- Input de Búsqueda Global (Visible solo para administrativos) -->
                            <form v-if="isAdmin" @submit.prevent="submitSearch" class="flex items-center me-4">
                                <input
                                    type="search"
                                    v-model="searchForm.query"
                                    placeholder="Buscar..."
                                    class="block w-64 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50
                                           dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 transition-colors duration-300"
                                />
                                <button type="submit" class="ms-2 px-3 py-2 rounded-md bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-800 transition-colors duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                            </form>

                            <!-- Botón de cambio de tema (Visible para todos) -->
                            <button
                                @click="toggleTheme"
                                class="px-3 py-2 rounded-md text-sm font-medium transition-colors duration-300
                                       bg-gray-200 text-gray-800
                                       dark:bg-gray-700 dark:text-gray-200
                                       hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-900"
                            >
                                Cambiar a {{ nextThemeText }}
                            </button>

                            <!-- Settings Dropdown (Visible para todos) -->
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none dark:bg-gray-800 dark:text-gray-400 dark:hover:text-gray-300"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="-me-0.5 ms-2 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                            class="dark:text-gray-200 dark:hover:bg-gray-700"
                                        >
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                            class="dark:text-gray-200 dark:hover:bg-gray-700"
                                        >
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger (Responsive Menu Toggle) -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none
                                           dark:text-gray-500 dark:hover:bg-gray-700 dark:hover:text-gray-400 dark:focus:bg-gray-700 dark:focus:text-gray-400"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')"
                            class="text-gray-800 dark:text-gray-200"
                            active-class="border-indigo-400 dark:border-indigo-600 text-gray-700 dark:text-gray-100 bg-indigo-50 dark:bg-gray-700"
                            inactive-class="text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600"
                        >
                            Dashboard
                        </ResponsiveNavLink>
                        <!-- Solo para administrativos en el menú responsive -->
                        <ResponsiveNavLink v-if="isAdmin" :href="route('programas.index')" :active="route().current('programas.index')"
                            class="text-gray-800 dark:text-gray-200"
                            active-class="border-indigo-400 dark:border-indigo-600 text-gray-700 dark:text-gray-100 bg-indigo-50 dark:bg-gray-700"
                            inactive-class="text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600"
                        >
                            Programas
                        </ResponsiveNavLink>
                        <ResponsiveNavLink v-if="isAdmin" :href="route('modulos.index')" :active="route().current('modulos.index')"
                            class="text-gray-800 dark:text-gray-200"
                            active-class="border-indigo-400 dark:border-indigo-600 text-gray-700 dark:text-gray-100 bg-indigo-50 dark:bg-gray-700"
                            inactive-class="text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600"
                        >
                            Módulos
                        </ResponsiveNavLink>
                        <ResponsiveNavLink v-if="isAdmin" :href="route('docentes.index')" :active="route().current('docentes.index')"
                            class="text-gray-800 dark:text-gray-200"
                            active-class="border-indigo-400 dark:border-indigo-600 text-gray-700 dark:text-gray-100 bg-indigo-50 dark:bg-gray-700"
                            inactive-class="text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600"
                        >
                            Docentes
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div
                        class="border-t border-gray-200 dark:border-gray-700 pb-1 pt-4"
                    >
                        <div class="px-4">
                            <div
                                class="text-base font-medium text-gray-800 dark:text-gray-200"
                            >
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <!-- Input de Búsqueda Global (Responsive) -->
                            <form v-if="isAdmin" @submit.prevent="submitSearch" class="px-4 py-2">
                                <input
                                    type="search"
                                    v-model="searchForm.query"
                                    placeholder="Buscar..."
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50
                                           dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 transition-colors duration-300"
                                />
                                <button type="submit" class="mt-2 w-full px-4 py-2 rounded-md bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-800 transition-colors duration-300">
                                    Buscar
                                </button>
                            </form>

                            <ResponsiveNavLink @click="toggleTheme" as="button"
                                class="dark:text-gray-200 dark:hover:bg-gray-700"
                            >
                                Cambiar a {{ nextThemeText }}
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('profile.edit')"
                                class="dark:text-gray-200 dark:hover:bg-gray-700"
                            >
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="dark:text-gray-200 dark:hover:bg-gray-700"
                            >
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header
                class="bg-white shadow dark:bg-gray-800"
                v-if="$slots.header"
            >
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                <slot />
            </main>
        </div>
    </div>
</template>

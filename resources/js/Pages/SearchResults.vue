<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    query: String, // La consulta de búsqueda que se realizó
    results: Object, // Un objeto con los resultados: { users: [], programas: [], modulos: [] }
    pageVisits: Number,
});

// Propiedad computada para verificar si hay algún resultado en total
const hasResults = computed(() => {
    return props.results.users.length > 0 ||
           props.results.programas.length > 0 ||
           props.results.modulos.length > 0;
});
</script>

<template>
    <Head :title="`Resultados de búsqueda para: ${query}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Resultados de búsqueda para: "<span class="font-bold">{{ query }}</span>"
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="mb-4 text-gray-700 p-3 bg-gray-50 rounded-lg shadow-sm">
                        Visitas a esta página: <span class="font-bold text-indigo-600">{{ pageVisits }}</span>
                    </div>
                    <div v-if="hasResults">
                        <!-- Sección de Usuarios -->
                        <div v-if="results.users.length > 0" class="mb-8">
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4 border-b pb-2 border-gray-200 dark:border-gray-700">
                                Usuarios
                            </h3>
                            <ul class="space-y-4">
                                <li v-for="user in results.users" :key="user.id"
                                    class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between transition-colors duration-300">
                                    <div class="mb-2 sm:mb-0">
                                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ user.name }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ user.email }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Tipo: {{ user.tipo }}</p>
                                    </div>
                                    <!-- Nota: La ruta 'profile.edit' por defecto es para el usuario autenticado.
                                         Si quieres ver el perfil de otro usuario, necesitarás una ruta como 'users.show'
                                         y un controlador que la maneje. Aquí se usa 'profile.edit' como placeholder. -->
                                </li>
                            </ul>
                        </div>

                        <!-- Sección de Programas -->
                        <div v-if="results.programas.length > 0" class="mb-8">
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4 border-b pb-2 border-gray-200 dark:border-gray-700">
                                Programas
                            </h3>
                            <ul class="space-y-4">
                                <li v-for="programa in results.programas" :key="programa.id"
                                    class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between transition-colors duration-300">
                                    <div class="mb-2 sm:mb-0">
                                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ programa.titulo }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Sigla: {{ programa.sigla }}</p>
                                    </div>
                                    <Link :href="route('programas.show', programa.id)" class="text-indigo-600 hover:underline dark:text-indigo-400 mt-2 sm:mt-0">Ver Programa</Link>
                                </li>
                            </ul>
                        </div>

                        <!-- Sección de Módulos -->
                        <div v-if="results.modulos.length > 0">
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4 border-b pb-2 border-gray-200 dark:border-gray-700">
                                Módulos
                            </h3>
                            <ul class="space-y-4">
                                <li v-for="modulo in results.modulos" :key="modulo.id"
                                    class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between transition-colors duration-300">
                                    <div class="mb-2 sm:mb-0">
                                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ modulo.nombre }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Sigla: {{ modulo.sigla }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Avance: {{ modulo.porcentaje_avance }}%</p>
                                    </div>
                                    <Link :href="route('modulos.show', modulo.id)" class="text-indigo-600 hover:underline dark:text-indigo-400 mt-2 sm:mt-0">Ver Módulo</Link>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div v-else class="text-gray-600 dark:text-gray-400 text-center py-8">
                        <p>No se encontraron resultados para "{{ query }}".</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

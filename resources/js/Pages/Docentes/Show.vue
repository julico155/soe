<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    docente: Object, // El objeto docente que incluye sus módulos cargados
    pageVisits: Number, // El contador de visitas a esta página
});

// Propiedad computada para determinar si el docente tiene módulos asignados
const hasModules = computed(() => props.docente.modulos && props.docente.modulos.length > 0);
</script>

<template>
    <Head :title="`Detalles de Docente: ${docente.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-app-text leading-tight transition-colors duration-300">
                Detalles de Docente
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Contador de visitas a la página -->
                <div class="mb-4 text-app-text p-3 bg-app-card-bg rounded-lg shadow-sm transition-colors duration-300">
                    Visitas a esta página: <span class="font-bold text-indigo-600">{{ pageVisits }}</span>
                </div>

                <!-- Tarjeta de Detalles del Docente -->
                <div class="bg-app-card-bg overflow-hidden shadow-sm sm:rounded-lg p-6 text-app-text transition-colors duration-300">
                    <h3 class="text-2xl font-bold mb-4 border-b pb-2 border-app-nav-border transition-colors duration-300">
                        {{ docente.name }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-lg">
                        <div>
                            <p><span class="font-semibold">Email:</span> {{ docente.email }}</p>
                            <p><span class="font-semibold">Teléfono:</span> {{ docente.telefono || 'N/A' }}</p>
                        </div>
                        <div>
                            <p><span class="font-semibold">Dirección:</span> {{ docente.direccion || 'N/A' }}</p>
                            <p><span class="font-semibold">Tipo de Usuario:</span> {{ docente.tipo }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <Link :href="route('docentes.edit', docente.id)"
                              class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Editar Docente
                        </Link>
                        <Link :href="route('docentes.index')"
                              class="ms-3 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 dark:focus:bg-gray-600 dark:active:bg-gray-500">
                            Volver a la lista
                        </Link>
                    </div>
                </div>

                <!-- Módulos Asignados al Docente -->
                <div class="mt-8 bg-app-card-bg overflow-hidden shadow-sm sm:rounded-lg p-6 text-app-text transition-colors duration-300">
                    <h3 class="text-xl font-bold mb-4 border-b pb-2 border-app-nav-border transition-colors duration-300">
                        Módulos Asignados
                    </h3>

                    <div v-if="hasModules" class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-app-card-border transition-colors duration-300">
                            <thead class="bg-app-nav-bg transition-colors duration-300">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-app-text uppercase tracking-wider transition-colors duration-300">
                                        Sigla
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-app-text uppercase tracking-wider transition-colors duration-300">
                                        Nombre del Módulo
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-app-text uppercase tracking-wider transition-colors duration-300">
                                        Programa
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-app-text uppercase tracking-wider transition-colors duration-300">
                                        Avance (%)
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-app-card-border transition-colors duration-300">
                                <tr v-for="modulo in docente.modulos" :key="modulo.id" class="hover:bg-app-card-border transition-colors duration-300">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-app-text transition-colors duration-300">{{ modulo.sigla }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-app-text transition-colors duration-300">{{ modulo.nombre }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-app-text transition-colors duration-300">{{ modulo.programa ? modulo.programa.titulo : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-app-text transition-colors duration-300">{{ modulo.porcentaje_avance }}%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else class="text-center text-app-text py-4">
                        <p>Este docente no tiene módulos asignados.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

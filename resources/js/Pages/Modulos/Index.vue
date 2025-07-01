<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    modulos: Array,
});

// Para mensajes flash (success/error)
// MODIFICADO: Añadir el encadenamiento opcional (?) para evitar errores si 'flash' o 'flash.success/error' no existen
const flashSuccess = computed(() => usePage().props.flash?.success);
const flashError = computed(() => usePage().props.flash?.error);
</script>

<template>
    <Head title="Módulos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Módulos</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div v-if="flashSuccess" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            {{ flashSuccess }}
                        </div>
                        <div v-if="flashError" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            {{ flashError }}
                        </div>

                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-2xl font-bold">Listado de Módulos</h3>
                            <Link :href="route('modulos.create')" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Nuevo Módulo
                            </Link>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sigla</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avance (%)</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Programa</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Docente</th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Acciones</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="modulo in modulos" :key="modulo.id">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ modulo.sigla }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ modulo.nombre }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ modulo.porcentaje_avance }}%</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ modulo.programa ? modulo.programa.titulo + ' (' + modulo.programa.sigla + ')' : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ modulo.docente ? modulo.docente.name : 'No Asignado' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <Link :href="route('modulos.show', modulo.id)" class="text-blue-600 hover:text-blue-900 mr-4">Ver</Link>
                                            <Link :href="route('modulos.edit', modulo.id)" class="text-indigo-600 hover:text-indigo-900 mr-4">Editar</Link>
                                            <Link :href="route('modulos.destroy', modulo.id)" method="delete" as="button" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Estás seguro de querer eliminar este módulo?')">Eliminar</Link>
                                        </td>
                                    </tr>
                                    <tr v-if="modulos.length === 0">
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay módulos registrados.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
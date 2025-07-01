<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    programa: Object, // El objeto programa con sus módulos cargados
});
</script>

<template>
    <Head :title="`Programa: ${programa.sigla}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalles del Programa: {{ programa.titulo }} ({{ programa.sigla }})
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="mb-4">
                            <p><strong>Sigla:</strong> {{ programa.sigla }}</p>
                            <p><strong>Tipo:</strong> {{ programa.tipo }}</p>
                            <p><strong>Título Completo:</strong> {{ programa.titulo }}</p>
                        </div>

                        <h3 class="text-xl font-bold mb-4">Módulos del Programa</h3>
                        <div v-if="programa.modulos.length > 0">
                            <ul class="list-disc list-inside">
                                <li v-for="modulo in programa.modulos" :key="modulo.id" class="mb-2">
                                    <Link :href="route('modulos.show', modulo.id)" class="text-blue-600 hover:underline">
                                        {{ modulo.sigla }} - {{ modulo.nombre }}
                                    </Link>
                                </li>
                            </ul>
                        </div>
                        <div v-else class="text-gray-600">
                            No hay módulos asignados a este programa aún.
                        </div>

                        <div class="mt-6 flex justify-end">
                            <Link :href="route('programas.edit', programa.id)" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                                Editar Programa
                            </Link>
                            <Link :href="route('programas.index')" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Volver a Programas
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3'; // Importa Link para la navegación

// Define las props que este componente espera recibir de Laravel
const props = defineProps({
    modulos: {
        type: Array,
        required: true,
    },
});

// Puedes acceder a los módulos así: props.modulos
</script>

<template>
    <Head title="Dashboard Docente" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Mis Módulos (Docente)
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">
                        Módulos Asignados:
                    </h3>

                    <div v-if="props.modulos.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Iterar sobre cada módulo y crear una tarjeta -->
                        <div v-for="modulo in props.modulos" :key="modulo.id"
                             class="bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                            <Link :href="route('modulos.show', modulo.id)" class="block p-6">
                                <h4 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                    {{ modulo.nombre }} ({{ modulo.sigla }})
                                </h4>
                                <p class="text-gray-700 dark:text-gray-300 text-sm mb-4">
                                    Programa: <span class="font-medium">{{ modulo.programa.titulo }}</span>
                                </p>
                                <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5 mb-2">
                                    <div class="bg-indigo-600 h-2.5 rounded-full"
                                         :style="{ width: modulo.porcentaje_avance + '%' }"></div>
                                </div>
                                <p class="text-right text-sm text-gray-600 dark:text-gray-400">
                                    Avance: {{ modulo.porcentaje_avance }}%
                                </p>
                            </Link>
                        </div>
                    </div>
                    <div v-else class="text-gray-600 dark:text-gray-400">
                        <p>No tienes módulos asignados en este momento.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Estilos específicos para este componente si los necesitas,
   aunque la mayoría de los estilos se manejan con Tailwind CSS */
</style>

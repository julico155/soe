<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import RevisionDetailsModal from '@/Components/RevisionDetailsModal.vue';

const props = defineProps({
    revisions: Array, // Array de todas las revisiones
    success: String,
    error: String,
});

// Estado para el modal de detalles de revisión
const showRevisionDetailsModal = ref(false);
const selectedRevision = ref(null);

// Función para calcular el porcentaje de una revisión específica
const getRevisionPercentage = (revision) => {
    let totalScore = 0;
    if (revision.criterio1_1_cumplido) totalScore += 1;
    if (revision.criterio1_2_cumplido) totalScore += 1;
    if (revision.criterio1_3_cumplido) totalScore += 1;
    if (revision.criterio1_4_cumplido) totalScore += 1;
    if (revision.criterio2_1_cumplido) totalScore += 1;
    if (revision.criterio2_2_cumplido) totalScore += 1;
    if (revision.criterio2_3_cumplido) totalScore += 1;
    if (revision.criterio2_4_cumplido) totalScore += 1;
    if (revision.criterio3_1_cumplido) totalScore += 1;
    if (revision.criterio3_2_cumplido) totalScore += 1;
    return ((totalScore / 10) * 100).toFixed(0); // Redondea a 0 decimales
};

const openRevisionDetailsModal = (revision) => {
    selectedRevision.value = revision;
    showRevisionDetailsModal.value = true;
};

const closeRevisionDetailsModal = () => {
    showRevisionDetailsModal.value = false;
    selectedRevision.value = null;
};
</script>

<template>
    <Head title="Todas las Revisiones" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Todas las Revisiones
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div v-if="success" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 dark:bg-green-800 dark:text-green-100" role="alert">
                            {{ success }}
                        </div>
                        <div v-if="error" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 dark:bg-red-800 dark:text-red-100" role="alert">
                            {{ error }}
                        </div>

                        <div v-if="revisions.length > 0">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                ID Revisión
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Módulo
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Programa
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Fecha
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Puntuación
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Acciones
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                        <tr v-for="revision in revisions" :key="revision.id">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ revision.id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ revision.modulo ? revision.modulo.nombre : 'N/A' }} ({{ revision.modulo ? revision.modulo.sigla : 'N/A' }})
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ revision.modulo && revision.modulo.programa ? revision.modulo.programa.titulo : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ new Date(revision.created_at).toLocaleDateString() }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ getRevisionPercentage(revision) }}%
                                                <span v-if="getRevisionPercentage(revision) >= 100" class="text-green-600 font-semibold">(Completo)</span>
                                                <span v-else class="text-yellow-600 font-semibold">(Pendiente)</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <button @click="openRevisionDetailsModal(revision)"
                                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600 mr-3">
                                                    Ver Detalles
                                                </button>
                                                <Link :href="route('revisions.edit', revision.id)"
                                                      class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-600 mr-3">
                                                    Editar
                                                </Link>
                                                <!-- Puedes añadir un botón para eliminar si lo necesitas -->
                                                <!-- <button @click="deleteRevision(revision.id)" class="text-red-600 hover:text-red-900">Eliminar</button> -->
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div v-else class="text-gray-600 dark:text-gray-400">
                            No hay revisiones disponibles.
                        </div>

                        <div class="mt-6 flex justify-end">
                            <Link :href="route('dashboard')" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                                Volver al Dashboard
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Detalles de Revisión (reutilizado) -->
        <RevisionDetailsModal
            :show="showRevisionDetailsModal"
            :revision="selectedRevision"
            @close="closeRevisionDetailsModal"
            v-if="selectedRevision"
        />
    </AuthenticatedLayout>
</template>

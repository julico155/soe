<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import RevisionDetailsModal from '@/Components/RevisionDetailsModal.vue'; // Importa el nuevo componente modal

const props = defineProps({
    modulo: Object,
    ultimaRevision: Object, // Esta prop es la última revisión del módulo (si existe)
    pageVisits: Number,
});

const flashSuccess = computed(() => usePage().props.flash?.success);
const flashError = computed(() => usePage().props.flash?.error);

// Accede a los datos del usuario logueado que vienen de Inertia
const user = usePage().props.auth.user;

// Propiedad computada para verificar si el usuario es administrativo
const isAdministrativo = computed(() => user && user.tipo === 'administrativo');
// Propiedad computada para verificar si el usuario es docente
const isDocente = computed(() => user && user.tipo === 'docente');

const canAddRevision = computed(() => {
    // Solo los administrativos pueden añadir revisiones
    if (!isAdministrativo.value) {
        return false;
    }
    // Si no hay última revisión, se puede añadir
    if (!props.ultimaRevision) {
        return true;
    }
    // Si hay última revisión, se puede añadir si la puntuación total es menor a 10
    // Calculamos la puntuación total de la última revisión
    let totalScoreLastRevision = 0;
    if (props.ultimaRevision) {
        if (props.ultimaRevision.criterio1_1_cumplido) totalScoreLastRevision += 1;
        if (props.ultimaRevision.criterio1_2_cumplido) totalScoreLastRevision += 1;
        if (props.ultimaRevision.criterio1_3_cumplido) totalScoreLastRevision += 1;
        if (props.ultimaRevision.criterio1_4_cumplido) totalScoreLastRevision += 1;
        if (props.ultimaRevision.criterio2_1_cumplido) totalScoreLastRevision += 1;
        if (props.ultimaRevision.criterio2_2_cumplido) totalScoreLastRevision += 1;
        if (props.ultimaRevision.criterio2_3_cumplido) totalScoreLastRevision += 1;
        if (props.ultimaRevision.criterio2_4_cumplido) totalScoreLastRevision += 1;
        if (props.ultimaRevision.criterio3_1_cumplido) totalScoreLastRevision += 1;
        if (props.ultimaRevision.criterio3_2_cumplido) totalScoreLastRevision += 1;
    }
    return totalScoreLastRevision < 10;
});

const isModuloCompleted = computed(() => {
    // Usamos el porcentaje_avance del módulo directamente
    return props.modulo.porcentaje_avance >= 100;
});

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

// --- Lógica para el modal de detalles de revisión ---
const showRevisionDetailsModal = ref(false);
const selectedRevision = ref(null);

const openRevisionDetailsModal = (revision) => {
    selectedRevision.value = revision;
    showRevisionDetailsModal.value = true;
};

const closeRevisionDetailsModal = () => {
    showRevisionDetailsModal.value = false;
    selectedRevision.value = null;
};

// --- Lógica para la justificación de revisión (Docente) ---
const showJustificationModal = ref(false);
const justificationForm = useForm({
    message: '',
});

// Función para abrir el modal de justificación y pre-llenar si existe
const openJustificationModal = () => {
    if (props.ultimaRevision && props.ultimaRevision.justificacion) {
        justificationForm.message = props.ultimaRevision.justificacion;
    } else {
        justificationForm.message = ''; // Limpiar si no hay justificación previa
    }
    showJustificationModal.value = true;
};

const submitJustification = () => {
    if (props.ultimaRevision) {
        justificationForm.post(route('revisiones.justify', props.ultimaRevision.id), {
            onSuccess: () => {
                justificationForm.reset();
                showJustificationModal.value = false;
            },
            onError: (errors) => {
                console.error('Error al enviar justificación:', errors);
            },
        });
    } else {
        console.warn('No hay una última revisión para justificar.');
    }
};
</script>

<template>
    <Head :title="`Módulo: ${modulo.sigla}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Detalles del Módulo: {{ modulo.nombre }} ({{ modulo.sigla }})
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="mb-4 text-gray-700 p-3 bg-gray-50 rounded-lg shadow-sm dark:bg-gray-700 dark:text-gray-300">
                        Visitas : <span class="font-bold text-gray-900 dark:text-gray-100">{{ pageVisits }}</span>
                    </div>
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div v-if="flashSuccess" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 dark:bg-green-800 dark:text-green-100" role="alert">
                            {{ flashSuccess }}
                        </div>
                        <div v-if="flashError" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 dark:bg-red-800 dark:text-red-100" role="alert">
                            {{ flashError }}
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <p class="font-semibold text-gray-700 dark:text-gray-300">Sigla:</p>
                                <p>{{ modulo.sigla }}</p>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-700 dark:text-gray-300">Nombre:</p>
                                <p>{{ modulo.nombre }}</p>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-700 dark:text-gray-300">Porcentaje de Avance Actual:</p>
                                <p>{{ modulo.porcentaje_avance }}% <span v-if="isModuloCompleted" class="text-green-600 font-bold">(Completo al 100%)</span></p>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-700 dark:text-gray-300">Programa:</p>
                                <p v-if="modulo.programa">
                                    <Link :href="route('programas.show', modulo.programa.id)" class="text-blue-600 hover:underline dark:text-blue-400">
                                        {{ modulo.programa.titulo }} ({{ modulo.programa.sigla }})
                                    </Link>
                                </p>
                                <p v-else class="text-gray-500 dark:text-gray-400">N/A</p>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-700 dark:text-gray-300">Docente:</p>
                                <p>{{ modulo.docente ? modulo.docente.name : 'No Asignado' }}</p>
                            </div>
                        </div>

                        <h3 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-200">Revisiones Realizadas</h3>
                        <div v-if="modulo.revisiones && modulo.revisiones.length > 0">
                            <div v-for="revision in modulo.revisiones" :key="revision.id" class="mb-4 p-4 border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-700">
                                <p class="text-gray-800 dark:text-gray-200"><strong>Fecha de Revisión:</strong> {{ new Date(revision.created_at).toLocaleDateString() }}</p>
                                <div class="flex flex-wrap gap-4 mt-2 text-gray-700 dark:text-gray-300">
                                    <p class="font-bold">
                                        <strong>Puntuación:</strong> {{ getRevisionPercentage(revision) }}% completado
                                    </p>
                                    <p v-if="getRevisionPercentage(revision) >= 100" class="text-green-600 font-semibold ml-auto">Estado: Completado</p>
                                    <p v-else class="text-yellow-600 font-semibold ml-auto">Estado: Pendiente</p>
                                </div>

                                <!-- Botones de acción para cada revisión -->
                                <div class="mt-4 flex justify-end gap-2">
                                    <!-- Botón Ver Detalles de Revisión (abre modal) -->
                                    <button @click="openRevisionDetailsModal(revision)"
                                            class="inline-flex items-center px-3 py-1.5 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 dark:bg-blue-600 dark:hover:bg-blue-700">
                                        Ver Detalles
                                    </button>

                                    <!-- Botón Editar Revisión (solo para administrativos) -->
                                    <Link v-if="isAdministrativo" :href="route('revisions.edit', revision.id)"
                                          class="inline-flex items-center px-3 py-1.5 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150 dark:bg-yellow-600 dark:hover:bg-yellow-700">
                                        Editar
                                    </Link>

                                    <!-- Botón Justificar/Editar Justificación - visible solo para docentes en la última revisión -->
                                    <button v-if="isDocente && ultimaRevision && ultimaRevision.id === revision.id"
                                            @click="openJustificationModal()"
                                            class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 dark:bg-blue-700 dark:hover:bg-blue-800">
                                        {{ ultimaRevision.justificacion ? 'Editar Justificación' : 'Justificar' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-gray-600 dark:text-gray-400">
                            No hay revisiones para este módulo.
                        </div>

                        <div class="mt-6 flex justify-end items-center">
                            <!-- Botón "Añadir Revisión" - Visible solo para Administrativos y si se puede añadir revisión -->
                            <Link v-if="isAdministrativo && canAddRevision" :href="route('modulos.revisiones.create', modulo.id)"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2 dark:bg-green-700 dark:hover:bg-green-800">
                                Añadir Revisión
                            </Link>
                            <!-- Mensaje cuando no se puede añadir revisión (para administrativos) -->
                            <span v-else-if="isAdministrativo && !canAddRevision"
                                class="inline-flex items-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed mr-2 dark:bg-gray-600">
                                Módulo Completado (100%)
                            </span>

                            <!-- Botón "Editar Módulo" - Visible solo para Administrativos -->
                            <Link v-if="isAdministrativo" :href="route('modulos.edit', modulo.id)"
                                class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2 dark:bg-yellow-600 dark:hover:bg-yellow-700">
                                Editar Módulo
                            </Link>

                            <!-- Botón "Volver a Módulos" - Visible solo para Administrativos -->
                            <Link v-if="isAdministrativo" :href="route('modulos.index')"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                                Volver a Módulos
                            </Link>

                            <!-- Botón "Volver a Mis Módulos" - Visible solo para Docentes -->
                            <Link v-if="!isAdministrativo" :href="route('dashboard')"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                                Volver a Mis Módulos
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Justificación de Revisión (para docentes) -->
        <div v-if="showJustificationModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md mx-auto">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    {{ ultimaRevision && ultimaRevision.justificacion ? 'Editar Justificación' : 'Justificar Revisión' }}
                </h3>
                <form @submit.prevent="submitJustification">
                    <div class="mb-4">
                        <label for="justificationMessage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mensaje de Justificación:</label>
                        <textarea id="justificationMessage" v-model="justificationForm.message" rows="5"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50
                                         dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:focus:border-indigo-500 dark:focus:ring-indigo-500"
                                  required></textarea>
                        <div v-if="justificationForm.errors.message" class="text-red-600 text-sm mt-1">
                            {{ justificationForm.errors.message }}
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" @click="showJustificationModal = false"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="justificationForm.processing"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 dark:bg-blue-700 dark:hover:bg-blue-800">
                            {{ ultimaRevision && ultimaRevision.justificacion ? 'Actualizar Justificación' : 'Enviar Justificación' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal de Detalles de Revisión (para mostrar el checklist completo) -->
        <RevisionDetailsModal
            :show="showRevisionDetailsModal"
            :revision="selectedRevision"
            @close="closeRevisionDetailsModal"
            v-if="selectedRevision"
        />
    </AuthenticatedLayout>
</template>

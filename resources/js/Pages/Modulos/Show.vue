<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, useForm } from '@inertiajs/vue3'; // Importa useForm
import { computed, ref } from 'vue'; // Importa ref

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
    if (!props.ultimaRevision) {
        return true;
    }
    return props.ultimaRevision.total_score < 10;
});

const isModuloCompleted = computed(() => {
    return props.ultimaRevision && props.ultimaRevision.total_score >= 10;
});

// Función para calcular el porcentaje de una revisión específica
const getRevisionPercentage = (totalScore) => {
    return ((totalScore / 10) * 100).toFixed(0); // Redondea a 0 decimales
};

// --- Lógica para la justificación de revisión ---
const showJustificationModal = ref(false); // Controla la visibilidad del modal
const justificationForm = useForm({
    message: '', // Campo para el mensaje de justificación
});

// Función para enviar la justificación
const submitJustification = () => {
    // Asegúrate de que haya una última revisión para justificar
    if (props.ultimaRevision) {
        justificationForm.post(route('revisiones.justify', props.ultimaRevision.id), {
            onSuccess: () => {
                justificationForm.reset(); // Limpia el formulario
                showJustificationModal.value = false; // Cierra el modal
                // Inertia recargará la página automáticamente si el backend devuelve un Inertia::render o redirect
            },
            onError: (errors) => {
                console.error('Error al enviar justificación:', errors);
                // Aquí podrías mostrar los errores al usuario si es necesario
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
                    <div class="mb-4 text-gray-700 p-3 bg-gray-50 rounded-lg shadow-sm">
                            Visitas : <span class="font-bold text-600">{{ pageVisits }}</span>
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
                                    <p><strong>P:</strong> {{ revision.p_score }} / 4</p>
                                    <p><strong>O:</strong> {{ revision.o_score }} / 4</p>
                                    <p><strong>S1:</strong> {{ revision.s1_score }} / 2</p>
                                    <p class="font-bold">
                                        <strong>Total:</strong> {{ revision.total_score }} / 10
                                        <span class="ml-2 text-gray-600 dark:text-gray-400">({{ getRevisionPercentage(revision.total_score) }}% completado)</span>
                                    </p>
                                    <p v-if="revision.total_score >= 10" class="text-green-600 font-semibold ml-auto">Estado: Completado</p>
                                    <p v-else class="text-yellow-600 font-semibold ml-auto">Estado: Pendiente</p>
                                </div>
                                <!-- SECCIÓN CLAVE: Mostrar justificación si existe -->
                                <div v-if="revision.justificacion" class="mt-3 p-2 bg-blue-50 rounded-md border border-blue-200 dark:bg-blue-900 dark:border-blue-700">
                                    <p class="font-semibold text-blue-800 dark:text-blue-100">Justificación del Docente:</p>
                                    <p class="text-blue-700 whitespace-pre-wrap dark:text-blue-200">{{ revision.justificacion }}</p>
                                </div>
                                <!-- Mostrar observaciones del administrador -->
                                <div v-if="revision.observacion" class="mt-3 p-2 bg-gray-50 rounded-md border border-gray-200 dark:bg-gray-600 dark:border-gray-500">
                                    <p class="font-semibold text-gray-800 dark:text-gray-200">Observaciones del Administrador:</p>
                                    <p class="text-gray-700 whitespace-pre-wrap dark:text-gray-300">{{ revision.observacion }}</p>
                                </div>
                                <div v-else class="mt-3 text-gray-500 text-sm dark:text-gray-400">
                                    No hay observaciones para esta revisión.
                                </div>
                                <!-- Botón Justificar Revisión - visible solo para docentes, para la última revisión y si no tiene justificación -->
                                <div class="mt-4 text-right" v-if="isDocente && props.ultimaRevision && props.ultimaRevision.id === revision.id && !revision.justificacion">
                                    <button @click="showJustificationModal = true"
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 dark:bg-blue-700 dark:hover:bg-blue-800 dark:active:bg-blue-900 dark:focus:border-blue-800 dark:focus:ring-blue-600">
                                        Justificar Revisión
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
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2 dark:bg-green-700 dark:hover:bg-green-800 dark:active:bg-green-900 dark:focus:border-green-800 dark:focus:ring-green-600">
                                Añadir Revisión
                            </Link>
                            <!-- Mensaje cuando no se puede añadir revisión (para administrativos) -->
                            <span v-else-if="isAdministrativo && !canAddRevision"
                                class="inline-flex items-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed mr-2 dark:bg-gray-600">
                                Módulo Completado (100%)
                            </span>

                            <!-- Botón "Editar Módulo" - Visible solo para Administrativos -->
                            <Link v-if="isAdministrativo" :href="route('modulos.edit', modulo.id)"
                                class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:active:bg-yellow-800 dark:focus:border-yellow-800 dark:focus:ring-yellow-500">
                                Editar Módulo
                            </Link>

                            <!-- Botón "Volver a Módulos" - Visible solo para Administrativos -->
                            <Link v-if="isAdministrativo" :href="route('modulos.index')"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 dark:focus:border-gray-600 dark:focus:ring-gray-500">
                                Volver a Módulos
                            </Link>

                            <!-- Botón "Volver a Mis Módulos" - Visible solo para Docentes -->
                            <Link v-if="!isAdministrativo" :href="route('dashboard')"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 dark:focus:border-gray-600 dark:focus:ring-gray-500">
                                Volver a Mis Módulos
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Justificación de Revisión -->
        <div v-if="showJustificationModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md mx-auto">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Justificar Revisión</h3>
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
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 dark:bg-blue-700 dark:hover:bg-blue-800 dark:active:bg-blue-900 dark:focus:border-blue-800 dark:focus:ring-blue-600">
                            Enviar Justificación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

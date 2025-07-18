<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue'; // Importa ref para el carrusel
import ImageCarouselModal from '@/Components/ImageCarouselModal.vue'; // Importa el carrusel

const props = defineProps({
    revision: {
        type: Object,
        required: true,
    },
    totalScore: {
        type: Number,
        default: 0,
    },
});

// Estado para el carrusel de imágenes
const showImageCarousel = ref(false);
const carouselImages = ref([]);
const initialCarouselIndex = ref(0);

const getCheckIcon = (isCumplido) => {
    return isCumplido
        ? '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>'
        : '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>';
};

// Función para abrir el carrusel
const openCarousel = (index) => {
    carouselImages.value = props.revision.images.map(img => ({
        id: img.id,
        src: `data:${img.mime_type};base64,${img.image_base64}`,
        filename: img.filename
    }));
    initialCarouselIndex.value = index;
    showImageCarousel.value = true;
};

const closeCarousel = () => {
    showImageCarousel.value = false;
};
</script>

<template>
    <Head :title="`Detalles de Revisión ${revision.id}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Detalles de Revisión #{{ revision.id }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Información de la Revisión</h3>
                            <p><strong>ID de Revisión:</strong> {{ revision.id }}</p>
                            <p><strong>Módulo:</strong> {{ revision.modulo ? revision.modulo.name : 'N/A' }}</p>
                            <p><strong>Fecha de Creación:</strong> {{ new Date(revision.created_at).toLocaleDateString() }}</p>
                            <p><strong>Última Actualización:</strong> {{ new Date(revision.updated_at).toLocaleDateString() }}</p>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Criterios de Revisión</h3>

                            <!-- Categoría 1: Presentación (4 Puntos) -->
                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Categoría 1: Presentación (4 Puntos)</h4>
                                <ul>
                                    <li class="flex items-center mb-1">
                                        <span v-html="getCheckIcon(revision.criterio1_1_cumplido)"></span>
                                        1.1: Meta
                                    </li>
                                    <li class="flex items-center mb-1">
                                        <span v-html="getCheckIcon(revision.criterio1_2_cumplido)"></span>
                                        1.2: Bienvenida
                                    </li>
                                    <li class="flex items-center mb-1">
                                        <span v-html="getCheckIcon(revision.criterio1_3_cumplido)"></span>
                                        1.3: Diagrama Introductorio
                                    </li>
                                    <li class="flex items-center mb-1">
                                        <span v-html="getCheckIcon(revision.criterio1_4_cumplido)"></span>
                                        1.4: Problema formativo a resolver
                                    </li>
                                    <li class="flex items-center mb-1">
                                        <span v-html="getCheckIcon(revision.criterio1_5_cumplido)"></span>
                                        1.5: Aprendizajes esperados
                                    </li>
                                    <li class="flex items-center mb-1">
                                        <span v-html="getCheckIcon(revision.criterio1_6_cumplido)"></span>
                                        1.6: Competencia
                                    </li>
                                    <li class="flex items-center mb-1">
                                        <span v-html="getCheckIcon(revision.criterio1_7_cumplido)"></span>
                                        1.7: Criterios de evaluación
                                    </li>
                                </ul>
                            </div>

                            <!-- Categoría 2: Organización (4 Puntos) -->
                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Categoría 2: Organización (4 Puntos)</h4>
                                <ul>
                                    <li class="flex items-center mb-1">
                                        <span v-html="getCheckIcon(revision.criterio2_1_cumplido)"></span>
                                        2.1: Direccionamiento
                                    </li>
                                    <li class="flex items-center mb-1">
                                        <span v-html="getCheckIcon(revision.criterio2_2_cumplido)"></span>
                                        2.2: Enlaces
                                    </li>
                                    <li class="flex items-center mb-1">
                                        <span v-html="getCheckIcon(revision.criterio2_3_cumplido)"></span>
                                        2.3: Recursos de Organización
                                    </li>
                                    <li class="flex items-center mb-1">
                                        <span v-html="getCheckIcon(revision.criterio2_4_cumplido)"></span>
                                        2.4: Información del docente
                                    </li>
                                    <li class="flex items-center mb-1">
                                        <span v-html="getCheckIcon(revision.criterio2_5_cumplido)"></span>
                                        2.5: Bibliografía básica y complementaria
                                    </li>
                                    <li class="flex items-center mb-1">
                                        <span v-html="getCheckIcon(revision.criterio2_6_cumplido)"></span>
                                        2.6: Programa académico
                                    </li>
                                    <li class="flex items-center mb-1">
                                        <span v-html="getCheckIcon(revision.criterio2_7_cumplido)"></span>
                                        2.7: Cronograma de avance
                                    </li>
                                </ul>
                            </div>

                            <!-- Categoría 3: Semana 1 (2 Puntos) -->
                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Categoría 3: Semana 1 (2 Puntos)</h4>
                                <ul>
                                    <li class="flex items-center mb-1">
                                        <span v-html="getCheckIcon(revision.criterio3_1_cumplido)"></span>
                                        3.1: Recursos
                                    </li>
                                    <li class="flex items-center mb-1">
                                        <span v-html="getCheckIcon(revision.criterio3_2_cumplido)"></span>
                                        3.2: Actividades
                                    </li>
                                </ul>
                            </div>

                            <p class="mt-4 text-lg font-bold">Nota Total: {{ totalScore }} / 10</p>
                        </div>

                        <!-- Justificación del Docente -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Justificación del Docente</h3>
                            <p class="whitespace-pre-wrap">{{ revision.justificacion || 'No hay justificación del docente.' }}</p>
                        </div>

                        <!-- Observaciones del Supervisor -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Observaciones del Supervisor</h3>
                            <p class="whitespace-pre-wrap">{{ revision.observacion || 'No hay observaciones del supervisor.' }}</p>
                        </div>

                        <!-- Imágenes de Justificación -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Imágenes de Justificación</h3>
                            <div v-if="revision.images && revision.images.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                <div v-for="(image, index) in revision.images" :key="image.id" class="relative">
                                    <img :src="`data:${image.mime_type};base64,${image.image_base64}`"
                                         class="w-full h-32 object-cover rounded-md border border-gray-300 dark:border-gray-700 cursor-pointer hover:opacity-75 transition-opacity"
                                         :alt="image.filename || 'Imagen de justificación'"
                                         @click="openCarousel(index)" />
                                </div>
                            </div>
                            <p v-else class="text-gray-600 dark:text-gray-400">No hay imágenes adjuntas.</p>
                        </div>

                        <div class="flex justify-end mt-6">
                            <Link :href="route('revisions.edit', revision.id)" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Editar Revisión
                            </Link>
                            <Link :href="route('modulos.show', revision.modulo_id)" class="ms-4 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                                Volver al Módulo
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carrusel de Imágenes Modal -->
        <ImageCarouselModal
            :show="showImageCarousel"
            :images="carouselImages"
            :initial-index="initialCarouselIndex"
            @close="closeCarousel"
        />
    </AuthenticatedLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import ImageCarouselModal from '@/Components/ImageCarouselModal.vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    revision: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['close']);

const showImageCarousel = ref(false);
const carouselImages = ref([]);
const initialCarouselIndex = ref(0);

const getCheckIcon = (isCumplido) => {
    return isCumplido
        ? '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>'
        : '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>';
};

const totalScore = computed(() => {
    let score = 0;
    if (props.revision.criterio1_1_cumplido) score += 1;
    if (props.revision.criterio1_2_cumplido) score += 1;
    if (props.revision.criterio1_3_cumplido) score += 1;
    if (props.revision.criterio1_4_cumplido) score += 1;
    if (props.revision.criterio2_1_cumplido) score += 1;
    if (props.revision.criterio2_2_cumplido) score += 1;
    if (props.revision.criterio2_3_cumplido) score += 1;
    if (props.revision.criterio2_4_cumplido) score += 1;
    if (props.revision.criterio3_1_cumplido) score += 1;
    if (props.revision.criterio3_2_cumplido) score += 1;
    return score;
});

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
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-2xl mx-auto overflow-y-auto max-h-[90vh]">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Detalles de Revisión #{{ revision.id }}</h3>

            <div class="mb-6">
                <p><strong>Módulo:</strong> {{ revision.modulo ? revision.modulo.name : 'N/A' }}</p>
                <p><strong>Fecha de Revisión:</strong> {{ new Date(revision.created_at).toLocaleDateString() }}</p>
            </div>

            <div class="mb-6">
                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Criterios de Revisión</h4>

                <!-- Categoría 1: Presentación (4 Puntos) -->
                <div class="mb-4">
                    <h5 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Categoría 1: Presentación (4 Puntos)</h5>
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
                    <h5 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Categoría 2: Organización (4 Puntos)</h5>
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
                    <h5 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Categoría 3: Semana 1 (2 Puntos)</h5>
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

            <div class="flex justify-end">
                <button @click="emit('close')" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                    Cerrar
                </button>
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
</template>

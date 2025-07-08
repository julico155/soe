<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    modulo: Object,
});

const form = useForm({
    modulo_id: '',
    criterio1_1_cumplido: false,
    criterio1_2_cumplido: false,
    criterio1_3_cumplido: false,
    criterio1_4_cumplido: false,
    criterio2_1_cumplido: false,
    criterio2_2_cumplido: false,
    criterio2_3_cumplido: false,
    criterio2_4_cumplido: false,
    criterio3_1_cumplido: false,
    criterio3_2_cumplido: false,
    observacion: '',
    images: [],
});

const totalScore = computed(() => {
    let score = 0;
    if (form.criterio1_1_cumplido) score += 1;
    if (form.criterio1_2_cumplido) score += 1;
    if (form.criterio1_3_cumplido) score += 1;
    if (form.criterio1_4_cumplido) score += 1;
    if (form.criterio2_1_cumplido) score += 1;
    if (form.criterio2_2_cumplido) score += 1;
    if (form.criterio2_3_cumplido) score += 1;
    if (form.criterio2_4_cumplido) score += 1;
    if (form.criterio3_1_cumplido) score += 1;
    if (form.criterio3_2_cumplido) score += 1;
    return score;
});

const canSubmit = computed(() => !form.processing);

const handleImageUpload = (event) => {
    const files = event.target.files;
    form.images = [];
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                form.images.push(e.target.result);
            };
            reader.readAsDataURL(file);
        } else {
            alert('Solo se permiten archivos de imagen.');
        }
    }
};

const removeImage = (index) => {
    form.images.splice(index, 1);
};

const submit = () => {
    form.post(route('modulos.revisiones.store', props.modulo.id), {
        onSuccess: () => {
            form.reset();
            form.images = [];
        },
        onError: (errors) => {
            console.error('Error al guardar la revisión:', errors);
        }
    });
};
</script>

<template>
    <Head :title="`Añadir Revisión a ${modulo.sigla}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Añadir Revisión para el Módulo: {{ modulo.nombre }} ({{ modulo.sigla }})
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <form @submit.prevent="submit">
                            <!-- Campo de selección de Módulo -->
                            <div class="mb-4">
                                <InputLabel for="modulo_id" value="Módulo" />
                                <select id="modulo_id" v-model="form.modulo_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                    <option value="">Seleccione un módulo</option>
                                    <!-- Asegúrate de pasar 'modules' como prop desde el controlador si usas esto -->
                                    <!-- <option v-for="module in modules" :key="module.id" :value="module.id">{{ module.name }}</option> -->
                                </select>
                                <InputError class="mt-2" :message="form.errors.modulo_id" />
                            </div>

                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Criterios de Revisión (Checklist - Total 10 Puntos)</h3>

                            <!-- Categoría 1: Presentación (4 puntos) -->
                            <div class="mb-6 border p-4 rounded-md dark:border-gray-700">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Categoría 1: Presentación (4 Puntos)</h4>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio1_1" v-model:checked="form.criterio1_1_cumplido" />
                                    <InputLabel for="criterio1_1" class="ml-2">1.1: Meta</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio1_1_cumplido" />
                                </div>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio1_2" v-model:checked="form.criterio1_2_cumplido" />
                                    <InputLabel for="criterio1_2" class="ml-2">1.2: Bienvenida</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio1_2_cumplido" />
                                </div>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio1_3" v-model:checked="form.criterio1_3_cumplido" />
                                    <InputLabel for="criterio1_3" class="ml-2">1.3: Diagrama Introductorio</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio1_3_cumplido" />
                                </div>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio1_4" v-model:checked="form.criterio1_4_cumplido" />
                                    <InputLabel for="criterio1_4" class="ml-2">1.4: Problema formativo a resolver</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio1_4_cumplido" />
                                </div>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio1_5" v-model:checked="form.criterio1_5_cumplido" />
                                    <InputLabel for="criterio1_5" class="ml-2">1.5: Aprendizajes esperados</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio1_5_cumplido" />
                                </div>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio1_6" v-model:checked="form.criterio1_6_cumplido" />
                                    <InputLabel for="criterio1_6" class="ml-2">1.6: Competencia</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio1_6_cumplido" />
                                </div>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio1_7" v-model:checked="form.criterio1_7_cumplido" />
                                    <InputLabel for="criterio1_7" class="ml-2">1.7: Criterios de evaluación</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio1_7_cumplido" />
                                </div>
                            </div>

                            <!-- Categoría 2: Organización (4 puntos) -->
                            <div class="mb-6 border p-4 rounded-md dark:border-gray-700">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Categoría 2: Organización (4 Puntos)</h4>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio2_1" v-model:checked="form.criterio2_1_cumplido" />
                                    <InputLabel for="criterio2_1" class="ml-2">2.1: Direccionamiento</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio2_1_cumplido" />
                                </div>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio2_2" v-model:checked="form.criterio2_2_cumplido" />
                                    <InputLabel for="criterio2_2" class="ml-2">2.2: Enlaces</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio2_2_cumplido" />
                                </div>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio2_3" v-model:checked="form.criterio2_3_cumplido" />
                                    <InputLabel for="criterio2_3" class="ml-2">2.3: Recursos de Organización</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio2_3_cumplido" />
                                </div>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio2_4" v-model:checked="form.criterio2_4_cumplido" />
                                    <InputLabel for="criterio2_4" class="ml-2">2.4: Información del docente</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio2_4_cumplido" />
                                </div>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio2_5" v-model:checked="form.criterio2_5_cumplido" />
                                    <InputLabel for="criterio2_5" class="ml-2">2.5: Bibliografía básica y complementaria</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio2_5_cumplido" />
                                </div>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio2_6" v-model:checked="form.criterio2_6_cumplido" />
                                    <InputLabel for="criterio2_6" class="ml-2">2.6: Programa académico</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio2_6_cumplido" />
                                </div>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio2_7" v-model:checked="form.criterio2_7_cumplido" />
                                    <InputLabel for="criterio2_7" class="ml-2">2.7: Cronograma de avance</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio2_7_cumplido" />
                                </div>
                            </div>

                            <!-- Categoría 3: Semana 1 (2 puntos) -->
                            <div class="mb-6 border p-4 rounded-md dark:border-gray-700">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Categoría 3: Semana 1 (2 Puntos)</h4>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio3_1" v-model:checked="form.criterio3_1_cumplido" />
                                    <InputLabel for="criterio3_1" class="ml-2">3.1: Recursos</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio3_1_cumplido" />
                                </div>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio3_2" v-model:checked="form.criterio3_2_cumplido" />
                                    <InputLabel for="criterio3_2" class="ml-2">3.2: Actividades</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio3_2_cumplido" />
                                </div>
                            </div>

                            <!-- Observaciones (Supervisor) -->
                            <div class="mt-6">
                                <InputLabel for="observacion" value="Observaciones del Supervisor (Opcional)" />
                                <textarea
                                    id="observacion"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                    rows="4"
                                    v-model="form.observacion"
                                ></textarea>
                                <InputError class="mt-2" :message="form.errors.observacion" />
                            </div>

                            <!-- Sección de subida de imágenes -->
                            <div class="mt-6">
                                <InputLabel for="images" value="Adjuntar Imágenes (Opcional)" />
                                <input
                                    type="file"
                                    id="images"
                                    multiple
                                    accept="image/*"
                                    @change="handleImageUpload"
                                    class="mt-1 block w-full text-sm text-gray-500
                                           file:mr-4 file:py-2 file:px-4
                                           file:rounded-md file:border-0
                                           file:text-sm file:font-semibold
                                           file:bg-indigo-50 file:text-indigo-700
                                           hover:file:bg-indigo-100 dark:file:bg-indigo-900 dark:file:text-indigo-300 dark:hover:file:bg-indigo-800"
                                />
                                <InputError class="mt-2" :message="form.errors['images']" />
                                <InputError v-if="form.errors['images.0']" class="mt-2" :message="form.errors['images.0']" />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Máximo 5MB por imagen.</p>

                                <div v-if="form.images.length > 0" class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                    <div v-for="(image, index) in form.images" :key="index" class="relative group">
                                        <img :src="image" class="w-full h-32 object-cover rounded-md border border-gray-300 dark:border-gray-700" alt="Imagen de revisión" />
                                        <button @click="removeImage(index)" type="button"
                                                class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 text-xs opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4 mt-6 text-lg font-bold">
                                Puntuación Total: {{ totalScore }} / 10
                            </div>
                            <p v-if="totalScore < 10" class="text-sm text-yellow-600 dark:text-yellow-400 mb-4">
                                Necesitas 10 puntos para completar el módulo al 100%.
                            </p>
                            <p v-else class="text-sm text-green-600 dark:text-green-400 mb-4">
                                ¡Puntuación suficiente para el 100% de avance!
                            </p>

                            <div class="flex items-center justify-end mt-4">
                                <Link :href="route('modulos.show', modulo.id)" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                                    Cancelar
                                </Link>
                                <PrimaryButton :class="{ 'opacity-25': form.processing || !canSubmit }" :disabled="form.processing || !canSubmit">
                                    Guardar Revisión
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

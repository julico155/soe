<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    revision: {
        type: Object,
        required: true,
    },
    success: {
        type: String,
        default: '',
    },
    error: {
        type: String,
        default: '',
    },
});

const form = useForm({
    modulo_id: props.revision.modulo_id,
    criterio1_1_cumplido: props.revision.criterio1_1_cumplido,
    criterio1_2_cumplido: props.revision.criterio1_2_cumplido,
    criterio1_3_cumplido: props.revision.criterio1_3_cumplido,
    criterio1_4_cumplido: props.revision.criterio1_4_cumplido,
    criterio2_1_cumplido: props.revision.criterio2_1_cumplido,
    criterio2_2_cumplido: props.revision.criterio2_2_cumplido,
    criterio2_3_cumplido: props.revision.criterio2_3_cumplido,
    criterio2_4_cumplido: props.revision.criterio2_4_cumplido,
    criterio3_1_cumplido: props.revision.criterio3_1_cumplido,
    criterio3_2_cumplido: props.revision.criterio3_2_cumplido,
    observacion: props.revision.observacion || '',
    new_images: [],
    removed_image_ids: [],
});

const existingImages = ref(props.revision.images.map(img => ({ ...img, src: `data:${img.mime_type};base64,${img.image_base64}` })));

const handleNewImageUpload = (event) => {
    const files = event.target.files;
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                form.new_images.push(e.target.result);
            };
            reader.readAsDataURL(file);
        } else {
            alert('Solo se permiten archivos de imagen.');
        }
    }
};

const removeExistingImage = (indexToRemove) => {
    const imageId = existingImages.value[indexToRemove].id;
    form.removed_image_ids.push(imageId);
    existingImages.value.splice(indexToRemove, 1);
};

const removeNewImage = (index) => {
    form.new_images.splice(index, 1);
};

const submit = () => {
    form.put(route('revisions.update', props.revision.id), {
        onSuccess: () => {
            console.log('Revisión actualizada con éxito.');
        },
        onError: (errors) => {
            console.error('Error al actualizar la revisión:', errors);
        },
    });
};
</script>

<template>
    <Head :title="`Editar Revisión ${revision.id}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Editar Revisión #{{ revision.id }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <!-- Mensajes de éxito o error (flash messages) -->
                        <div v-if="success" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ success }}
                        </div>
                        <div v-if="error" class="mb-4 font-medium text-sm text-red-600 dark:text-red-400">
                            {{ error }}
                        </div>

                        <form @submit.prevent="submit">
                            <!-- Campo de Módulo (se muestra como texto, no editable) -->
                            <div class="mb-4">
                                <InputLabel value="Módulo" />
                                <p class="mt-1 text-gray-700 dark:text-gray-300">
                                    {{ revision.modulo ? `${revision.modulo.nombre} (${revision.modulo.sigla})` : 'Módulo no disponible' }}
                                </p>
                            </div>

                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Criterios de Revisión (Checklist - Total 10 Puntos)</h3>

                            <!-- Categoría 1: Presentación (4 puntos) -->
                            <div class="mb-6 border p-4 rounded-md dark:border-gray-700">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Categoría 1: Presentación (4 Puntos)</h4>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio1_1_edit" v-model:checked="form.criterio1_1_cumplido" />
                                    <InputLabel for="criterio1_1_edit" class="ml-2">1.1: Meta</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio1_1_cumplido" />
                                </div>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio1_2_edit" v-model:checked="form.criterio1_2_cumplido" />
                                    <InputLabel for="criterio1_2_edit" class="ml-2">1.2: Bienvenida</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio1_2_cumplido" />
                                </div>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio1_3_edit" v-model:checked="form.criterio1_3_cumplido" />
                                    <InputLabel for="criterio1_3_edit" class="ml-2">1.3: Diagrama Introductorio</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio1_3_cumplido" />
                                </div>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio1_4_edit" v-model:checked="form.criterio1_4_cumplido" />
                                    <InputLabel for="criterio1_4_edit" class="ml-2">1.4: Problema formativo a resolver</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio1_4_cumplido" />
                                </div>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio1_5_edit" v-model:checked="form.criterio1_5_cumplido" />
                                    <InputLabel for="criterio1_5_edit" class="ml-2">1.5: Aprendizajes esperados</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio1_5_cumplido" />
                                </div>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio1_6_edit" v-model:checked="form.criterio1_6_cumplido" />
                                    <InputLabel for="criterio1_6_edit" class="ml-2">1.6: Competencia</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio1_6_cumplido" />
                                </div>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio1_7_edit" v-model:checked="form.criterio1_7_cumplido" />
                                    <InputLabel for="criterio1_7_edit" class="ml-2">1.7: Criterios de evaluación</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio1_7_cumplido" />
                                </div>
                            </div>

                            <!-- Categoría 2: Organización (4 puntos) -->
                            <div class="mb-6 border p-4 rounded-md dark:border-gray-700">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Categoría 2: Organización (4 Puntos)</h4>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio2_1_edit" v-model:checked="form.criterio2_1_cumplido" />
                                    <InputLabel for="criterio2_1_edit" class="ml-2">2.1: Direccionamiento</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio2_1_cumplido" />
                                </div>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio2_2_edit" v-model:checked="form.criterio2_2_cumplido" />
                                    <InputLabel for="criterio2_2_edit" class="ml-2">2.2: Enlaces</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio2_2_cumplido" />
                                </div>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio2_3_edit" v-model:checked="form.criterio2_3_cumplido" />
                                    <InputLabel for="criterio2_3_edit" class="ml-2">2.3: Recursos de Organización</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio2_3_cumplido" />
                                </div>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio2_4_edit" v-model:checked="form.criterio2_4_cumplido" />
                                    <InputLabel for="criterio2_4_edit" class="ml-2">2.4: Información del docente</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio2_4_cumplido" />
                                </div>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio2_5_edit" v-model:checked="form.criterio2_5_cumplido" />
                                    <InputLabel for="criterio2_5_edit" class="ml-2">2.5: Bibliografía básica y complementaria</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio2_5_cumplido" />
                                </div>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio2_6_edit" v-model:checked="form.criterio2_6_cumplido" />
                                    <InputLabel for="criterio2_6_edit" class="ml-2">2.6: Programa académico</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio2_6_cumplido" />
                                </div>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio2_7_edit" v-model:checked="form.criterio2_7_cumplido" />
                                    <InputLabel for="criterio2_7_edit" class="ml-2">2.7: Cronograma de avance</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio2_7_cumplido" />
                                </div>
                            </div>

                            <!-- Categoría 3: Semana 1 (2 puntos) -->
                            <div class="mb-6 border p-4 rounded-md dark:border-gray-700">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Categoría 3: Semana 1 (2 Puntos)</h4>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio3_1_edit" v-model:checked="form.criterio3_1_cumplido" />
                                    <InputLabel for="criterio3_1_edit" class="ml-2">3.1: Recursos</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio3_1_cumplido" />
                                </div>
                                <div class="mb-3 flex items-center">
                                    <Checkbox id="criterio3_2_edit" v-model:checked="form.criterio3_2_cumplido" />
                                    <InputLabel for="criterio3_2_edit" class="ml-2">3.2: Actividades</InputLabel>
                                    <InputError class="ml-2" :message="form.errors.criterio3_2_cumplido" />
                                </div>
                            </div>

                            <!-- Observaciones -->
                            <div class="mt-6">
                                <InputLabel for="observacion_edit" value="Observaciones del Supervisor" />
                                <textarea
                                    id="observacion_edit"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                    v-model="form.observacion"
                                    rows="3"
                                ></textarea>
                                <InputError class="mt-2" :message="form.errors.observacion" />
                            </div>

                            <!-- Sección de imágenes existentes -->
                            <div v-if="existingImages.length > 0" class="mt-6">
                                <InputLabel value="Imágenes Existentes" />
                                <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                    <div v-for="(image, index) in existingImages" :key="image.id" class="relative group">
                                        <img :src="image.src" class="w-full h-32 object-cover rounded-md border border-gray-300 dark:border-gray-700" :alt="image.filename || 'Imagen existente'" />
                                        <button @click="removeExistingImage(index)" type="button"
                                                class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 text-xs opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección de subida de nuevas imágenes -->
                            <div class="mt-6">
                                <InputLabel for="new_images" value="Adjuntar Nuevas Imágenes (Opcional)" />
                                <input
                                    type="file"
                                    id="new_images"
                                    multiple
                                    accept="image/*"
                                    @change="handleNewImageUpload"
                                    class="mt-1 block w-full text-sm text-gray-500
                                           file:mr-4 file:py-2 file:px-4
                                           file:rounded-md file:border-0
                                           file:text-sm file:font-semibold
                                           file:bg-indigo-50 file:text-indigo-700
                                           hover:file:bg-indigo-100 dark:file:bg-indigo-900 dark:file:text-indigo-300 dark:hover:file:bg-indigo-800"
                                />
                                <InputError class="mt-2" :message="form.errors['new_images']" />
                                <InputError v-if="form.errors['new_images.0']" class="mt-2" :message="form.errors['new_images.0']" />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Máximo 5MB por imagen.</p>

                                <div v-if="form.new_images.length > 0" class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                    <div v-for="(image, index) in form.new_images" :key="index" class="relative group">
                                        <img :src="image" class="w-full h-32 object-cover rounded-md border border-gray-300 dark:border-gray-700" alt="Nueva imagen" />
                                        <button @click="removeNewImage(index)" type="button"
                                                class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 text-xs opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <Link :href="route('modulos.show', revision.modulo_id)" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                    Cancelar
                                </Link>

                                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Actualizar Revisión
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

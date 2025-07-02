<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    modulo: Object,    // El objeto módulo a editar
    programas: Array,  // Lista de programas para el select
    docentes: Array,   // Lista de docentes para el select
    pageVisits: Number,
});

const form = useForm({
    sigla: props.modulo.sigla,
    nombre: props.modulo.nombre,
    porcentaje_avance: props.modulo.porcentaje_avance,
    programa_id: props.modulo.programa_id,
    docente_id: props.modulo.docente_id,
});

const submit = () => {
    form.put(route('modulos.update', props.modulo.id)); // Usar .put para la actualización
};
</script>

<template>
    <Head :title="`Editar Módulo: ${modulo.sigla}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Módulo</h2>
        </template>

        <div class="py-12">
            <div class="max-w-md mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="mb-4 text-gray-700 p-3 bg-gray-50 rounded-lg shadow-sm">
                            Visitas : <span class="font-bold text-600">{{ pageVisits }}</span>
                    </div>
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <InputLabel for="sigla" value="Sigla" />
                                <TextInput
                                    id="sigla"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.sigla"
                                    required
                                    autofocus
                                />
                                <InputError class="mt-2" :message="form.errors.sigla" />
                            </div>

                            <div class="mb-4">
                                <InputLabel for="nombre" value="Nombre" />
                                <TextInput
                                    id="nombre"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.nombre"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.nombre" />
                            </div>

                            <div class="mb-4">
                                <InputLabel for="porcentaje_avance" value="Porcentaje de Avance (%)" />
                                <TextInput
                                    id="porcentaje_avance"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.porcentaje_avance"
                                    min="0"
                                    max="100"
                                />
                                <InputError class="mt-2" :message="form.errors.porcentaje_avance" />
                            </div>

                            <div class="mb-4">
                                <InputLabel for="programa_id" value="Programa" />
                                <select
                                    id="programa_id"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    v-model="form.programa_id"
                                    required
                                >
                                    <option value="">Selecciona un programa</option>
                                    <option v-for="programa in programas" :key="programa.id" :value="programa.id">
                                        {{ programa.titulo }} ({{ programa.sigla }})
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.programa_id" />
                            </div>

                            <div class="mb-4">
                                <InputLabel for="docente_id" value="Docente" />
                                <select
                                    id="docente_id"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    v-model="form.docente_id"
                                >
                                    <option value="">Ninguno</option>
                                    <option v-for="docente in docentes" :key="docente.id" :value="docente.id">
                                        {{ docente.name }}
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.docente_id" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Actualizar Módulo
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
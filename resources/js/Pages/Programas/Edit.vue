<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    programa: Object, // El objeto programa que se va a editar
});

const form = useForm({
    sigla: props.programa.sigla,
    tipo: props.programa.tipo,
    titulo: props.programa.titulo,
});

const submit = () => {
    form.put(route('programas.update', props.programa.id)); // Usar .put para la actualización
};
</script>

<template>
    <Head :title="`Editar Programa: ${programa.sigla}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Programa</h2>
        </template>

        <div class="py-12">
            <div class="max-w-md mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
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
                                <InputLabel for="tipo" value="Tipo" />
                                <select
                                    id="tipo"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    v-model="form.tipo"
                                    required
                                >
                                    <option value="">Selecciona un tipo</option>
                                    <option value="maestria">Maestría</option>
                                    <option value="diplomado">Diplomado</option>
                                    <option value="doctorado">Doctorado</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.tipo" />
                            </div>

                            <div class="mb-4">
                                <InputLabel for="titulo" value="Título" />
                                <TextInput
                                    id="titulo"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.titulo"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.titulo" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Actualizar Programa
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
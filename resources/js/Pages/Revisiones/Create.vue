<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
// import TextAreaInput from '@/Components/TextAreaInput.vue'; // Si tienes un componente TextAreaInput
import { Head, useForm, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    modulo: Object,
});

const form = useForm({
    p_score: 0,
    o_score: 0,
    s1_score: 0,
    observacion: '', // <-- ¡Añade este campo!
});

const totalScore = computed(() => form.p_score + form.o_score + form.s1_score);
const canSubmit = computed(() =>
    form.p_score >= 0 && form.p_score <= 4 &&
    form.o_score >= 0 && form.o_score <= 4 &&
    form.s1_score >= 0 && form.s1_score <= 2
);

const submit = () => {
    form.post(route('modulos.revisiones.store', props.modulo.id), {
        onSuccess: () => {
            // Opcional: form.reset();
        }
    });
};
</script>

<template>
    <Head :title="`Añadir Revisión a ${modulo.sigla}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Añadir Revisión para el Módulo: {{ modulo.nombre }} ({{ modulo.sigla }})
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-md mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <InputLabel for="p_score" value="Puntuación P (0-4)" />
                                <TextInput
                                    id="p_score"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.p_score"
                                    required
                                    min="0"
                                    max="4"
                                />
                                <InputError class="mt-2" :message="form.errors.p_score" />
                            </div>

                            <div class="mb-4">
                                <InputLabel for="o_score" value="Puntuación O (0-4)" />
                                <TextInput
                                    id="o_score"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.o_score"
                                    required
                                    min="0"
                                    max="4"
                                />
                                <InputError class="mt-2" :message="form.errors.o_score" />
                            </div>

                            <div class="mb-4">
                                <InputLabel for="s1_score" value="Puntuación S1 (0-2)" />
                                <TextInput
                                    id="s1_score"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.s1_score"
                                    required
                                    min="0"
                                    max="2"
                                />
                                <InputError class="mt-2" :message="form.errors.s1_score" />
                            </div>

                            <div class="mb-4">
                                <InputLabel for="observacion" value="Observaciones (Opcional)" />
                                <textarea
                                    id="observacion"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                                    rows="4"
                                    v-model="form.observacion"
                                ></textarea>
                                <InputError class="mt-2" :message="form.errors.observacion" />
                            </div>

                            <div class="mb-4 text-lg font-bold">
                                Puntuación Total: {{ totalScore }} / 10
                            </div>
                            <p v-if="totalScore < 10" class="text-sm text-yellow-600 mb-4">
                                Necesitas 10 puntos para completar el módulo al 100%.
                            </p>
                            <p v-else class="text-sm text-green-600 mb-4">
                                ¡Puntuación suficiente para el 100% de avance!
                            </p>

                            <div class="flex items-center justify-end mt-4">
                                <Link :href="route('modulos.show', modulo.id)" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
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
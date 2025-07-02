<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    telefono: '',   // <-- Añadir
    direccion: '',  // <-- Añadir
    tipo: 'docente', // <-- Añadir, con valor predeterminado 'docente'
});

const props = defineProps({
    // ... otras props
    pageVisits: Number, // Asegúrate de que esta prop esté definida
});

const submit = () => {
    form.post(route('docentes.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Crear Docente" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Crear Nuevo Docente</h2>
        </template>

        <div class="py-12">
            <div class="max-w-md mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="mb-4 text-gray-700 p-3 bg-gray-50 rounded-lg shadow-sm">
                        Visitas a esta página: <span class="font-bold text-indigo-600">{{ pageVisits }}</span>
                    </div>
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <InputLabel for="name" value="Nombre Completo" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                    autofocus
                                    autocomplete="name"
                                />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div class="mb-4">
                                <InputLabel for="email" value="Correo Electrónico" />
                                <TextInput
                                    id="email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="form.email"
                                    required
                                    autocomplete="username"
                                />
                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>

                            <div class="mb-4">
                                <InputLabel for="telefono" value="Teléfono" />
                                <TextInput
                                    id="telefono"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.telefono"
                                    autocomplete="tel"
                                />
                                <InputError class="mt-2" :message="form.errors.telefono" />
                            </div>

                            <div class="mb-4">
                                <InputLabel for="direccion" value="Dirección" />
                                <TextInput
                                    id="direccion"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.direccion"
                                    autocomplete="street-address"
                                />
                                <InputError class="mt-2" :message="form.errors.direccion" />
                            </div>

                            <div class="mb-4 hidden">
                                <InputLabel for="tipo" value="Tipo de Usuario" />
                                <select
                                    id="tipo"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    v-model="form.tipo"
                                    required
                                >
                                    <option value="docente">Docente</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.tipo" />
                            </div>

                            <div class="mb-4">
                                <InputLabel for="password" value="Contraseña" />
                                <TextInput
                                    id="password"
                                    type="password"
                                    class="mt-1 block w-full"
                                    v-model="form.password"
                                    required
                                    autocomplete="new-password"
                                />
                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>

                            <div class="mb-4">
                                <InputLabel for="password_confirmation" value="Confirmar Contraseña" />
                                <TextInput
                                    id="password_confirmation"
                                    type="password"
                                    class="mt-1 block w-full"
                                    v-model="form.password_confirmation"
                                    required
                                    autocomplete="new-password"
                                />
                                <InputError class="mt-2" :message="form.errors.password_confirmation" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Registrar Docente
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
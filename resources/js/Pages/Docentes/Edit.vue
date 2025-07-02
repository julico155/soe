<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

// Define las props que este componente recibirá de Laravel
const props = defineProps({
    docente: {
        type: Object,
        required: true,
    },
    pageVisits: {
        type: Number,
        default: 0,
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

// Inicializa el formulario con los datos del docente actual
const form = useForm({
    name: props.docente.name,
    email: props.docente.email,
    telefono: props.docente.telefono,
    direccion: props.docente.direccion,
    password: '', // Campo de contraseña vacío para no mostrar la contraseña actual
    password_confirmation: '', // Campo para confirmar la nueva contraseña
    tipo: props.docente.tipo, // Asegúrate de enviar el tipo de usuario
});

// Función para enviar el formulario de actualización
const submit = () => {
    // Usamos form.put para enviar una solicitud PUT al endpoint de actualización
    // Inertia.js automáticamente mapeará esto a tu método update en el controlador
    form.put(route('docentes.update', props.docente.id), {
        onSuccess: () => {
            // Limpia los campos de contraseña después de un éxito
            form.reset('password', 'password_confirmation');
            // La sesión 'success' se manejará automáticamente por Inertia y se mostrará
            // en el layout si lo tienes configurado para mensajes flash.
        },
        onError: () => {
            // Manejo de errores, los errores de validación se mostrarán automáticamente
            // debajo de cada campo gracias a InputError.vue
            form.reset('password', 'password_confirmation'); // Limpia la contraseña en caso de error
        },
    });
};
</script>

<template>
    <Head :title="`Editar Docente: ${docente.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Editar Docente: {{ docente.name }}
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

                        <!-- Contador de visitas de la página -->
                        <div class="mb-6 text-right text-sm text-gray-500 dark:text-gray-400">
                            Visitas a esta página: {{ pageVisits }}
                        </div>

                        <form @submit.prevent="submit">
                            <!-- Campo Nombre -->
                            <div>
                                <InputLabel for="name" value="Nombre" />
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

                            <!-- Campo Email -->
                            <div class="mt-4">
                                <InputLabel for="email" value="Email" />
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

                            <!-- Campo Teléfono -->
                            <div class="mt-4">
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

                            <!-- Campo Dirección -->
                            <div class="mt-4">
                                <InputLabel for="direccion" value="Dirección" />
                                <TextInput
                                    id="direccion"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.direccion"
                                    autocomplete="address-level1"
                                />
                                <InputError class="mt-2" :message="form.errors.direccion" />
                            </div>

                            <!-- Campo Contraseña (opcional para actualizar) -->
                            <div class="mt-4">
                                <InputLabel for="password" value="Nueva Contraseña (dejar en blanco para no cambiar)" />
                                <TextInput
                                    id="password"
                                    type="password"
                                    class="mt-1 block w-full"
                                    v-model="form.password"
                                    autocomplete="new-password"
                                />
                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>

                            <!-- Campo Confirmar Contraseña -->
                            <div class="mt-4">
                                <InputLabel for="password_confirmation" value="Confirmar Nueva Contraseña" />
                                <TextInput
                                    id="password_confirmation"
                                    type="password"
                                    class="mt-1 block w-full"
                                    v-model="form.password_confirmation"
                                    autocomplete="new-password"
                                />
                                <InputError class="mt-2" :message="form.errors.password_confirmation" />
                            </div>

                            <!-- Campo Tipo de Usuario (oculto o deshabilitado si siempre es 'docente') -->
                            <!-- Si quieres que el tipo sea editable, puedes hacerlo un select.
                                 Por ahora, lo mantengo como un input oculto para asegurar que el tipo 'docente' se envía. -->
                            <input type="hidden" v-model="form.tipo" />


                            <div class="flex items-center justify-end mt-4">
                                <Link :href="route('docentes.index')" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                    Cancelar
                                </Link>

                                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Actualizar Docente
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue'; // Importamos computed para la lista de stats

const props = defineProps({
    stats: Object,
});

// Definimos una propiedad computada para organizar los datos de las tarjetas.
// Esto facilita el uso de v-for en el template.
const dashboardStats = computed(() => [
    { title: "Total Programas", value: props.stats.totalProgramas, unit: '' },
    { title: "Total Módulos", value: props.stats.totalModulos, unit: '' },
    { title: "Total Docentes", value: props.stats.totalDocentes, unit: '' },
    { title: "Módulos Completados (100%)", value: props.stats.modulosCompletados, unit: '' },
    { title: "Módulos Pendientes (<100%)", value: props.stats.modulosPendientes, unit: '' },
    { title: "Avance Promedio de Módulos", value: props.stats.porcentajeAvancePromedio, unit: '%' },
]);
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="(stat, index) in dashboardStats" :key="index"
                         class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 flex flex-col items-center justify-center text-center">
                        <h3 class="text-lg font-semibold text-gray-700">{{ stat.title }}</h3>
                        <p class="text-4xl font-bold text-indigo-600 mt-2">{{ stat.value }}{{ stat.unit }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 mt-8">
                    <h3 class="text-xl font-bold mb-4">¡Bienvenido a tu panel de gestión!</h3>
                    <p>Aquí podrás ver un resumen rápido del estado de tus programas y módulos.</p>
                    <p class="mt-2 text-sm text-gray-600">
                        Navega a las secciones de Programas, Módulos o Docentes para una gestión más detallada.
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
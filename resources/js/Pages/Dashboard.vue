<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

// Importa los componentes de Chart.js y vue-chartjs
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js';
import { Pie } from 'vue-chartjs';

// Registra los componentes necesarios de Chart.js
ChartJS.register(ArcElement, Tooltip, Legend);

const props = defineProps({
    stats: Object,
    docenteStats: Array, // Nueva prop para las estadísticas de docentes
    pageVisits: Number,
});

// Propiedad computada para organizar los datos de las tarjetas de estadísticas
const dashboardStats = computed(() => [
    { title: "Total Programas", value: props.stats.totalProgramas, unit: '' },
    { title: "Total Módulos", value: props.stats.totalModulos, unit: '' },
    { title: "Total Docentes", value: props.stats.totalDocentes, unit: '' },
    { title: "Módulos Completados (100%)", value: props.stats.modulosCompletados, unit: '' },
    { title: "Módulos Pendientes (<100%)", value: props.stats.modulosPendientes, unit: '' },
    { title: "Avance Promedio de Módulos", value: props.stats.porcentajeAvancePromedio, unit: '%' },
]);

// Datos para el Gráfico de Torta de Avance de Módulos
const pieChartData = computed(() => {
    return {
        labels: ['Módulos Completados', 'Módulos Pendientes'],
        datasets: [
            {
                backgroundColor: ['#4CAF50', '#FFC107'], // Verde para completados, Ámbar para pendientes
                data: [props.stats.modulosCompletados, props.stats.modulosPendientes],
            },
        ],
    };
});

// Opciones para el Gráfico de Torta
const pieChartOptions = computed(() => {
    return {
        responsive: true,
        maintainAspectRatio: false, // Permite que el gráfico no mantenga su relación de aspecto original
        plugins: {
            legend: {
                labels: {
                    color: 'var(--color-app-text)', // Usa la variable CSS para el color del texto de la leyenda
                },
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.label || '';
                        if (label) {
                            label += ': ';
                        }
                        if (context.parsed !== null) {
                            label += context.parsed;
                        }
                        return label;
                    }
                }
            }
        },
    };
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-app-text leading-tight transition-colors duration-300">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Estadísticas en tarjetas -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="(stat, index) in dashboardStats" :key="index"
                         class="bg-app-card-bg overflow-hidden shadow-sm sm:rounded-lg p-6 text-app-text flex flex-col items-center justify-center text-center transition-colors duration-300">
                        <h3 class="text-lg font-semibold text-app-text transition-colors duration-300">{{ stat.title }}</h3>
                        <p class="text-4xl font-bold text-indigo-600 mt-2">{{ stat.value }}{{ stat.unit }}</p>
                    </div>
                </div>

                <!-- Gráfico de Torta de Avance de Módulos -->
                <div class="mt-8 bg-app-card-bg overflow-hidden shadow-sm sm:rounded-lg p-6 transition-colors duration-300">
                    <h3 class="text-xl font-bold text-app-text mb-4 transition-colors duration-300">Estado de Completación de Módulos</h3>
                    <div class="relative h-64 w-full"> <!-- Contenedor con altura fija para el gráfico -->
                        <Pie :data="pieChartData" :options="pieChartOptions" />
                    </div>
                </div>

                <!-- Tabla de Docentes con Mejores Porcentajes -->
                <div class="mt-8 bg-app-card-bg overflow-hidden shadow-sm sm:rounded-lg p-6 transition-colors duration-300">
                    <h3 class="text-xl font-bold text-app-text mb-4 transition-colors duration-300">Docentes por Porcentaje de Avance de Módulos</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-app-card-border transition-colors duration-300">
                            <thead class="bg-app-nav-bg transition-colors duration-300">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-app-text uppercase tracking-wider transition-colors duration-300">
                                        Docente
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-app-text uppercase tracking-wider transition-colors duration-300">
                                        Email
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-app-text uppercase tracking-wider transition-colors duration-300">
                                        Módulos Asignados
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-app-text uppercase tracking-wider transition-colors duration-300">
                                        Avance Promedio
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-app-card-border transition-colors duration-300">
                                <tr v-for="docente in docenteStats" :key="docente.id" class="hover:bg-app-card-border transition-colors duration-300">
                                    <td class="px-6 py-4 whitespace-nowrap text-app-text transition-colors duration-300">{{ docente.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-app-text transition-colors duration-300">{{ docente.email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-app-text transition-colors duration-300">{{ docente.modules_count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-app-text transition-colors duration-300">{{ docente.average_completion }}%</td>
                                </tr>
                                <tr v-if="docenteStats.length === 0">
                                    <td colspan="4" class="px-6 py-4 text-center text-app-text transition-colors duration-300">No hay docentes para mostrar.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-app-card-bg overflow-hidden shadow-sm sm:rounded-lg p-6 text-app-text mt-8 transition-colors duration-300">
                    <h3 class="text-xl font-bold mb-4 transition-colors duration-300">¡Bienvenido a tu panel de gestión!</h3>
                    <p>Aquí podrás ver un resumen rápido del estado de tus programas y módulos.</p>
                    <p class="mt-2 text-sm text-app-text transition-colors duration-300">
                        Navega a las secciones de Programas, Módulos o Docentes para una gestión más detallada.
                    </p>
                    <div class="mb-4 text-gray-700 p-3 bg-gray-50 rounded-lg shadow-sm">
                        Visitas a esta página: <span class="font-bold text-indigo-600">{{ pageVisits }}</span>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

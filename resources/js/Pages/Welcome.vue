<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

// La función handleImageError ya no es estrictamente necesaria si no usas el background.svg
// pero la mantengo por si decides reintroducir elementos que la necesiten.
function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Head title="Bienvenido al SOE" />

    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <!-- Puedes mantener o eliminar el background.svg si no lo necesitas -->
        <!-- Si lo eliminas, también puedes quitar la función handleImageError -->
        <img
            id="background"
            class="absolute -left-20 top-0 max-w-[877px] opacity-20 dark:opacity-10"
            src="https://laravel.com/assets/img/welcome/background.svg"
            alt="Background"
            @error="handleImageError"
        />

        <div
            class="relative flex min-h-screen flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white"
        >
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <!-- Header de la aplicación -->
                <header
                    class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3"
                >
                    <div class="flex lg:col-start-2 lg:justify-center">
                        <!-- Tu logo del postgrado en el header -->
                        <img
                            src="/images/logo_postgrado.png"
                            alt="Logo Postgrado"
                            class="h-16 w-auto"
                        />
                    </div>
                    <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                        >
                            Dashboard
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Iniciar Sesión
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Registrarse
                            </Link>
                        </template>
                    </nav>
                </header>

                <!-- Contenido principal de la página de bienvenida -->
                <main class="mt-6">
                    <!-- Sección de Bienvenida al SOE -->
                    <div
                        id="soe-welcome-section"
                        class="flex flex-col items-center text-center p-6 mb-12 bg-white rounded-lg shadow-lg dark:bg-zinc-900"
                    >
                        <!-- Tu logo del postgrado en un tamaño más grande para la sección principal -->

                        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-4">
                            Postgrado FICCT
                        </h1>
                        <p class="mt-4 text-lg text-gray-700 dark:text-gray-300 max-w-3xl">
                            La Unidad de Posgrado “School of Engineering” de la Facultad de Ingeniería en Ciencias de la Computación y Telecomunicaciones de la Universidad Autónoma Gabriel René Moreno tiene como propósito brindar servicios de formación académica a nivel de postgrado en el ámbito de la Informática y Telecomunicaciones.
                        </p>
                    </div>

                    <!-- Sección de Programas con Mayor Empleabilidad -->
                    <div class="mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white text-center mb-4">
                            PROGRAMAS CON MAYOR EMPLEABILIDAD
                        </h2>
                        <p class="text-lg text-gray-700 dark:text-gray-300 text-center mb-8 max-w-3xl mx-auto">
                            Ofrecemos los programas con mayor requerimiento en el mercado laboral los cuales te permitirán actualizarte y reforzar tus conocimientos.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                            <!-- Tarjeta 1: Doctorado en Ciencias de la Computación -->
                            <div class="flex flex-col items-start gap-4 rounded-lg bg-white p-6 shadow-md ring-1 ring-white/[0.05] dark:bg-zinc-900 dark:ring-zinc-800">
                                <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600 sm:size-16">
                                    <!-- Icono para Doctorado: Computadora y Código -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1l-.75-3M3 13V7a2 2 0 012-2h14a2 2 0 012 2v6m-4 0h.01M7 13h.01M12 13h.01M16 13h.01M21 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6m18 0H3" />
                                    </svg>
                                </div>
                                <div class="pt-3 sm:pt-5">
                                    <h3 class="text-xl font-semibold text-black dark:text-white mb-2">
                                        DOCTORADO EN CIENCIAS DE LA COMPUTACIÓN
                                    </h3>
                                    <p class="text-sm/relaxed text-gray-700 dark:text-gray-300">
                                        Forma parte del avance científico con investigaciones innovadoras en ciencias computacionales para resolver problemas críticos y generar impacto positivo en la sociedad.
                                    </p>
                                </div>
                            </div>

                            <!-- Tarjeta 2: Maestría en Ciencia de Datos e Inteligencia Artificial -->
                            <div class="flex flex-col items-start gap-4 rounded-lg bg-white p-6 shadow-md ring-1 ring-white/[0.05] dark:bg-zinc-900 dark:ring-zinc-800">
                                <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-purple-100 text-purple-600 sm:size-16">
                                    <!-- Icono para Ciencia de Datos/IA: Chip/Procesador -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 10v2m14-2v2M1.75 10.75h20.5a.75.75 0 00.75-.75V7.5a2 2 0 00-2-2H3a2 2 0 00-2 2v2.5a.75.75 0 00.75.75zM1.75 13.25h20.5a.75.75 0 01.75.75v2.5a2 2 0 01-2 2H3a2 2 0 01-2-2v-2.5a.75.75 0 01.75-.75z" />
                                    </svg>
                                </div>
                                <div class="pt-3 sm:pt-5">
                                    <h3 class="text-xl font-semibold text-black dark:text-white mb-2">
                                        MAESTRÍA EN CIENCIA DE DATOS E INTELIGENCIA ARTIFICIAL
                                    </h3>
                                    <p class="text-sm/relaxed text-gray-700 dark:text-gray-300">
                                        Domina herramientas de vanguardia para transformar grandes volúmenes de datos en soluciones inteligentes y estratégicas.
                                    </p>
                                </div>
                            </div>

                            <!-- Tarjeta 3: Maestría en Ciberseguridad y Ciberdefensa -->
                            <div class="flex flex-col items-start gap-4 rounded-lg bg-white p-6 shadow-md ring-1 ring-white/[0.05] dark:bg-zinc-900 dark:ring-zinc-800">
                                <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600 sm:size-16">
                                    <!-- Icono para Ciberseguridad: Candado -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v3h8z" />
                                    </svg>
                                </div>
                                <div class="pt-3 sm:pt-5">
                                    <h3 class="text-xl font-semibold text-black dark:text-white mb-2">
                                        MAESTRÍA EN CIBERSEGURIDAD Y CIBERDEFENSA
                                    </h3>
                                    <p class="text-sm/relaxed text-gray-700 dark:text-gray-300">
                                        El presente y el futuro dependen del correcto manejo de los datos y su protección.
                                    </p>
                                </div>
                            </div>

                            <!-- Tarjeta 4: Maestría en Dir. Estratégica en Ing. de Software -->
                            <div class="flex flex-col items-start gap-4 rounded-lg bg-white p-6 shadow-md ring-1 ring-white/[0.05] dark:bg-zinc-900 dark:ring-zinc-800">
                                <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-600 sm:size-16">
                                    <!-- Icono para Ing. de Software: Engranaje/Herramientas -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div class="pt-3 sm:pt-5">
                                    <h3 class="text-xl font-semibold text-black dark:text-white mb-2">
                                        MAESTRÍA EN DIR. ESTRATÉGICA EN ING. DE SOFTWARE
                                    </h3>
                                    <p class="text-sm/relaxed text-gray-700 dark:text-gray-300">
                                        Las nuevas empresas tecnológicas requieren de profesionales capaces de dirigir equipos productivos.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>

                <!-- Footer de la aplicación -->
                <footer
                    class="py-16 text-center text-sm text-black dark:text-white/70"
                >
                    Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
                </footer>
            </div>
        </div>
    </div>
</template>

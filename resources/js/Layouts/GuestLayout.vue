<script setup>
import { Link } from '@inertiajs/vue3';
// import ApplicationLogo from '@/Components/ApplicationLogo.vue'; // Ya no es necesario
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div>
            <Link href="/">
                <!-- Reemplazamos ApplicationLogo por tu imagen logo2.png -->
                <img
                    src="/images/logo2.png"
                    alt="Logo de la Aplicación"
                    class="w-20 h-20 object-contain"
                />
            </Link>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>

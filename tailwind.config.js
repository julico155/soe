import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {

    darkMode: 'class', 
    
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Colores base que se usarán en los componentes
                // Por defecto (light mode)
                'app-bg': 'var(--color-app-bg-light)',
                'app-text': 'var(--color-app-text-light)',
                'app-nav-bg': 'var(--color-app-nav-bg-light)',
                'app-nav-border': 'var(--color-app-nav-border-light)',
                'app-header-bg': 'var(--color-app-header-bg-light)',
                'app-card-bg': 'var(--color-app-card-bg-light)',
                'app-card-border': 'var(--color-app-card-border-light)',
                'app-link': 'var(--color-app-link-light)',
                'app-link-hover': 'var(--color-app-link-hover-light)',
                'app-button-bg-secondary': 'var(--color-app-button-bg-secondary-light)',
                'app-button-text-secondary': 'var(--color-app-button-text-secondary-light)',
                'app-input-bg': 'var(--color-app-input-bg-light)',
                'app-input-border': 'var(--color-app-input-border-light)',
                'app-flash-success-bg': 'var(--color-app-flash-success-bg-light)',
                'app-flash-success-text': 'var(--color-app-flash-success-text-light)',
                'app-flash-error-bg': 'var(--color-app-flash-error-bg-light)',
                'app-flash-error-text': 'var(--color-app-flash-error-text-light)',


                // Colores para el modo oscuro (usando el prefijo 'dark:')
                'dark-app-bg': 'var(--color-app-bg-dark)',
                'dark-app-text': 'var(--color-app-text-dark)',
                'dark-app-nav-bg': 'var(--color-app-nav-bg-dark)',
                'dark-app-nav-border': 'var(--color-app-nav-border-dark)',
                'dark-app-header-bg': 'var(--color-app-header-bg-dark)',
                'dark-app-card-bg': 'var(--color-app-card-bg-dark)',
                'dark-app-card-border': 'var(--color-app-card-border-dark)',
                'dark-app-link': 'var(--color-app-link-dark)',
                'dark-app-link-hover': 'var(--color-app-link-hover-dark)',
                'dark-app-button-bg-secondary': 'var(--color-app-button-bg-secondary-dark)',
                'dark-app-button-text-secondary': 'var(--color-app-button-text-secondary-dark)',
                'dark-app-input-bg': 'var(--color-app-input-bg-dark)',
                'dark-app-input-border': 'var(--color-app-input-border-dark)',
                'dark-app-flash-success-bg': 'var(--color-app-flash-success-bg-dark)',
                'dark-app-flash-success-text': 'var(--color-app-flash-success-text-dark)',
                'dark-app-flash-error-bg': 'var(--color-app-flash-error-bg-dark)',
                'dark-app-flash-error-text': 'var(--color-app-flash-error-text-dark)',

                // Colores para el modo niño (usando el prefijo 'child-mode:')
                'child-app-bg': 'var(--color-app-bg-child)',
                'child-app-text': 'var(--color-app-text-child)',
                'child-app-nav-bg': 'var(--color-app-nav-bg-child)',
                'child-app-nav-border': 'var(--color-app-nav-border-child)',
                'child-app-header-bg': 'var(--color-app-header-bg-child)',
                'child-app-card-bg': 'var(--color-app-card-bg-child)',
                'child-app-card-border': 'var(--color-app-card-border-child)',
                'child-app-link': 'var(--color-app-link-child)',
                'child-app-link-hover': 'var(--color-app-link-hover-child)',
                'child-app-button-bg-secondary': 'var(--color-app-button-bg-secondary-child)',
                'child-app-button-text-secondary': 'var(--color-app-button-text-secondary-child)',
                'child-app-input-bg': 'var(--color-app-input-bg-child)',
                'child-app-input-border': 'var(--color-app-input-border-child)',
                'child-app-flash-success-bg': 'var(--color-app-flash-success-bg-child)',
                'child-app-flash-success-text': 'var(--color-app-flash-success-text-child)',
                'child-app-flash-error-bg': 'var(--color-app-flash-error-bg-child)',
                'child-app-flash-error-text': 'var(--color-app-flash-error-text-child)',
            },
        },
    },

    plugins: [forms],
};

// resources/js/Composables/useTheme.js

// Importa las funciones reactivas de Vue 3 que vamos a necesitar
import { ref, onMounted, watch } from 'vue';

// Define y exporta la función composable
export function useTheme() {
    // 1. Estado Reactivo del Tema:
    // 'ref' hace que 'theme' sea una variable reactiva.
    // Esto significa que si 'theme.value' cambia, cualquier parte de tu UI que lo use se actualizará.
    // Inicialmente, lo configuramos a 'light' como valor por defecto.
    const theme = ref('light');

    // 2. Función para Alternar el Tema:
    // Simplemente cambia el valor de 'theme' entre 'light' y 'dark'.
    const toggleTheme = () => {
        theme.value = theme.value === 'light' ? 'dark' : 'light';
    };

    // 3. Lógica que se ejecuta cuando el componente que usa este composable se monta:
    // Esto es crucial para cargar la preferencia guardada o la del sistema operativo al inicio.
    onMounted(() => {
        // Intenta obtener el tema guardado de LocalStorage
        const savedTheme = localStorage.getItem('theme');

        if (savedTheme) {
            // Si hay un tema guardado, lo usamos
            theme.value = savedTheme;
        } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            // Si no hay nada guardado, verifica si el sistema operativo prefiere el tema oscuro
            theme.value = 'dark';
        }

        // Aplica el tema que se ha determinado (ya sea guardado, del sistema o el por defecto 'light')
        applyTheme(theme.value);
    });

    // 4. Observar Cambios en el Tema:
    // 'watch' reacciona a los cambios en 'theme.value'.
    // Cada vez que 'theme.value' cambie (por ejemplo, al llamar a 'toggleTheme'), esta función se ejecuta.
    watch(theme, (newTheme) => {
        applyTheme(newTheme); // Aplica la clase CSS al body
        localStorage.setItem('theme', newTheme); // Guarda la nueva preferencia en LocalStorage
    });

    // 5. Función Auxiliar para Aplicar la Clase CSS:
    // Esta función maneja la adición/remoción de la clase 'dark' en el elemento <body>.
    // Es lo que visualmente cambia tu tema.
    const applyTheme = (currentTheme) => {
        if (currentTheme === 'dark') {
            document.body.classList.add('dark'); // Agrega la clase 'dark'
        } else {
            document.body.classList.remove('dark'); // Remueve la clase 'dark'
        }
    };

    // 6. Retornar las Variables y Funciones que otros componentes pueden usar:
    // Lo que retornamos aquí será accesible para cualquier componente que use 'useTheme()'.
    return {
        theme,        // El estado actual del tema (reactivo)
        toggleTheme   // La función para cambiar el tema
    };
}
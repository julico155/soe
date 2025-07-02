// resources/js/Composables/useTheme.js
import { ref, onMounted, watch } from 'vue';

export function useTheme() {
    // Los temas disponibles
    const themes = ['light', 'dark', 'child'];

    // Función interna para aplicar/remover las clases de tema al body
    // ¡MOVEMOS ESTA FUNCIÓN AL PRINCIPIO PARA QUE ESTÉ DEFINIDA ANTES DE SER USADA!
    const applyTheme = (currentTheme) => {
        // Remueve todas las clases de tema antes de añadir la nueva
        document.body.classList.remove('light', 'dark', 'child-mode');

        if (currentTheme === 'dark') {
            document.body.classList.add('dark');
        } else if (currentTheme === 'child') {
            document.body.classList.add('child-mode'); // Nueva clase para el modo niño
        }
        // Si es 'light', no se añade ninguna clase específica (es el estado base)
        console.log(`Tema aplicado: ${currentTheme}`); // Para depuración
    };

    const theme = ref('light'); // Estado reactivo del tema, inicia en 'light'

    // Función para cambiar el tema
    const toggleTheme = () => {
        const currentIndex = themes.indexOf(theme.value);
        const nextIndex = (currentIndex + 1) % themes.length; // Ciclo a través de los temas
        theme.value = themes[nextIndex];
    };

    // Al montar el componente, intenta cargar el tema de LocalStorage
    onMounted(() => {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme && themes.includes(savedTheme)) { // Asegúrate de que el tema guardado sea válido
            theme.value = savedTheme;
        } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            // Si no hay preferencia guardada y el sistema prefiere oscuro, usa 'dark'
            theme.value = 'dark';
        }
        // Si no hay preferencia guardada ni del sistema, se queda en 'light' por defecto
        applyTheme(theme.value); // Aplica el tema inicial
    });

    // Observa los cambios en 'theme.value' y aplica el tema y lo guarda
    watch(theme, (newTheme) => {
        applyTheme(newTheme);
        localStorage.setItem('theme', newTheme);
    }, { immediate: true }); // Agrega { immediate: true } para que el watcher se ejecute al inicio


    return {
        theme,
        toggleTheme
    };
}

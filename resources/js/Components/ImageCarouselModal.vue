<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    images: {
        type: Array,
        default: () => [],
    },
    initialIndex: {
        type: Number,
        default: 0,
    },
});

const emit = defineEmits(['close']);

const currentIndex = ref(props.initialIndex);

// Observar cambios en initialIndex para actualizar currentIndex cuando el modal se abre con una nueva imagen
watch(() => props.initialIndex, (newIndex) => {
    currentIndex.value = newIndex;
});

// Observar cambios en 'show' para resetear el índice si el modal se cierra y se vuelve a abrir
watch(() => props.show, (newVal) => {
    if (newVal) {
        currentIndex.value = props.initialIndex;
    }
});

const nextImage = () => {
    currentIndex.value = (currentIndex.value + 1) % props.images.length;
};

const prevImage = () => {
    currentIndex.value = (currentIndex.value - 1 + props.images.length) % props.images.length;
};

// Cerrar el modal al presionar ESC
const handleKeydown = (event) => {
    if (event.key === 'Escape') {
        emit('close');
    } else if (event.key === 'ArrowRight') {
        nextImage();
    } else if (event.key === 'ArrowLeft') {
        prevImage();
    }
};

// Añadir y remover event listeners para las teclas
watch(() => props.show, (newVal) => {
    if (newVal) {
        window.addEventListener('keydown', handleKeydown);
    } else {
        window.removeEventListener('keydown', handleKeydown);
    }
}, { immediate: true }); // immediate para que se configure al montar si show es true
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-80">
        <div class="relative w-full h-full max-w-4xl max-h-4xl flex items-center justify-center">
            <!-- Botón de cerrar -->
            <button @click="emit('close')"
                    class="absolute top-4 right-4 text-white text-4xl p-2 rounded-full bg-gray-700 bg-opacity-50 hover:bg-opacity-75 z-20">
                &times;
            </button>

            <!-- Imagen actual -->
            <div v-if="images.length > 0" class="relative w-full h-full flex items-center justify-center">
                <img :src="images[currentIndex].src"
                     :alt="images[currentIndex].filename || 'Imagen de revisión'"
                     class="max-w-full max-h-full object-contain rounded-lg shadow-lg"
                />

                <!-- Controles de navegación -->
                <button v-if="images.length > 1" @click="prevImage"
                        class="absolute left-4 p-3 rounded-full bg-gray-700 bg-opacity-50 text-white text-2xl hover:bg-opacity-75 transition-colors">
                    &#10094;
                </button>
                <button v-if="images.length > 1" @click="nextImage"
                        class="absolute right-4 p-3 rounded-full bg-gray-700 bg-opacity-50 text-white text-2xl hover:bg-opacity-75 transition-colors">
                    &#10095;
                </button>

                <!-- Indicador de imagen actual -->
                <div v-if="images.length > 1" class="absolute bottom-4 text-white text-lg bg-gray-700 bg-opacity-50 px-3 py-1 rounded-full">
                    {{ currentIndex + 1 }} / {{ images.length }}
                </div>
            </div>
            <div v-else class="text-white text-xl">
                No hay imágenes para mostrar.
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Puedes añadir estilos específicos si es necesario, pero Tailwind debería ser suficiente */
</style>

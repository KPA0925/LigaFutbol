<script setup lang="ts">
import { ref, watch } from 'vue';
import { Button } from '@/components/ui/button';

const props = defineProps<{
    show: boolean;
    title?: string;
    message?: string;
}>();

const emit = defineEmits(['confirm', 'cancel']);

const isOpen = ref(props.show);

watch(
    () => props.show,
    (v) => (isOpen.value = v)
);

function close() {
    emit('cancel');
}
function confirm() {
    emit('confirm');
}
</script>

<template>
    <div
        v-if="isOpen"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
    >
        <!-- Tarjeta -->
        <div
            class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl animate-fadeIn"
        >
            <h2 class="text-xl font-semibold text-gray-800">
                {{ title ?? 'Confirmar eliminación' }}
            </h2>

            <p class="mt-2 text-gray-600">
                {{ message ?? '¿Estás seguro de eliminar este registro?' }}
            </p>

            <div class="mt-6 flex justify-end gap-3">
                <Button
                    variant="outline"
                    class="border-gray-400 text-gray-600"
                    @click="close"
                >
                    Cancelar
                </Button>

                <Button class="bg-[#D62027] text-white" @click="confirm">
                    Eliminar
                </Button>
            </div>
        </div>
    </div>
</template>

<style>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-fadeIn {
    animation: fadeIn 0.18s ease-out;
}
</style>

<script setup lang="ts">
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { ref } from 'vue';

import { Button } from '@/components/ui/button';

// Props
const props = defineProps({
    module: {
        type: String,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
});

const open = ref(false);

// Abre el modal desde el padre
defineExpose({ open });

// Exportar Excel
function exportExcel() {
    const query = new URLSearchParams({
        module: props.module,
        ...props.filters,
    }).toString();

    window.location.href = `/export?${query}`;
}
</script>

<template>
    <Dialog v-model="open">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>Exportar Datos</DialogTitle>
                <DialogDescription>
                    Selecciona el formato para exportar la información filtrada.
                </DialogDescription>
            </DialogHeader>

            <div class="mt-4 flex flex-col gap-3">
                <Button @click="exportExcel" class="w-full">
                    📄 Exportar a Excel
                </Button>
            </div>

            <DialogFooter>
                <Button variant="secondary" @click="open = false"
                    >Cerrar</Button
                >
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>



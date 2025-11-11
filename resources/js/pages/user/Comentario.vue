<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Comentarios', href: dashboard().url },
];

const comentarios = ref([
    { usuario: 'Juan Pérez', texto: 'Gran partido hoy 🔥', fecha: '2025-11-10' },
    { usuario: 'Luis Gómez', texto: 'Increíble jugada del 10 ⚽', fecha: '2025-11-09' },
]);

const nuevoComentario = ref('');

function agregarComentario() {
    if (nuevoComentario.value.trim() !== '') {
        comentarios.value.unshift({
            usuario: 'Tú',
            texto: nuevoComentario.value,
            fecha: new Date().toISOString().split('T')[0],
        });
        nuevoComentario.value = '';
    }
}
</script>

<template>
    <Head title="Comentarios" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 bg-white rounded-2xl shadow-md dark:bg-gray-900">
            <h1 class="text-2xl font-bold text-[#003366] mb-4">💬 Comentarios de la Comunidad</h1>

            <div class="mb-6">
                <textarea
                    v-model="nuevoComentario"
                    class="w-full rounded-lg border-gray-300 p-3 text-gray-800 dark:bg-gray-800 dark:text-white"
                    rows="3"
                    placeholder="Escribe un comentario..."
                ></textarea>
                <button
                    @click="agregarComentario"
                    class="mt-2 rounded-lg bg-[#D62027] px-4 py-2 text-white hover:bg-[#b81e23] transition"
                >
                    Publicar
                </button>
            </div>

            <div class="space-y-4">
                <div
                    v-for="(c, i) in comentarios"
                    :key="i"
                    class="rounded-lg border p-4 shadow-sm bg-gray-50 dark:bg-gray-800"
                >
                    <p class="font-semibold text-[#003366] dark:text-white">{{ c.usuario }}</p>
                    <p class="text-gray-700 dark:text-gray-300">{{ c.texto }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ c.fecha }}</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Estadísticas', href: dashboard().url },
];

const stats = [
    { nombre: 'Goles Totales', valor: 120 },
    { nombre: 'Tarjetas Amarillas', valor: 45 },
    { nombre: 'Tarjetas Rojas', valor: 10 },
];
</script>

<template>
    <Head title="Estadísticas" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 bg-white rounded-2xl shadow-md dark:bg-gray-900">
            <h1 class="text-2xl font-bold text-[#003366] mb-6">📊 Estadísticas Generales</h1>
            <div class="grid gap-6 md:grid-cols-3">
                <div
                    v-for="s in stats"
                    :key="s.nombre"
                    class="flex flex-col items-center justify-center rounded-xl bg-[#003366] text-white p-6 shadow-md"
                >
                    <span class="text-4xl font-bold">{{ s.valor }}</span>
                    <span class="text-sm uppercase tracking-wider mt-2">{{ s.nombre }}</span>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

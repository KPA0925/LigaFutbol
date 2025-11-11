<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Partidos',
        href: dashboard().url,
    },
];

const partidos = [
    {
        id: 1,
        equipoA: 'Tigres FC',
        equipoB: 'Leones Rojos',
        estado: 'Jugando',
        marcador: '2 - 1',
    },
    {
        id: 2,
        equipoA: 'Águilas Doradas',
        equipoB: 'Pumas',
        estado: 'Finalizado',
        marcador: '3 - 0',
    },
    {
        id: 3,
        equipoA: 'Huracanes',
        equipoB: 'Toros FC',
        estado: 'En espera',
        marcador: '-',
    },
];
</script>

<template>
    <Head title="Partidos" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="rounded-2xl bg-white p-6 shadow-md dark:bg-gray-900">
            <h1 class="mb-4 text-2xl font-bold text-[#003366]">
                📅 Partidos de la Liga
            </h1>
            <div class="space-y-4">
                <div
                    v-for="partido in partidos"
                    :key="partido.id"
                    class="flex items-center justify-between rounded-lg border-l-4 p-4 shadow-sm"
                    :class="{
                        'border-green-500 bg-green-50 dark:bg-green-900/30':
                            partido.estado === 'Finalizado',
                        'border-yellow-500 bg-yellow-50 dark:bg-yellow-900/30':
                            partido.estado === 'En espera',
                        'border-blue-500 bg-blue-50 dark:bg-blue-900/30':
                            partido.estado === 'Jugando',
                    }"
                >
                    <span
                        class="font-semibold text-gray-800 dark:text-gray-100"
                    >
                        {{ partido.equipoA }} vs {{ partido.equipoB }}
                    </span>
                    <span
                        class="text-sm font-medium text-gray-500 dark:text-gray-300"
                    >
                        {{ partido.estado }} — <b>{{ partido.marcador }}</b>
                    </span>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

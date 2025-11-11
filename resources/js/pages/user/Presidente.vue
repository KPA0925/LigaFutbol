<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Presidentes', href: dashboard().url },
];

const presidentes = [
    { nombre: 'Julián Soto', equipo: 'Tigres FC', años: 5 },
    { nombre: 'Oscar Márquez', equipo: 'Leones Rojos', años: 3 },
    { nombre: 'María López', equipo: 'Huracanes', años: 4 },
];
</script>

<template>
    <Head title="Presidentes" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 bg-white rounded-2xl shadow-md dark:bg-gray-900">
            <h1 class="text-2xl font-bold text-[#003366] mb-4">🧑‍💼 Presidentes de Clubes</h1>
            <div class="grid gap-4 md:grid-cols-3">
                <div
                    v-for="p in presidentes"
                    :key="p.nombre"
                    class="rounded-lg border p-4 shadow hover:shadow-lg transition"
                >
                    <h2 class="font-bold text-lg text-[#D62027]">{{ p.nombre }}</h2>
                    <p class="text-gray-600 dark:text-gray-300">Equipo: {{ p.equipo }}</p>
                    <p class="text-gray-600 dark:text-gray-300">Años en el cargo: {{ p.años }}</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

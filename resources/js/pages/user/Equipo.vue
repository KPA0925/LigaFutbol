<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Equipos', href: dashboard().url },
];

const equipos = [
    { nombre: 'Tigres FC', ciudad: 'Medellín', presidente: 'Julián Soto' },
    { nombre: 'Leones Rojos', ciudad: 'Cali', presidente: 'Oscar Márquez' },
    { nombre: 'Huracanes', ciudad: 'Bogotá', presidente: 'María López' },
];
</script>

<template>
    <Head title="Equipos" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 bg-white rounded-2xl shadow-md dark:bg-gray-900">
            <h1 class="text-2xl font-bold text-[#003366] mb-4">🏟️ Equipos de la Liga</h1>
            <table class="min-w-full border rounded-lg overflow-hidden">
                <thead class="bg-[#003366] text-white">
                    <tr>
                        <th class="p-3 text-left">Nombre</th>
                        <th class="p-3 text-left">Ciudad</th>
                        <th class="p-3 text-left">Presidente</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="equipo in equipos"
                        :key="equipo.nombre"
                        class="border-b hover:bg-gray-50 dark:hover:bg-gray-800"
                    >
                        <td class="p-3">{{ equipo.nombre }}</td>
                        <td class="p-3">{{ equipo.ciudad }}</td>
                        <td class="p-3">{{ equipo.presidente }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>

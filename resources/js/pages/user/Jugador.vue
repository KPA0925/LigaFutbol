<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Jugadores', href: dashboard().url },
];

const jugadores = [
    {
        nombre: 'Carlos Ruiz',
        equipo: 'Tigres FC',
        goles: 12,
        nacionalidad: 'Colombia',
    },
    {
        nombre: 'Luis Gómez',
        equipo: 'Leones Rojos',
        goles: 8,
        nacionalidad: 'Ecuador',
    },
    {
        nombre: 'Andrés López',
        equipo: 'Huracanes',
        goles: 15,
        nacionalidad: 'Chile',
    },
];
</script>

<template>
    <Head title="Jugadores" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="rounded-2xl bg-white p-6 shadow-md dark:bg-gray-900">
            <h1 class="mb-4 text-2xl font-bold text-[#003366]">
                ⚽ Jugadores Destacados
            </h1>
            <div class="grid gap-4 md:grid-cols-3">
                <div
                    v-for="jugador in jugadores"
                    :key="jugador.nombre"
                    class="rounded-lg border p-4 shadow transition hover:shadow-lg"
                >
                    <h2 class="text-lg font-bold text-[#D62027]">
                        {{ jugador.nombre }}
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300">
                        Equipo: {{ jugador.equipo }}
                    </p>
                    <p class="text-gray-600 dark:text-gray-300">
                        Goles: {{ jugador.goles }}
                    </p>
                    <p class="text-gray-600 dark:text-gray-300">
                        Nacionalidad: {{ jugador.nacionalidad }}
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

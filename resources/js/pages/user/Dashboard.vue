<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    totalTeams: Number,
    totalPlayers: Number,
    totalMatches: Number,
    totalGoals: Number,
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
            <div class="rounded-xl bg-white p-6 shadow dark:bg-gray-900">
                <h3
                    class="text-lg font-semibold text-gray-600 dark:text-gray-300"
                >
                    Equipos
                </h3>
                <p class="text-3xl font-bold text-[#003366]">
                    {{ props.totalTeams }}
                </p>
            </div>

            <div class="rounded-xl bg-white p-6 shadow dark:bg-gray-900">
                <h3
                    class="text-lg font-semibold text-gray-600 dark:text-gray-300"
                >
                    Jugadores
                </h3>
                <p class="text-3xl font-bold text-[#003366]">
                    {{ props.totalPlayers }}
                </p>
            </div>

            <div class="rounded-xl bg-white p-6 shadow dark:bg-gray-900">
                <h3
                    class="text-lg font-semibold text-gray-600 dark:text-gray-300"
                >
                    Partidos
                </h3>
                <p class="text-3xl font-bold text-[#003366]">
                    {{ props.totalMatches }}
                </p>
            </div>

            <div class="rounded-xl bg-white p-6 shadow dark:bg-gray-900">
                <h3
                    class="text-lg font-semibold text-gray-600 dark:text-gray-300"
                >
                    Goles
                </h3>
                <p class="text-3xl font-bold text-[#003366]">
                    {{ props.totalGoals }}
                </p>
            </div>
        </div>
    </AppLayout>
</template>

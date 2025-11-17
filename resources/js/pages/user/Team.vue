<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';

interface Team {
    id: number;
    name: string;
    city: string;
    stadium: string;
    capacity: number;
}

const props = defineProps<{
    teams: Team[];
}>();

const breadcrumbs = [{ title: 'Equipos', href: dashboard().url }];
</script>

<template>
    <Head title="Equipos" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="rounded-xl bg-white p-6 shadow dark:bg-gray-900">
            <h1 class="mb-4 text-2xl font-bold text-[#003366]">
                🏟 Equipos Registrados
            </h1>

            <Link
                href="/admin/reports/teams"
                class="mb-4 inline-block rounded-lg bg-green-600 px-4 py-2 text-white shadow hover:bg-green-700"
            >
                Descargar Excel
            </Link>

            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-200 text-left dark:bg-gray-700">
                        <th class="p-3 text-center">Equipo</th>
                        <th class="p-3 text-center">Ciudad</th>
                        <th class="p-3 text-center">Estadio</th>
                        <th class="p-3 text-center">Capacidad</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="team in props.teams"
                        :key="team.id"
                        class="border-b"
                    >
                        <td class="p-3 text-center">{{ team.name }}</td>
                        <td class="p-3 text-center">{{ team.city }}</td>
                        <td class="p-3 text-center">{{ team.stadium }}</td>
                        <td class="p-3 text-center">{{ team.capacity }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>

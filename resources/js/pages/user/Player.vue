<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';

interface Team {
    id: number;
    name: string;
}

interface Player {
    id: number;
    fullname: string;
    birth_date: string;
    position: string;
    team?: Team | null;
}

const props = defineProps<{
    players: Player[];
}>();

const breadcrumbs = [{ title: 'Jugadores', href: dashboard().url }];
</script>

<template>
    <Head title="Jugadores" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="rounded-xl bg-white p-6 shadow dark:bg-gray-900">
            <h1 class="mb-4 text-2xl font-bold text-[#003366]">⚽ Jugadores</h1>

            <Link
                href="/admin/reports/players"
                class="mb-4 inline-block rounded-lg bg-green-600 px-4 py-2 text-white shadow hover:bg-green-700"
            >
                Descargar Excel
            </Link>

            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-200 text-center dark:bg-gray-700">
                        <th class="p-3">Nombre</th>
                        <th class="p-3">Fecha Nac.</th>
                        <th class="p-3">Posición</th>
                        <th class="p-3">Equipo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="p in props.players"
                        :key="p.id"
                        class="border-b text-center"
                    >
                        <td class="p-2">{{ p.fullname }}</td>
                        <td class="p-2">{{ p.birth_date }}</td>
                        <td class="p-2">{{ p.position }}</td>
                        <td class="p-2">{{ p.team?.name }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>

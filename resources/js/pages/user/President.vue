<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';

interface Team {
    id: number;
    name: string;
}

interface President {
    id: number;
    DNI: string;
    name: string;
    lastname: string;
    elected_date: string;
    team?: Team | null;
}

const props = defineProps<{
    presidents: President[];
}>();

const breadcrumbs = [{ title: 'Presidentes', href: dashboard().url }];
</script>

<template>
    <Head title="Presidentes" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="rounded-xl bg-white p-6 shadow-md dark:bg-gray-900">
            <h1 class="mb-4 text-2xl font-bold text-[#003366]">
                👔 Presidentes de Equipos
            </h1>

            <Link
                href="/admin/reports/presidents"
                class="mb-4 inline-block rounded-lg bg-green-600 px-4 py-2 text-white shadow hover:bg-green-700"
            >
                Descargar Excel
            </Link>

            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-200 text-center dark:bg-gray-700">
                        <th class="p-3">DNI</th>
                        <th class="p-3">Nombre</th>
                        <th class="p-3">Equipo</th>
                        <th class="p-3">Elegido</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="pr in props.presidents"
                        :key="pr.id"
                        class="border-b text-center"
                    >
                        <td class="p-2">{{ pr.DNI }}</td>
                        <td class="p-2">{{ pr.name }} {{ pr.lastname }}</td>
                        <td class="p-2">{{ pr.team?.name }}</td>
                        <td class="p-2">{{ pr.elected_date }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>

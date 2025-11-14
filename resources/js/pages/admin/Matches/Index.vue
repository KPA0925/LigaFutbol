<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

defineProps<{
    matches: {
        id: number;
        match_date_time: string;
        goal_home: number;
        goal_away: number;
        season: string;
        home_team?: { id: number; name: string } | null;
        away_team?: { id: number; name: string } | null;
    }[];
}>();

function destroyMatch(id: number) {
    if (confirm('¿Seguro que deseas eliminar este partido?')) {
        router.delete(route('admin.matches.destroy', id));
    }
}
</script>

<template>
    <Head title="Partidos" />

    <AppLayout>
        <div class="p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold">📅 Partidos</h1>
                <Link :href="route('admin.matches.create')">
                    <Button>➕ Nuevo Partido</Button>
                </Link>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border border-gray-300 text-left text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3">Fecha y Hora</th>
                            <th class="p-3">Temporada</th>
                            <th class="p-3">Equipos</th>
                            <th class="p-3">Marcador</th>
                            <th class="p-3">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="match in matches"
                            :key="match.id"
                            class="border-t transition hover:bg-gray-50"
                        >
                            <td class="p-3">
                                {{
                                    new Date(
                                        match.match_date_time,
                                    ).toLocaleString()
                                }}
                            </td>
                            <td class="p-3">{{ match.season }}</td>
                            <td class="p-3">
                                {{ match.home_team?.name ?? '—' }} vs
                                {{ match.away_team?.name ?? '—' }}
                            </td>
                            <td class="p-3">
                                {{ match.goal_home }} - {{ match.goal_away }}
                            </td>
                            <td class="flex gap-2 p-3">
                                <Link
                                    :href="
                                        route('admin.matches.edit', match.id)
                                    "
                                >
                                    <Button size="sm" variant="outline">
                                        ✏️ Editar
                                    </Button>
                                </Link>

                                <Button
                                    size="sm"
                                    variant="destructive"
                                    @click="destroyMatch(match.id)"
                                >
                                    🗑️ Eliminar
                                </Button>
                            </td>
                        </tr>

                        <tr v-if="matches.length === 0">
                            <td
                                colspan="5"
                                class="p-4 text-center text-gray-500"
                            >
                                No hay partidos registrados aún.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>

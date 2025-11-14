<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

defineProps<{
    goals: {
        id: number;
        minute: number;
        description: string | null;
        player: { fullname: string; team: { name: string } };
        match: { season: string; match_date_time: string };
    }[];
}>();

function destroyGoal(id: number) {
    if (confirm('¿Seguro que deseas eliminar este gol?')) {
        router.delete(route('admin.goals.destroy', id));
    }
}
</script>

<template>
    <Head title="Goles" />
    <AppLayout>
        <div class="p-6">
            <!-- Título y botón -->
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold">⚽ Registro de Goles</h1>
                <Link :href="route('admin.goals.create')">
                    <Button>➕ Nuevo Gol</Button>
                </Link>
            </div>

            <!-- Tabla -->
            <div
                class="overflow-x-auto rounded-lg border border-gray-300 shadow-sm"
            >
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="p-3">Partido</th>
                            <th class="p-3">Equipo</th>
                            <th class="p-3">Jugador</th>
                            <th class="p-3">Minuto</th>
                            <th class="p-3">Descripción</th>
                            <th class="p-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="goal in goals"
                            :key="goal.id"
                            class="border-t transition hover:bg-gray-50"
                        >
                            <td class="p-3">
                                {{ goal.match.season }} —
                                {{
                                    new Date(
                                        goal.match.match_date_time,
                                    ).toLocaleString()
                                }}
                            </td>
                            <td class="p-3">{{ goal.player.team?.name }}</td>
                            <td class="p-3">{{ goal.player.fullname }}</td>
                            <td class="p-3 font-semibold">
                                {{ goal.minute }}’
                            </td>
                            <td class="p-3 italic text-gray-700">
                                {{ goal.description || '—' }}
                            </td>
                            <td class="flex justify-center gap-2 p-3">
                                <Link
                                    :href="route('admin.goals.edit', goal.id)"
                                >
                                    <Button size="sm" variant="outline">
                                        Editar
                                    </Button>
                                </Link>
                                <Button
                                    size="sm"
                                    @click="destroyGoal(goal.id)"
                                >
                                    Eliminar
                                </Button>
                            </td>
                        </tr>

                        <tr v-if="goals.length === 0">
                            <td
                                colspan="6"
                                class="p-4 text-center text-gray-500"
                            >
                                No hay goles registrados aún.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>

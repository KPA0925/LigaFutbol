<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

defineProps<{
    players: {
        id: number;
        fullname: string;
        birth_date: string;
        position: string;
        team?: { name: string };
    }[];
}>();

function destroyPlayer(id: number) {
    if (confirm('¿Seguro que deseas eliminar este jugador?')) {
        router.delete(route('admin.players.destroy', id));
    }
}
</script>

<template>
    <Head title="Jugadores" />
    <AppLayout>
        <div class="p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold">⚽ Jugadores</h1>
                <Link :href="route('admin.players.create')">
                    <Button>➕ Nuevo Jugador</Button>
                </Link>
            </div>

            <div class="overflow-x-auto">
                <table
                    class="w-full text-left text-sm text-gray-500 dark:text-gray-400"
                >
                    <thead
                        class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400"
                    >
                        <tr>
                            <th class="px-6 py-3">Nombre Completo</th>
                            <th class="px-6 py-3">Cumpleaños</th>
                            <th class="px-6 py-3">Posición</th>
                            <th class="px-6 py-3">Equipo</th>
                            <th class="px-6 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="player in players"
                            :key="player.id"
                            class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900 dark:even:bg-gray-700"
                        >
                            <td class="px-6 py-3">{{ player.fullname }}</td>
                            <td class="px-6 py-3">{{ player.birth_date }}</td>
                            <td class="px-6 py-3">{{ player.position }}</td>
                            <td class="px-6 py-3">
                                {{ player.team?.name ?? 'Sin equipo' }}
                            </td>
                            <td class="flex gap-2 px-6 py-3">
                                <Link
                                    :href="
                                        route('admin.players.edit', player.id)
                                    "
                                >
                                    <Button variant="outline" size="sm"
                                        >Editar</Button
                                    >
                                </Link>
                                <Button
                                    size="sm"
                                    @click="destroyPlayer(player.id)"
                                    >Eliminar</Button
                                >
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>

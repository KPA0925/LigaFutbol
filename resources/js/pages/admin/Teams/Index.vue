<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

defineProps<{
    teams: {
        id: number;
        name: string;
        founded_date: string;
        city: string;
        stadium: string;
        capacity: number;
        players_count: number;
    }[];
}>();

function destroyTeam(id: number) {
    if (confirm('¿Seguro que deseas eliminar este equipo?')) {
        router.delete(route('admin.teams.destroy', id));
    }
}
</script>

<template>
    <Head title="Equipos" />
    <AppLayout>
        <div class="p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold">⚽ Equipos</h1>
                <Link :href="route('admin.teams.create')">
                    <Button>➕ Nuevo Equipo</Button>
                </Link>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                <Card v-for="team in teams" :key="team.id">
                    <CardContent class="space-y-2 p-4">
                        <h2 class="text-lg font-semibold">{{ team.name }}</h2>
                        <p><strong>Ciudad:</strong> {{ team.city }}</p>
                        <p><strong>Estadio:</strong> {{ team.stadium }}</p>
                        <p><strong>Capacidad:</strong> {{ team.capacity }}</p>
                        <p>
                            <strong>Jugadores:</strong> {{ team.players_count }}
                        </p>
                        <div class="flex justify-between pt-2">
                            <Link :href="route('admin.teams.edit', team.id)">
                                <Button variant="outline" size="sm"
                                    >Editar</Button
                                >
                            </Link>
                            <Button
                                size="sm"
                                @click="destroyTeam(team.id)"
                                >Eliminar</Button
                            >
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>

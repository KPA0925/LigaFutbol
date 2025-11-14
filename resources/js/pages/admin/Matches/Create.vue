<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

defineProps<{
    teams: { id: number; name: string }[];
}>();

const form = useForm({
    match_date_time: '',
    season: '',
    id_home_team: '',
    id_away_team: '',
});

function submit() {
    form.post(route('admin.matches.store'));
}
</script>

<template>
    <Head title="Nuevo Partido" />

    <AppLayout>
        <div class="mx-auto max-w-xl p-6">
            <h1 class="mb-4 text-2xl font-bold text-gray-800">
                ➕ Registrar Partido
            </h1>

            <form @submit.prevent="submit" class="space-y-5">
                <!-- Fecha -->
                <div>
                    <Label>Fecha y Hora</Label>
                    <Input
                        type="datetime-local"
                        v-model="form.match_date_time"
                        required
                    />
                </div>

                <!-- Temporada -->
                <div>
                    <Label>Temporada</Label>
                    <Input v-model="form.season" required />
                </div>

                <!-- Local -->
                <div>
                    <Label>Equipo Local</Label>
                    <select
                        v-model="form.id_home_team"
                        class="w-full rounded border p-2"
                        required
                    >
                        <option value="">Seleccione un equipo</option>
                        <option v-for="team in teams" :value="team.id">
                            {{ team.name }}
                        </option>
                    </select>
                </div>

                <!-- Visitante -->
                <div>
                    <Label>Equipo Visitante</Label>
                    <select
                        v-model="form.id_away_team"
                        class="w-full rounded border p-2"
                        required
                    >
                        <option value="">Seleccione un equipo</option>
                        <option v-for="team in teams" :value="team.id">
                            {{ team.name }}
                        </option>
                    </select>
                </div>

                <div class="flex justify-end gap-2">
                    <Link :href="route('admin.matches.index')">
                        <Button variant="outline">Cancelar</Button>
                    </Link>
                    <Button type="submit">Guardar</Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

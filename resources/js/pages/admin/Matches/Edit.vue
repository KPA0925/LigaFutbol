<script setup>
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const props = defineProps({
    match: Object,
    teams: Array,
    id_home_team: Number,
    id_away_team: Number,
});

// Form con valores precargados
const form = useForm({
    match_date_time: props.match.match_date_time_formatted,
    season: props.match.season,
    id_home_team: props.id_home_team,
    id_away_team: props.id_away_team,
});

function submit() {
    form.put(route('admin.matches.update', props.match.id));
}
</script>

<template>
    <Head title="Editar Partido" />
    <AppLayout>
        <div class="mx-auto max-w-xl p-6">
            <h1 class="mb-6 text-2xl font-bold">✏️ Editar Partido</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <!-- FECHA Y HORA -->
                <div>
                    <Label>Fecha y Hora</Label>
                    <Input
                        type="datetime-local"
                        v-model="form.match_date_time"
                        required
                    />
                </div>

                <!-- TEMPORADA -->
                <div>
                    <Label>Temporada</Label>
                    <Input v-model="form.season" required />
                </div>

                <!-- EQUIPO LOCAL -->
                <div>
                    <Label>Equipo Local</Label>
                    <select
                        v-model="form.id_home_team"
                        class="w-full rounded border p-2"
                        required
                    >
                        <option
                            v-for="team in teams"
                            :key="team.id"
                            :value="team.id"
                        >
                            {{ team.name }}
                        </option>
                    </select>
                </div>

                <!-- EQUIPO VISITANTE -->
                <div>
                    <Label>Equipo Visitante</Label>
                    <select
                        v-model="form.id_away_team"
                        class="w-full rounded border p-2"
                        required
                    >
                        <option
                            v-for="team in teams"
                            :key="team.id"
                            :value="team.id"
                        >
                            {{ team.name }}
                        </option>
                    </select>
                </div>

                <!-- BOTONES -->
                <div class="flex justify-end gap-2 pt-4">
                    <Link :href="route('admin.matches.index')">
                        <Button variant="outline">Cancelar</Button>
                    </Link>
                    <Button :disabled="form.processing">Actualizar</Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

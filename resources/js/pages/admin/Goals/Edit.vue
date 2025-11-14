<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';

// Props desde el controlador
const props = defineProps<{
    goal: any;
    players: any[];
    matches: any[];
}>();

// Formulario con valores actuales
const form = useForm({
    id_match: props.goal.id_match,
    id_team: props.goal.player?.team?.id || '',
    id_player: props.goal.id_player,
    minute: props.goal.minute,
    description: props.goal.description,
});

// PARTIDO SELECCIONADO
const selectedMatch = computed(() =>
    props.matches.find((m) => m.id === Number(form.id_match)),
);

// EQUIPOS DISPONIBLES
const availableTeams = computed(() => {
    if (!selectedMatch.value) return [];
    return [
        selectedMatch.value.home_team,
        selectedMatch.value.away_team,
    ].filter(Boolean);
});

// JUGADORES DISPONIBLES
const availablePlayers = computed(() =>
    props.players.filter((p) => p.id_team === Number(form.id_team)),
);

function submit() {
    form.put(route('admin.goals.update', props.goal.id));
}
</script>

<template>
    <Head title="Editar Gol" />
    <AppLayout>
        <div class="mx-auto max-w-xl p-6">
            <h1 class="mb-4 text-2xl font-bold">✏️ Editar Gol</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <!-- PARTIDO -->
                <div>
                    <Label>Partido</Label>
                    <select
                        v-model="form.id_match"
                        class="w-full rounded border p-2"
                        required
                    >
                        <option value="">Seleccione un partido</option>
                        <option
                            v-for="m in props.matches"
                            :key="m.id"
                            :value="m.id"
                        >
                            {{ m.season }} - {{ m.match_date_time }} -
                            {{ m.home_team?.name }} vs {{ m.away_team?.name }}
                        </option>
                    </select>
                </div>

                <!-- EQUIPO -->
                <div v-if="selectedMatch">
                    <Label>Equipo</Label>
                    <select
                        v-model="form.id_team"
                        class="w-full rounded border p-2"
                        required
                    >
                        <option value="">Seleccione un equipo</option>
                        <option
                            v-for="t in availableTeams"
                            :key="t.id"
                            :value="t.id"
                        >
                            {{ t.name }}
                        </option>
                    </select>
                </div>

                <!-- JUGADOR -->
                <div v-if="form.id_team">
                    <Label>Jugador</Label>
                    <select
                        v-model="form.id_player"
                        class="w-full rounded border p-2"
                        required
                    >
                        <option value="">Seleccione un jugador</option>
                        <option
                            v-for="p in availablePlayers"
                            :key="p.id"
                            :value="p.id"
                        >
                            {{ p.fullname }}
                        </option>
                    </select>
                </div>

                <!-- MINUTO -->
                <div>
                    <Label>Minuto</Label>
                    <Input
                        type="number"
                        v-model="form.minute"
                        min="1"
                        max="120"
                        required
                    />
                </div>

                <!-- DESCRIPCIÓN -->
                <div>
                    <Label>Descripción</Label>
                    <Input
                        v-model="form.description"
                        placeholder="Ej: Gol de cabeza"
                    />
                </div>

                <!-- BOTONES -->
                <div class="flex justify-end gap-2 pt-4">
                    <Link :href="route('admin.goals.index')">
                        <Button variant="outline">Cancelar</Button>
                    </Link>
                    <Button type="submit" :disabled="form.processing"
                        >Actualizar</Button
                    >
                </div>
            </form>
        </div>
    </AppLayout>
</template>

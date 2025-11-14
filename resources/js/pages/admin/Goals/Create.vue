<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';

// ⬅️ ESTA LÍNEA ES CLAVE
const props = defineProps<{ players: any[]; matches: any[] }>();

const form = useForm({
    id_match: '',
    id_team: '',
    id_player: '',
    minute: '',
    description: '',
});

const selectedMatchData = computed(() => {
    return props.matches.find((m) => m.id === Number(form.id_match));
});

const availableTeams = computed(() => {
    if (!selectedMatchData.value) return [];
    const { home_team, away_team } = selectedMatchData.value;
    return [home_team, away_team].filter(Boolean);
});

const availablePlayers = computed(() => {
    if (!form.id_team) return [];
    return props.players.filter((p) => p.id_team === Number(form.id_team));
});

function submit() {
    form.post(route('admin.goals.store'));
}
</script>

<template>
    <Head title="Registrar Gol" />
    <AppLayout>
        <div class="mx-auto max-w-xl p-6">
            <h1 class="mb-4 text-2xl font-bold">➕ Registrar Gol</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <!-- Partido -->
                <div>
                    <Label>Partido</Label>
                    <select
                        v-model="form.id_match"
                        class="w-full rounded border p-2"
                        required
                    >
                        <option value="">Seleccione un partido</option>
                        <option v-for="m in matches" :key="m.id" :value="m.id">
                            {{ m.season }} - {{ m.match_date_time }} |
                            {{ m.home_team?.name }} vs {{ m.away_team?.name }}
                        </option>
                    </select>
                </div>

                <!-- Equipo -->
                <div v-if="form.id_match">
                    <Label>Equipo</Label>
                    <select
                        v-model="form.id_team"
                        class="w-full rounded border p-2"
                        required
                    >
                        <option value="">Seleccione un equipo</option>
                        <option
                            v-for="t in availableTeams"
                            :key="t?.id"
                            :value="t?.id"
                        >
                            {{ t?.name }}
                        </option>
                    </select>
                </div>

                <!-- Jugador -->
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

                <!-- Minuto -->
                <div v-if="form.id_player">
                    <Label>Minuto</Label>
                    <Input
                        type="number"
                        v-model="form.minute"
                        min="1"
                        max="120"
                        required
                    />
                </div>

                <!-- Descripción -->
                <div>
                    <Label>Descripción</Label>
                    <Input
                        v-model="form.description"
                        placeholder="Ej: Gol de tiro libre"
                    />
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <Link :href="route('admin.goals.index')">
                        <Button variant="outline">Cancelar</Button>
                    </Link>
                    <Button type="submit" :disabled="form.processing">
                        Guardar
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

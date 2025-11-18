<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';
import { type BreadcrumbItem } from '@/types';

const props = defineProps<{
    goal: any;
    players: any[];
    matches: any[];
}>();

const form = useForm({
    id_match: props.goal.id_match,
    id_team: props.goal.player?.team?.id || '',
    id_player: props.goal.id_player,
    minute: props.goal.minute,
    description: props.goal.description,
});

const selectedMatch = computed(() =>
    props.matches.find((m) => m.id === Number(form.id_match)),
);

const availableTeams = computed(() => {
    if (!selectedMatch.value) return [];
    return [
        selectedMatch.value.home_team,
        selectedMatch.value.away_team,
    ].filter(Boolean);
});

const availablePlayers = computed(() =>
    props.players.filter((p) => p.id_team === Number(form.id_team)),
);

function submit() {
    form.put(route('admin.goals.update', props.goal.id));
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Goles', href: route('admin.goals.index') },
    { title: 'Editar Gol', href: '' },
];
</script>

<template>
    <Head title="Editar Gol" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex justify-center py-10">
            <div
                class="w-full max-w-xl rounded-xl border border-gray-200 bg-white p-8 shadow-lg"
            >
                <!-- Título -->
                <h2 class="mb-1 text-2xl font-semibold text-gray-800">
                    Editar Gol
                </h2>
                <p class="mb-6 text-gray-500">
                    Modifique los datos del gol registrado.
                </p>

                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Partido -->
                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Partido</Label>
                        <select
                            v-model="form.id_match"
                            class="h-11 rounded-lg border border-gray-300 bg-white px-3 text-gray-700 focus:border-blue-500"
                            required
                        >
                            <option value="">Seleccione un partido</option>
                            <option
                                v-for="m in props.matches"
                                :key="m.id"
                                :value="m.id"
                            >
                                {{ m.season }} — {{ m.match_date_time }} |
                                {{ m.home_team?.name }} vs
                                {{ m.away_team?.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Equipo -->
                    <div class="flex flex-col gap-1" v-if="selectedMatch">
                        <Label class="text-gray-700">Equipo</Label>
                        <select
                            v-model="form.id_team"
                            class="h-11 rounded-lg border border-gray-300 bg-white px-3 text-gray-700 focus:border-blue-500"
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

                    <!-- Jugador -->
                    <div class="flex flex-col gap-1" v-if="form.id_team">
                        <Label class="text-gray-700">Jugador</Label>
                        <select
                            v-model="form.id_player"
                            class="h-11 rounded-lg border border-gray-300 bg-white px-3 text-gray-700 focus:border-blue-500"
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
                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Minuto</Label>
                        <Input
                            type="number"
                            min="1"
                            max="120"
                            v-model="form.minute"
                            class="h-11 rounded-lg border-gray-300 focus:border-blue-500"
                            required
                        />
                    </div>

                    <!-- Descripción -->
                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Descripción</Label>
                        <Input
                            v-model="form.description"
                            placeholder="Ej: Gol de cabeza"
                            class="h-11 rounded-lg border-gray-300 focus:border-blue-500"
                        />
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end gap-3 pt-3">
                        <Link :href="route('admin.goals.index')">
                            <Button
                                variant="outline"
                                class="px-4 py-2 text-[#D62027] border-[#D62027]"
                            >
                                Cancelar
                            </Button>
                        </Link>

                        <Button
                            type="submit"
                            class="px-4 py-2 bg-[#D62027] text-white"
                            :disabled="form.processing"
                        >
                            Actualizar
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

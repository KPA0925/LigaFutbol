<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';

const props = defineProps<{ players: any[]; matches: any[] }>();

const form = useForm({
    id_match: '',
    id_team: '',
    id_player: '',
    minute: '',
    description: '',
});

// 📌 Obtener datos del partido seleccionado
const selectedMatchData = computed(() =>
    props.matches.find((m) => m.id === Number(form.id_match)),
);

// 📌 Equipos del partido seleccionado
const availableTeams = computed(() => {
    if (!selectedMatchData.value) return [];
    const { home_team, away_team } = selectedMatchData.value;
    return [home_team, away_team].filter(Boolean);
});

// 📌 Jugadores del equipo seleccionado
const availablePlayers = computed(() =>
    props.players.filter((p) => p.id_team === Number(form.id_team)),
);

function submit() {
    form.post(route('admin.goals.store'));
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Goles', href: route('admin.goals.index') },
    { title: 'Registrar Gol', href: '' },
];
</script>

<template>
    <Head title="Registrar Gol" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex justify-center py-10">
            <div
                class="w-full max-w-xl rounded-xl border border-gray-200 bg-white p-8 shadow-lg"
            >
                <!-- Título -->
                <h2 class="mb-1 text-2xl font-semibold text-gray-800">
                    Registrar Gol
                </h2>
                <p class="mb-6 text-gray-500">
                    Complete la información del gol registrado en el partido.
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

                        <!-- ❗ MENSAJE DE ERROR DEL PARTIDO -->
                        <p
                            v-if="form.errors.id_match"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.id_match }}
                        </p>
                    </div>

                    <!-- Equipo -->
                    <div class="flex flex-col gap-1" v-if="form.id_match">
                        <Label class="text-gray-700">Equipo</Label>

                        <select
                            v-model="form.id_team"
                            class="h-11 rounded-lg border border-gray-300 bg-white px-3 text-gray-700 focus:border-blue-500"
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
                    <div class="flex flex-col gap-1" v-if="form.id_player">
                        <Label class="text-gray-700">Minuto</Label>
                        <Input
                            type="number"
                            v-model="form.minute"
                            min="1"
                            max="120"
                            class="h-11 rounded-lg border-gray-300 focus:border-blue-500"
                            required
                        />
                    </div>

                    <!-- Descripción -->
                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Descripción</Label>
                        <Input
                            v-model="form.description"
                            placeholder="Ej: Gol de tiro libre"
                            class="h-11 rounded-lg border-gray-300 focus:border-blue-500"
                        />
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end gap-3 pt-3">
                        <Link :href="route('admin.goals.index')">
                            <Button
                                variant="outline"
                                class="border-[#D62027] px-4 py-2 text-[#D62027]"
                            >
                                Cancelar
                            </Button>
                        </Link>

                        <Button
                            type="submit"
                            class="bg-[#D62027] px-4 py-2 text-white"
                            :disabled="form.processing"
                        >
                            Guardar
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

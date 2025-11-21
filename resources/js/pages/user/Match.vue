<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { route } from 'ziggy-js';

interface Match {
    id: number;
    season: string;
    match_date_time: string;
    home_team: { id: number; name: string };
    away_team: { id: number; name: string };
    goal_home: number;
    goal_away: number;
}

const props = defineProps<{ matches: Match[] }>();

const searchSeason = ref<number | null>(null);
const searchTeam = ref('');
const searchDate = ref('');

const appliedSeason = ref<number | null>(null);
const appliedTeam = ref('');
const appliedDate = ref('');

function applySearch() {
    appliedSeason.value = searchSeason.value;
    appliedTeam.value = searchTeam.value.toLowerCase().trim();
    appliedDate.value = searchDate.value.trim();
}

function clearFilters() {
    searchSeason.value = null;
    searchTeam.value = '';
    searchDate.value = '';

    appliedSeason.value = null;
    appliedTeam.value = '';
    appliedDate.value = '';
}

const filteredMatches = computed(() => {
    return props.matches.filter((m) => {
        let ok = true;

        if (appliedSeason.value && !isNaN(Number(appliedSeason.value))) {
            ok = ok && Number(m.season) === Number(appliedSeason.value);
        }

        if (appliedTeam.value) {
            const teams =
                `${m.home_team.name} ${m.away_team.name}`.toLowerCase();
            ok = ok && teams.includes(appliedTeam.value);
        }

        if (appliedDate.value) {
            const d = new Date(m.match_date_time);
            const y = d.getFullYear();
            const mo = String(d.getMonth() + 1).padStart(2, '0');
            const da = String(d.getDate()).padStart(2, '0');
            const formatted = `${y}-${mo}-${da}`;
            ok = ok && formatted === appliedDate.value;
        }

        return ok;
    });
});

function formatDate(dateStr: string) {
    const d = new Date(dateStr);
    const y = d.getFullYear();
    const m = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    const hh = String(d.getHours()).padStart(2, '0');
    const mm = String(d.getMinutes()).padStart(2, '0');
    return `${day} ${getMonthName(m)} ${y} · ${hh}:${mm}`;
}

function getMonthName(month: string) {
    const meses = [
        'Ene',
        'Feb',
        'Mar',
        'Abr',
        'May',
        'Jun',
        'Jul',
        'Ago',
        'Sep',
        'Oct',
        'Nov',
        'Dic',
    ];
    return meses[Number(month) - 1];
}

function matchStatus(dateStr: string) {
    const now = new Date();
    const match = new Date(dateStr);
    const endMatch = new Date(match.getTime() + 2 * 60 * 60 * 1000);

    if (now < match) return 'En espera';
    if (now >= match && now <= endMatch) return 'Jugando';
    return 'Finalizado';
}

const breadcrumbs = [{ title: 'Partidos', href: dashboard().url }];
</script>

<template>
    <Head title="Partidos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-8 p-6">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-800">Partidos</h1>

                <a
                    :href="
                        route('export.excel', {
                            module: 'matches',
                            season: appliedSeason || undefined,
                            team: appliedTeam || undefined,
                            date: appliedDate || undefined,
                        })
                    "
                    target="_blank"
                >
                    <Button class="bg-[#D62027] px-4 py-2 text-white">
                        <i class="fa-solid fa-download"></i> Exportar Excel
                    </Button>
                </a>
            </div>

            <div class="rounded-xl border bg-white p-6 shadow-md">
                <h2 class="mb-4 text-lg font-semibold">Filtros de Búsqueda</h2>

                <div class="grid gap-6 md:grid-cols-3">
                    <div>
                        <label class="text-sm">Temporada</label>
                        <input
                            v-model.number="searchSeason"
                            type="number"
                            placeholder="Ej: 2025"
                            class="mt-1 w-full rounded-lg border p-2"
                        />
                    </div>

                    <div>
                        <label class="text-sm">Equipo</label>
                        <input
                            v-model="searchTeam"
                            type="text"
                            placeholder="Buscar por equipo"
                            class="mt-1 w-full rounded-lg border p-2"
                        />
                    </div>

                    <div>
                        <label class="text-sm">Fecha</label>
                        <input
                            v-model="searchDate"
                            type="date"
                            class="mt-1 w-full rounded-lg border p-2"
                        />
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <Button
                        variant="outline"
                        class="border-red-600 text-red-600"
                        @click="clearFilters"
                    >
                        Limpiar
                    </Button>

                    <Button class="bg-red-600 text-white" @click="applySearch">
                        Buscar
                    </Button>
                </div>
            </div>

            <div
                v-if="filteredMatches.length > 0"
                class="grid gap-6 md:grid-cols-2 lg:grid-cols-3"
            >
                <div
                    v-for="m in filteredMatches"
                    :key="m.id"
                    class="rounded-2xl border bg-white p-6 shadow-md transition hover:shadow-xl"
                >
                    <div class="mb-4 flex items-center justify-between">
                        <span
                            class="rounded-full px-3 py-1 text-xs font-semibold"
                            :class="{
                                'bg-red-100 text-red-600':
                                    matchStatus(m.match_date_time) ===
                                    'Jugando',
                                'bg-gray-200 text-gray-700':
                                    matchStatus(m.match_date_time) ===
                                    'En espera',
                                'bg-green-100 text-green-700':
                                    matchStatus(m.match_date_time) ===
                                    'Finalizado',
                            }"
                        >
                            {{
                                matchStatus(m.match_date_time) === 'Jugando'
                                    ? 'En vivo'
                                    : matchStatus(m.match_date_time) ===
                                        'En espera'
                                      ? 'Próximo'
                                      : 'Finalizado'
                            }}
                        </span>

                        <div
                            class="flex items-center gap-1 text-sm text-gray-500"
                        >
                            <i
                                class="fa-solid fa-calendar-days text-red-600"
                            ></i>
                            <span>{{ formatDate(m.match_date_time) }}</span>
                        </div>
                    </div>

                    <div
                        class="mb-3 flex items-center justify-between px-4 text-center"
                    >
                        <div class="flex-1 font-bold text-gray-900">
                            {{ m.home_team.name }}
                        </div>
                        <i class="fa-solid fa-trophy text-3xl text-red-600"></i>
                        <div class="flex-1 font-bold text-gray-900">
                            {{ m.away_team.name }}
                        </div>
                    </div>

                    <div
                        class="text-center text-4xl font-extrabold text-red-600"
                    >
                        {{ m.goal_home }}
                        <span class="text-black"> - </span>
                        {{ m.goal_away }}
                    </div>

                    <div
                        class="mt-2 flex items-center justify-center gap-2 text-sm text-gray-500"
                    >
                        <i class="fa-solid fa-flag text-red-600"></i>
                        <span>Temporada {{ m.season }}</span>
                    </div>
                </div>
            </div>

            <div
                v-else
                class="rounded-xl border bg-white p-6 text-center text-gray-500 shadow-md"
            >
                No hay resultados.
            </div>
        </div>
    </AppLayout>
</template>
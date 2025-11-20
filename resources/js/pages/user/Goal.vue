<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { dashboard } from '@/routes';
import { route } from 'ziggy-js';
import { Button } from '@/components/ui/button';

interface Goal {
    id: number;
    minute: number;
    description: string | null;
    player: { fullname: string; team?: { name: string } | null };
    match: {
        season: string;
        match_date_time: string;
        homeTeam?: { name: string } | null;
        awayTeam?: { name: string } | null;
    };
}

const props = defineProps<{ goals: Goal[] }>();

/* -------------------------
   🔍 Filtros inputs
--------------------------*/
const filters = ref({
    season: '',
    team: '',
    player: '',
});

/* -------------------------
   🔍 Filtros aplicados (congelados)
--------------------------*/
const appliedFilters = ref({
    season: '',
    team: '',
    player: '',
});

const filtersEnabled = ref(false);

/* -------------------------
   🔘 Aplicar filtros
--------------------------*/
function applyFilters() {
    appliedFilters.value = { ...filters.value };
    filtersEnabled.value = true;
}

/* -------------------------
   🧹 Limpiar filtros
--------------------------*/
function resetFilters() {
    filters.value = { season: '', team: '', player: '' };
    appliedFilters.value = { ...filters.value };
    filtersEnabled.value = false;
}

/* -------------------------
   🔎 Filtrado computado
--------------------------*/
const filtered = computed(() => {
    if (!filtersEnabled.value) return props.goals;

    return props.goals.filter((g) => {
        const seasonOK = g.match.season
            .toLowerCase()
            .includes(appliedFilters.value.season.toLowerCase());

        const teamName =
            (g.player.team?.name ?? '') +
            ' ' +
            (g.match.homeTeam?.name ?? '') +
            ' ' +
            (g.match.awayTeam?.name ?? '');

        const teamOK = teamName
            .toLowerCase()
            .includes(appliedFilters.value.team.toLowerCase());

        const playerOK = g.player.fullname
            .toLowerCase()
            .includes(appliedFilters.value.player.toLowerCase());

        return seasonOK && teamOK && playerOK;
    });
});

/* -------------------------
   Utilities
--------------------------*/
function shortDate(dt: string) {
    const d = new Date(dt);
    const day = String(d.getDate()).padStart(2, '0');
    const moName = getMonthName(String(d.getMonth() + 1).padStart(2, '0'));
    const y = d.getFullYear();
    return `${day} ${moName} ${y}`;
}

function getMonthName(month: string) {
    const meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
    return meses[Number(month) - 1] ?? month;
}

const breadcrumbs = [{ title: 'Goles', href: dashboard().url }];
</script>

<template>
    <Head title="Goles" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-8 p-6">

            <!-- Header + Export -->
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-800">Goles</h1>

                <a
                    :href="
                        route('export.excel', {
                            module: 'goals',
                            season: appliedFilters.season || undefined,
                            team: appliedFilters.team || undefined,
                            player: appliedFilters.player || undefined,
                        })
                    "
                    target="_blank"
                >
                    <Button class="bg-red-600 text-white hover:bg-red-700">
                        <i class="fa-solid fa-download"></i> Exportar Excel
                    </Button>
                </a>
            </div>

            <!-- FILTROS -->
            <div class="rounded-xl border bg-white p-6 shadow-md">
                <h2 class="mb-4 text-lg font-semibold text-gray-800">Filtros de Búsqueda</h2>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Temporada</label>
                        <input
                            v-model="filters.season"
                            type="text"
                            placeholder="Ej: 2024"
                            class="mt-1 w-full rounded-lg border-gray-300 p-2"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Equipo</label>
                        <input
                            v-model="filters.team"
                            type="text"
                            placeholder="Buscar equipo"
                            class="mt-1 w-full rounded-lg border-gray-300 p-2"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Jugador</label>
                        <input
                            v-model="filters.player"
                            type="text"
                            placeholder="Buscar jugador"
                            class="mt-1 w-full rounded-lg border-gray-300 p-2"
                        />
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <Button
                        variant="outline"
                        class="border-red-600 text-red-600 hover:bg-red-50"
                        @click="resetFilters"
                    >
                        Limpiar
                    </Button>

                    <Button
                        class="bg-red-600 text-white hover:bg-red-700"
                        @click="applyFilters"
                    >
                        <i class="fa-solid fa-magnifying-glass"></i> Buscar
                    </Button>
                </div>
            </div>

            <!-- CARDS -->
            <div
                v-if="filtered.length > 0"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
            >
                <div
                    v-for="g in filtered"
                    :key="g.id"
                    class="rounded-2xl border bg-white p-6 flex flex-col shadow hover:shadow-lg transition"
                >
                    <!-- top: minute + name + team -->
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 rounded-full bg-red-600 text-white flex items-center justify-center text-lg font-bold">
                            {{ g.minute }}'
                        </div>

                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900">
                                {{ g.player.fullname }}
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                <i class="fa-solid fa-shield"></i>
                                {{ g.player.team?.name ?? 'Sin equipo' }}
                            </p>

                            <div v-if="g.description" class="mt-3">
                                <span class="inline-block px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-full">
                                    {{ g.description }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="my-4 border-t"></div>

                    <!-- bottom row: season + match date (same row) -->
                    <div class="grid grid-cols-2 gap-4 text-center text-gray-700">

                        <!-- Temporada -->
                        <div>
                            <div class="text-lg font-semibold text-gray-900">
                                {{ g.match.season }}
                            </div>
                            <div class="text-xs text-gray-500">Temporada</div>
                        </div>

                        <!-- Fecha del partido -->
                        <div>
                            <div class="text-lg font-semibold text-gray-900">
                                {{ shortDate(g.match.match_date_time) }}
                            </div>
                            <div class="text-xs text-gray-500">Fecha del Partido</div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- empty -->
            <div
                v-else
                class="rounded-xl border bg-white p-6 shadow-md text-center text-gray-500"
            >
                No hay goles que coincidan con la búsqueda.
            </div>
        </div>
    </AppLayout>
</template>
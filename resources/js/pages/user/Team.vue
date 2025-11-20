<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { dashboard } from '@/routes';
import { Button } from '@/components/ui/button';
import { route } from 'ziggy-js';

interface Team {
    id: number;
    name: string;
    city: string | null;
    stadium: string | null;
    capacity: number;
    players_count: number;
    founded_date: string | null;
}

const props = defineProps<{ teams: Team[] }>();

/* -------------------------
   🔍 Filtros inputs
--------------------------*/
const filterName = ref('');
const filterCity = ref('');
const filterStadium = ref('');

/* -------------------------
   🔍 Filtros aplicados (congelados)
--------------------------*/
const appliedName = ref('');
const appliedCity = ref('');
const appliedStadium = ref('');
const filtersApplied = ref(false);

/* -------------------------
   Normalizar texto
--------------------------*/
function normalize(str: string | null | undefined) {
    return String(str ?? '')
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase()
        .trim();
}

/* -------------------------
   🔎 Filtrado computado
--------------------------*/
const filtered = computed(() => {
    if (!filtersApplied.value) return props.teams;

    return props.teams.filter((t) => {
        const n = normalize(appliedName.value);
        const c = normalize(appliedCity.value);
        const s = normalize(appliedStadium.value);

        return (
            normalize(t.name).includes(n) &&
            normalize(t.city).includes(c) &&
            normalize(t.stadium).includes(s)
        );
    });
});

/* -------------------------
   🔘 Aplicar filtros
--------------------------*/
function applyFilters() {
    filtersApplied.value = true;
    appliedName.value = filterName.value;
    appliedCity.value = filterCity.value;
    appliedStadium.value = filterStadium.value;
}

/* -------------------------
   🧹 Limpiar filtros
--------------------------*/
function clearFilters() {
    filterName.value = '';
    filterCity.value = '';
    filterStadium.value = '';

    appliedName.value = '';
    appliedCity.value = '';
    appliedStadium.value = '';

    filtersApplied.value = false;
}

/* -------------------------
   📆 Fecha
--------------------------*/
function formatFounded(dateStr: string | null) {
    if (!dateStr) return '—';
    const d = new Date(dateStr);
    return `${d.getFullYear()}`;
}

const breadcrumbs = [{ title: 'Equipos', href: dashboard().url }];
</script>

<template>
    <Head title="Equipos" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-8">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-800">Equipos</h1>
                
                <a
                    :href="
                        route('export.excel', {
                            module: 'teams',
                            name: appliedName || undefined,
                            city: appliedCity || undefined,
                            stadium: appliedStadium || undefined,
                        })
                    "
                    target="_blank"
                >
                    <Button class="bg-red-600 text-white">
                        <i class="fa-solid fa-download"></i> Exportar Excel
                    </Button>
                </a>
            </div>

            <!-- ░░░░░ Filtros ░░░░░ -->
            <div class="bg-white border border-gray-200 dark:bg-gray-900 rounded-xl p-6 shadow-md">
                <h2 class="text-lg font-semibold mb-4">Filtros de Búsqueda</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Nombre</label>
                        <input
                            v-model="filterName"
                            class="mt-1 w-full border rounded-lg h-10 px-3"
                            placeholder="Buscar por nombre"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Ciudad</label>
                        <input
                            v-model="filterCity"
                            class="mt-1 w-full border rounded-lg h-10 px-3"
                            placeholder="Buscar por ciudad"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Estadio</label>
                        <input
                            v-model="filterStadium"
                            class="mt-1 w-full border rounded-lg h-10 px-3"
                            placeholder="Buscar por estadio"
                        />
                    </div>
                </div>

                <!-- BOTONES -->
                <div class="mt-6 flex gap-3">
                    <button
                        class="border-[#D62027] text-[#D62027] px-4 py-2 rounded-lg border hover:bg-red-50"
                        @click="clearFilters"
                    >
                        Limpiar
                    </button>

                    <button
                        class="bg-[#D62027] text-white px-4 py-2 rounded-lg hover:bg-red-700"
                        @click="applyFilters"
                    >
                        <i class="fa-solid fa-magnifying-glass"></i>
                        Buscar
                    </button>
                </div>
            </div>

            <!-- ░░░░░ Tarjetas de equipos ░░░░░ -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="team in filtered"
                    :key="team.id"
                    class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow hover:shadow-lg transition border border-gray-200"
                >
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-red-500 flex items-center justify-center text-white text-xl font-bold">
                            {{ team.name.charAt(0).toUpperCase() }}
                        </div>

                        <div>
                            <h2 class="text-xl font-bold">
                                <i class="fa-solid fa-shield"></i> {{ team.name }}
                            </h2>
                            <p class="text-gray-600">{{ team.city }}</p>
                            <p class="font-semibold">{{ team.stadium }}</p>
                        </div>
                    </div>

                    <hr class="my-4" />

                    <div class="grid grid-cols-3 text-center text-sm">
                        <div>
                            <p class="text-red-600 text-xl font-bold">{{ team.capacity.toLocaleString() }}</p>
                            <p class="text-gray-600">Capacidad</p>
                        </div>

                        <div>
                            <p class="text-red-600 text-xl font-bold">{{ formatFounded(team.founded_date) }}</p>
                            <p class="text-gray-600">Fundado</p>
                        </div>

                        <div>
                            <p class="text-red-600 text-xl font-bold">{{ team.players_count }}</p>
                            <p class="text-gray-600">Jugadores</p>
                        </div>
                    </div>
                </div>
            </div>

            <p
                v-if="filtered.length === 0"
                class="text-center text-gray-500 mt-10 text-lg"
            >
                No hay equipos que coincidan con la búsqueda.
            </p>
        </div>
    </AppLayout>
</template>
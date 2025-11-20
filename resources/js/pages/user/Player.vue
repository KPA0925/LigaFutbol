<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { route } from 'ziggy-js';
import { dashboard } from '@/routes';
import { Button } from '@/components/ui/button';

/* -------------------------
   🧾 Tipos
--------------------------*/
interface Player {
    id: number;
    fullname: string;
    birth_date: string;
    position: string;
    team?: { name: string } | null;
}

/* -------------------------
   📦 Props
--------------------------*/
const props = defineProps<{
    players: Player[];
}>();

/* -------------------------
   🔍 Filtros inputs
--------------------------*/
const filterName = ref('');
const filterBirth = ref('');
const filterPosition = ref('');
const filterTeam = ref('');

/* -------------------------
   🔍 Filtros aplicados (congelados)
--------------------------*/
const activeFilters = ref({
    name: '',
    birth: '',
    position: '',
    team: '',
});

const filtersEnabled = ref(false);

/* -------------------------
   🔎 Filtrado computado
--------------------------*/
const filtered = computed(() => {
    if (!filtersEnabled.value) return props.players;

    return props.players.filter((p) => {
        const matchesName = p.fullname
            .toLowerCase()
            .includes(activeFilters.value.name.toLowerCase());

        const matchesBirth =
            activeFilters.value.birth === '' ||
            p.birth_date.startsWith(activeFilters.value.birth);

        const matchesPosition = p.position
            .toLowerCase()
            .includes(activeFilters.value.position.toLowerCase());

        const matchesTeam = (p.team?.name ?? '')
            .toLowerCase()
            .includes(activeFilters.value.team.toLowerCase());

        return matchesName && matchesBirth && matchesPosition && matchesTeam;
    });
});

/* -------------------------
   🔘 Aplicar filtros
--------------------------*/
function applyFilters() {
    activeFilters.value = {
        name: filterName.value,
        birth: filterBirth.value,
        position: filterPosition.value,
        team: filterTeam.value,
    };
    filtersEnabled.value = true;
}

/* -------------------------
   🧹 Limpiar filtros
--------------------------*/
function clearFilters() {
    filterName.value = '';
    filterBirth.value = '';
    filterPosition.value = '';
    filterTeam.value = '';

    activeFilters.value = {
        name: '',
        birth: '',
        position: '',
        team: '',
    };

    filtersEnabled.value = false;
}

/* -------------------------
   🧭 Breadcrumbs
--------------------------*/
const breadcrumbs = [{ title: 'Jugadores', href: dashboard().url }];
</script>

<template>
    <Head title="Jugadores" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-8 p-6">
            <!-- 🧭 Título + Excel -->
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-800">Jugadores</h1>

                <a
                    :href="
                        route('export.excel', {
                            module: 'players',
                            name: activeFilters.name || undefined,
                            birth: activeFilters.birth || undefined,
                            position: activeFilters.position || undefined,
                            team: activeFilters.team || undefined,
                        })
                    "
                    target="_blank"
                >
                    <Button class="bg-red-600 text-white hover:bg-red-700">
                        <i class="fa-solid fa-download"></i> Exportar Excel
                    </Button>
                </a>
            </div>

            <!-- 🔍 Filtros -->
            <div class="rounded-xl border bg-white p-6 shadow-md">
                <h2 class="mb-4 text-lg font-semibold text-gray-800">
                    Filtros de búsqueda
                </h2>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
                    <div>
                        <label class="text-sm font-medium text-gray-600"
                            >Nombre</label
                        >
                        <input
                            v-model="filterName"
                            type="text"
                            class="mt-1 w-full rounded-lg border p-2"
                            placeholder="Buscar por nombre"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600"
                            >Cumpleaños</label
                        >
                        <input
                            v-model="filterBirth"
                            type="date"
                            class="mt-1 w-full rounded-lg border p-2"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600"
                            >Posición</label
                        >
                        <input
                            v-model="filterPosition"
                            type="text"
                            class="mt-1 w-full rounded-lg border p-2"
                            placeholder="Buscar por posición"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600"
                            >Equipo</label
                        >
                        <input
                            v-model="filterTeam"
                            type="text"
                            class="mt-1 w-full rounded-lg border p-2"
                            placeholder="Buscar por equipo"
                        />
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <Button
                        variant="outline"
                        class="border-red-600 text-red-600 hover:bg-red-50"
                        @click="clearFilters"
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

            <!-- 🎴 GRID DE TARJETAS -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="p in filtered"
                    :key="p.id"
                    class="flex flex-col rounded-2xl border bg-white p-6 shadow hover:shadow-lg transition"
                >
                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-16 w-16 items-center justify-center rounded-full bg-red-600 text-2xl font-bold text-white"
                        >
                            {{ p.fullname.charAt(0) }}
                        </div>

                        <div>
                            <h2 class="text-xl font-bold text-gray-900">
                                {{ p.fullname }}
                            </h2>
                            <span
                                class="inline-block mt-1 rounded-full bg-gray-200 px-3 py-1 text-sm font-medium text-gray-700"
                            >
                                {{ p.position }}
                            </span>
                        </div>
                    </div>

                    <div class="my-4 border-t"></div>

                    <div class="grid grid-cols-2 text-center text-gray-700">
                        <div>
                            <strong>{{ p.birth_date }}</strong>
                            <p class="text-xs text-gray-500">Nacimiento</p>
                        </div>

                        <div>
                            <strong>{{ p.team?.name ?? '—' }}</strong>
                            <p class="text-xs text-gray-500">Equipo</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sin resultados -->
            <p
                v-if="filtered.length === 0"
                class="text-center text-gray-500 mt-10 text-lg"
            >
                No hay jugadores que coincidan con la búsqueda.
            </p>
        </div>
    </AppLayout>
</template>
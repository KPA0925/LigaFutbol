<script setup lang="ts">
import ConfirmDeleteModal from '@/components/ConfirmDeleteModal.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { route } from 'ziggy-js';

interface Team {
    name: string;
}
interface Match {
    id: number;
    season: number;
    match_date_time: string;
    home_team?: Team | null;
    away_team?: Team | null;
    goal_home?: number | null;
    goal_away?: number | null;
}

const props = defineProps<{ matches: Match[] }>();

// ---------- MODAL ELIMINAR ----------
const showDeleteModal = ref(false);
const matchToDelete = ref<number | null>(null);

function openDeleteModal(id: number) {
    matchToDelete.value = id;
    showDeleteModal.value = true;
}

function confirmDelete() {
    if (matchToDelete.value !== null) {
        router.delete(route('admin.matches.destroy', matchToDelete.value));
    }
    showDeleteModal.value = false;
}

function cancelDelete() {
    showDeleteModal.value = false;
}

// ---------- Filtros ----------
const searchSeason = ref<number | null>(null);
const searchTeam = ref('');
const searchDate = ref<string>('');

const appliedSeason = ref<number | null>(null);
const appliedTeam = ref('');
const appliedDate = ref('');

function applySearch() {
    appliedSeason.value = searchSeason.value;
    appliedTeam.value = searchTeam.value.trim().toLowerCase();
    appliedDate.value = searchDate.value.trim(); // YYYY-MM-DD
    currentPage.value = 1;
}

function clearFilters() {
    searchSeason.value = null;
    searchTeam.value = '';
    searchDate.value = '';
    appliedSeason.value = null;
    appliedTeam.value = '';
    appliedDate.value = '';
    currentPage.value = 1;
}

// ---------- Filtrado ----------
const filtered = computed(() => {
    return props.matches.filter((m) => {
        let ok = true;

        if (appliedSeason.value !== null)
            ok = ok && Number(m.season) === Number(appliedSeason.value);

        if (appliedTeam.value) {
            const both =
                `${m.home_team?.name ?? ''} ${m.away_team?.name ?? ''}`.toLowerCase();
            ok = ok && both.includes(appliedTeam.value);
        }

        if (appliedDate.value) {
            const matchDate = new Date(m.match_date_time);
            const y = matchDate.getFullYear();
            const mth = String(matchDate.getMonth() + 1).padStart(2, '0');
            const d = String(matchDate.getDate()).padStart(2, '0');
            const matchFormatted = `${y}-${mth}-${d}`;
            ok = ok && matchFormatted === appliedDate.value;
        }

        return ok;
    });
});

// ---------- Utilidades ----------
function formatDate(datetime: string) {
    const d = new Date(datetime);

    const y = d.getFullYear();
    const m = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');

    const hh = String(d.getHours()).padStart(2, '0');
    const mm = String(d.getMinutes()).padStart(2, '0');

    return `${y}-${m}-${day} ${hh}:${mm}`;
}

function getMatchStatus(dateStr: string) {
    const now = new Date();
    const match = new Date(dateStr);
    const endMatch = new Date(match.getTime() + 2 * 60 * 60 * 1000);

    if (now < match) return 'Partido en espera';
    if (now >= match && now <= endMatch) return 'Partido en curso';
    return 'Partido terminado';
}

// ---------- Paginación ----------
const itemsPerPage = 5;
const currentPage = ref(1);

const paginated = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    return filtered.value.slice(start, start + itemsPerPage);
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Partidos', href: route('admin.matches.index') },
];
</script>

<template>
    <Head title="Partidos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <!-- MODAL GLOBAL -->
        <ConfirmDeleteModal
            :show="showDeleteModal"
            title="Eliminar Partido"
            message="¿Estás seguro de que deseas eliminar este partido? Esta acción no se puede deshacer."
            @confirm="confirmDelete"
            @cancel="cancelDelete"
        />

        <div class="space-y-8 p-6">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-800">
                    Partidos de la liga
                </h1>

                <Link :href="route('admin.matches.create')">
                    <Button class="bg-[#D62027] px-4 py-2 text-white">
                        <i class="fa-solid fa-circle-plus"></i> Crear
                    </Button>
                </Link>
            </div>

            <!-- Filtros -->
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
                        class="border-[#D62027] px-4 py-2 text-[#D62027]"
                        @click="clearFilters"
                    >
                        Limpiar
                    </Button>

                    <Button
                        class="bg-[#D62027] px-4 py-2 text-white"
                        @click="applySearch"
                    >
                        <i class="fa-solid fa-magnifying-glass"></i> Buscar
                    </Button>
                </div>
            </div>

            <!-- Tabla -->
            <div class="rounded-xl border bg-white p-6 shadow-md">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold">Listado de partidos</h2>

                    <!-- ✔ BOTÓN EXPORTAR EXCEL -->
                    <a
                        :href="route('admin.export.excel', {
                            module: 'matches',
                            season: appliedSeason || undefined,
                            team: appliedTeam || undefined,
                            date: appliedDate || undefined
                        })"
                        target="_blank"
                    >
                        <Button class="bg-[#D62027] px-4 py-2 text-white">
                            <i class="fa-solid fa-download"></i> Exportar Excel
                        </Button>
                    </a>
                </div>

                <div class="overflow-x-auto rounded-lg border">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-100 text-sm">
                                <th class="p-3">Fecha y Hora</th>
                                <th class="p-3">Temporada</th>
                                <th class="p-3">Equipos</th>
                                <th class="p-3">Marcador</th>
                                <th class="p-3">Estado</th>
                                <th class="p-3">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="match in paginated"
                                :key="match.id"
                                class="border-t hover:bg-gray-50"
                            >
                                <td class="p-3 text-center">
                                    {{ formatDate(match.match_date_time) }}
                                </td>

                                <td class="p-3 text-center">
                                    {{ match.season }}
                                </td>

                                <td class="p-3 text-center">
                                    {{ match.home_team?.name ?? '—' }}
                                    <strong>vs</strong>
                                    {{ match.away_team?.name ?? '—' }}
                                </td>

                                <td class="p-3 text-center">
                                    {{ match.goal_home }} -
                                    {{ match.goal_away }}
                                </td>

                                <td class="p-3 text-center font-semibold">
                                    {{ getMatchStatus(match.match_date_time) }}
                                </td>

                                <td class="flex justify-center gap-2 p-3">
                                    <Link
                                        :href="route('admin.matches.edit', match.id)"
                                    >
                                        <Button size="sm">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </Button>
                                    </Link>

                                    <Button
                                        size="sm"
                                        @click="openDeleteModal(match.id)"
                                    >
                                        <i class="fa-solid fa-delete-left"></i>
                                    </Button>
                                </td>
                            </tr>

                            <tr v-if="filtered.length === 0">
                                <td
                                    colspan="6"
                                    class="p-6 text-center text-gray-500"
                                >
                                    No hay resultados.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="mt-4 flex items-center justify-between text-sm">
                    <span>
                        Mostrando {{ paginated.length }} de
                        {{ filtered.length }}
                    </span>

                    <div class="flex gap-2">
                        <Button
                            size="sm"
                            variant="outline"
                            :disabled="currentPage === 1"
                            @click="currentPage--"
                        >
                            Anterior
                        </Button>

                        <Button
                            size="sm"
                            variant="outline"
                            :disabled="
                                currentPage * itemsPerPage >= filtered.length
                            "
                            @click="currentPage++"
                        >
                            Siguiente
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

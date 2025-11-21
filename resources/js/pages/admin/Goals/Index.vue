<script setup lang="ts">
import ConfirmDeleteModal from '@/components/ConfirmDeleteModal.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { route } from 'ziggy-js';

interface Goal {
    id: number;
    minute: number;
    description: string | null;
    player: { fullname: string; team: { name: string } | null };
    match: { season: string; match_date_time: string };
}

const props = defineProps<{
    goals: Goal[];
}>();

const filters = ref({
    season: '',
    team: '',
    player: '',
});

const appliedFilters = ref({
    season: '',
    team: '',
    player: '',
});

function applyFilters() {
    appliedFilters.value = { ...filters.value };
    currentPage.value = 1;
}

const itemsPerPage = 5;
const currentPage = ref(1);

const filtered = computed(() => {
    return props.goals.filter((g) => {
        return (
            g.match.season
                .toLowerCase()
                .includes(appliedFilters.value.season.toLowerCase()) &&
            (g.player.team?.name ?? '')
                .toLowerCase()
                .includes(appliedFilters.value.team.toLowerCase()) &&
            g.player.fullname
                .toLowerCase()
                .includes(appliedFilters.value.player.toLowerCase())
        );
    });
});

const paginated = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    return filtered.value.slice(start, start + itemsPerPage);
});

function resetFilters() {
    filters.value = {
        season: '',
        team: '',
        player: '',
    };
    appliedFilters.value = { ...filters.value };
    currentPage.value = 1;
}

const showDeleteModal = ref(false);
const goalToDelete = ref<number | null>(null);

function openDeleteModal(id: number) {
    goalToDelete.value = id;
    showDeleteModal.value = true;
}

function confirmDelete() {
    if (goalToDelete.value) {
        router.delete(route('admin.goals.destroy', goalToDelete.value));
    }
    showDeleteModal.value = false;
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Goles', href: route('admin.goals.index') },
];
</script>

<template>
    <Head title="Goles" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-8 p-6">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-800">
                    Registro de Goles
                </h1>

                <Link :href="route('admin.goals.create')">
                    <Button class="bg-[#D62027] px-4 py-2 text-white">
                        <i class="fa-solid fa-circle-plus"></i> Crear
                    </Button>
                </Link>
            </div>

            <div class="rounded-xl border bg-white p-6 shadow-md">
                <h2 class="mb-4 text-lg font-semibold text-gray-800">
                    Filtros de Búsqueda
                </h2>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div>
                        <label class="text-sm font-medium text-gray-600"
                            >Temporada</label
                        >
                        <input
                            v-model="filters.season"
                            type="text"
                            placeholder="Buscar temporada"
                            class="mt-1 w-full rounded-lg border-gray-300 p-2"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600"
                            >Equipo</label
                        >
                        <input
                            v-model="filters.team"
                            type="text"
                            placeholder="Buscar equipo"
                            class="mt-1 w-full rounded-lg border-gray-300 p-2"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600"
                            >Jugador</label
                        >
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
                        class="border-[#D62027] px-4 py-2 text-[#D62027]"
                        variant="outline"
                        @click="resetFilters"
                    >
                        Limpiar
                    </Button>

                    <Button
                        class="bg-[#D62027] px-4 py-2 text-white"
                        @click="applyFilters"
                    >
                        <i class="fa-solid fa-magnifying-glass"></i> Buscar
                    </Button>
                </div>
            </div>

            <div class="rounded-xl border bg-white p-6 shadow-md">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">
                        Listado de goles
                    </h2>

                    <a
                        :href="
                            route('admin.export.excel', {
                                module: 'goals',
                                season: appliedFilters.season || undefined,
                                team: appliedFilters.team || undefined,
                                player: appliedFilters.player || undefined,
                            })
                        "
                        target="_blank"
                    >
                        <Button class="bg-[#D62027] px-4 py-2 text-white">
                            <i class="fa-solid fa-download"></i> Exportar Excel
                        </Button>
                    </a>
                </div>

                <div class="overflow-x-auto rounded-lg border">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-sm text-gray-700">
                                <th class="p-3">Partido</th>
                                <th class="p-3">Equipo</th>
                                <th class="p-3">Jugador</th>
                                <th class="p-3">Minuto</th>
                                <th class="p-3">Descripción</th>
                                <th class="p-3 text-center">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="goal in paginated"
                                :key="goal.id"
                                class="border-t hover:bg-gray-50"
                            >
                                <td class="p-3 text-center">
                                    {{ goal.match.season }} —
                                    {{
                                        new Date(goal.match.match_date_time)
                                            .toISOString()
                                            .slice(0, 16)
                                            .replace('T', ' ')
                                    }}
                                </td>

                                <td class="p-3 text-center">
                                    {{ goal.player.team?.name ?? '—' }}
                                </td>

                                <td class="p-3 text-center">
                                    {{ goal.player.fullname }}
                                </td>

                                <td class="p-3 text-center font-semibold">
                                    {{ goal.minute }}’
                                </td>

                                <td
                                    class="p-3 text-center italic text-gray-700"
                                >
                                    {{ goal.description || '—' }}
                                </td>

                                <td class="flex justify-center gap-2 p-3">
                                    <Link
                                        :href="
                                            route('admin.goals.edit', goal.id)
                                        "
                                    >
                                        <Button size="sm">
                                            <i
                                                class="fa-regular fa-pen-to-square"
                                            ></i>
                                        </Button>
                                    </Link>

                                    <Button
                                        size="sm"
                                        @click="openDeleteModal(goal.id)"
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

                <div
                    class="mt-4 flex items-center justify-between text-sm text-gray-600"
                >
                    <span
                        >Mostrando {{ paginated.length }} de
                        {{ filtered.length }}</span
                    >

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

        <ConfirmDeleteModal
            :show="showDeleteModal"
            title="Eliminar gol"
            message="¿Seguro que deseas eliminar este gol?"
            @confirm="confirmDelete"
            @cancel="showDeleteModal = false"
        />
    </AppLayout>
</template>

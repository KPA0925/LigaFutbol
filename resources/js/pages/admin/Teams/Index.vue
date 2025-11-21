<script setup lang="ts">
import ConfirmDeleteModal from '@/components/ConfirmDeleteModal.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
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

const props = defineProps<{
    teams: Team[];
}>();

const filterName = ref('');
const filterCity = ref('');
const filterStadium = ref('');

const results = ref<Team[]>([...props.teams]);

const itemsPerPage = 5;
const currentPage = ref(1);

function normalize(str: string | null | undefined) {
    return String(str ?? '')
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase()
        .trim();
}

function formatFounded(dateStr: string | null) {
    if (!dateStr) return '—';
    const d = new Date(dateStr);

    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
}

function search() {
    results.value = props.teams.filter((t) => {
        const nameOK = normalize(t.name).includes(normalize(filterName.value));
        const cityOK = normalize(t.city).includes(normalize(filterCity.value));
        const stadiumOK = normalize(t.stadium).includes(
            normalize(filterStadium.value),
        );

        return nameOK && cityOK && stadiumOK;
    });

    currentPage.value = 1;
}

function clearFilters() {
    filterName.value = '';
    filterCity.value = '';
    filterStadium.value = '';

    results.value = [...props.teams];
    currentPage.value = 1;
}

const paginated = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    return results.value.slice(start, start + itemsPerPage);
});

const showDeleteModal = ref(false);
const teamToDelete = ref<number | null>(null);

function openDeleteModal(id: number) {
    teamToDelete.value = id;
    showDeleteModal.value = true;
}

function confirmDelete() {
    if (teamToDelete.value) {
        router.delete(route('admin.teams.destroy', teamToDelete.value), {
            preserveScroll: true,
            onSuccess: () => {
                results.value = results.value.filter(t => t.id !== teamToDelete.value);

                if ((currentPage.value - 1) * itemsPerPage >= results.value.length) {
                    currentPage.value = Math.max(1, currentPage.value - 1);
                }
            },
        });
    }
    showDeleteModal.value = false;
}

function cancelDelete() {
    showDeleteModal.value = false;
    teamToDelete.value = null;
}

const breadcrumbs = [{ title: 'Equipos', href: route('admin.teams.index') }];
</script>

<template>
    <Head title="Equipos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-8 p-6">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-800">
                    Equipos de la liga
                </h1>

                <Link :href="route('admin.teams.create')">
                    <Button class="bg-[#D62027] px-4 py-2 text-white">
                        <i class="fa-solid fa-circle-plus"></i> Crear
                    </Button>
                </Link>
            </div>

            <div
                class="rounded-xl border border-gray-200 bg-white p-6 shadow-md"
            >
                <h2 class="mb-4 text-lg font-semibold text-gray-800">
                    Filtros de Búsqueda
                </h2>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div class="flex flex-col">
                        <label class="text-sm font-medium text-gray-600"
                            >Nombre</label
                        >
                        <input
                            v-model="filterName"
                            type="text"
                            placeholder="Buscar nombre"
                            class="mt-1 h-11 w-full rounded-lg border-gray-300 p-2"
                        />
                    </div>

                    <div class="flex flex-col">
                        <label class="text-sm font-medium text-gray-600"
                            >Ciudad</label
                        >
                        <input
                            v-model="filterCity"
                            type="text"
                            placeholder="Buscar ciudad"
                            class="mt-1 h-11 w-full rounded-lg border-gray-300 p-2"
                        />
                    </div>

                    <div class="flex flex-col">
                        <label class="text-sm font-medium text-gray-600"
                            >Estadio</label
                        >
                        <input
                            v-model="filterStadium"
                            type="text"
                            placeholder="Buscar estadio"
                            class="mt-1 h-11 w-full rounded-lg border-gray-300 p-2"
                        />
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <Button
                        class="border-[#D62027] px-4 py-2 text-[#D62027]"
                        variant="outline"
                        @click="clearFilters"
                    >
                        Limpiar
                    </Button>

                    <Button
                        class="bg-[#D62027] px-4 py-2 text-white"
                        @click="search"
                    >
                        <i class="fa-solid fa-magnifying-glass"></i> Buscar
                    </Button>
                </div>
            </div>

            <div
                class="rounded-xl border border-gray-200 bg-white p-6 shadow-md"
            >
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">
                        Listado de equipos
                    </h2>

                    <a
                        :href="
                            route('admin.export.excel', {
                                module: 'teams',
                                name: filterName || undefined,
                                city: filterCity || undefined,
                                stadium: filterStadium || undefined,
                            })
                        "
                        target="_blank"
                    >
                        <Button class="bg-[#D62027] px-4 py-2 text-white">
                            <i class="fa-solid fa-download"></i> Descargar Excel
                        </Button>
                    </a>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-sm text-gray-700">
                                <th class="p-3">Nombre</th>
                                <th class="p-3">Ciudad</th>
                                <th class="p-3">Estadio</th>
                                <th class="p-3">Capacidad</th>
                                <th class="p-3">Fundado</th>
                                <th class="p-3">Jugadores</th>
                                <th class="p-3">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="team in paginated"
                                :key="team.id"
                                class="border-t hover:bg-gray-50"
                            >
                                <td class="p-3 text-center">{{ team.name }}</td>
                                <td class="p-3 text-center">{{ team.city }}</td>
                                <td class="p-3 text-center">
                                    {{ team.stadium }}
                                </td>
                                <td class="p-3 text-center">
                                    {{ team.capacity }}
                                </td>

                                <td class="p-3 text-center">
                                    {{ formatFounded(team.founded_date) }}
                                </td>

                                <td class="p-3 text-center">
                                    {{ team.players_count }}
                                </td>

                                <td class="flex justify-center gap-2 p-3">
                                    <Link
                                        :href="
                                            route('admin.teams.edit', team.id)
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
                                        @click="openDeleteModal(team.id)"
                                    >
                                        <i class="fa-solid fa-delete-left"></i>
                                    </Button>
                                </td>
                            </tr>

                            <tr v-if="results.length === 0">
                                <td
                                    colspan="7"
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
                    <span>
                        Mostrando {{ paginated.length }} de {{ results.length }}
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
                                currentPage * itemsPerPage >= results.length
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

    <ConfirmDeleteModal
        :show="showDeleteModal"
        title="Eliminar equipo"
        message="¿Estás seguro de que deseas eliminar este equipo? Esta acción no se puede deshacer."
        @confirm="confirmDelete"
        @cancel="cancelDelete"
    />
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { route } from 'ziggy-js';

interface Team {
    id: number;
    name: string;
    city: string;
    stadium: string;
    capacity: number;
    players_count: number;
}

const props = defineProps<{
    teams: Team[];
}>();

/* -------------------------
   🔍 Filtros
--------------------------*/
const filterName = ref('');
const filterCity = ref('');
const filterStadium = ref('');
const filterPlayers = ref('');

/* -------------------------
  🔎 Filtro principal
--------------------------*/
const filtered = computed(() => {
    return props.teams.filter((t) => {
        return (
            t.name.toLowerCase().includes(filterName.value.toLowerCase()) &&
            t.city.toLowerCase().includes(filterCity.value.toLowerCase()) &&
            t.stadium
                .toLowerCase()
                .includes(filterStadium.value.toLowerCase()) &&
            (filterPlayers.value === '' ||
                String(t.players_count).includes(filterPlayers.value))
        );
    });
});

/* -------------------------
   📄 Paginación
--------------------------*/
const itemsPerPage = 10;
const currentPage = ref(1);

const paginated = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    return filtered.value.slice(start, start + itemsPerPage);
});

/* -------------------------
   ❌ Eliminar
--------------------------*/
function destroyTeam(id: number) {
    if (confirm('¿Seguro que deseas eliminar este equipo?')) {
        router.delete(route('admin.teams.destroy', id));
    }
}

const breadcrumbs = [
    { title: 'Equipos', href: route('admin.teams.index') },
];
</script>

<template>
    <Head title="Equipos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-8 p-6">
            <!-- 🧭 Encabezado -->
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-800">
                    Equipos de la liga
                </h1>

                <Link :href="route('admin.teams.create')">
                    <Button class="px-4 py-2  bg-[#D62027] text-white" variant="outline"><i class="fa-solid fa-circle-plus"></i>Crear</Button>
                </Link>
            </div>

            <!-- 🔍 Card de Filtros -->
            <div
                class="rounded-xl border border-gray-200 bg-white p-6 shadow-md"
            >
                <h2 class="mb-4 text-lg font-semibold text-gray-800">
                    Filtros de búsqueda
                </h2>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div>
                        <label class="text-sm font-medium text-gray-600"
                            >Nombre</label
                        >
                        <input
                            v-model="filterName"
                            type="text"
                            placeholder="Buscar por nombre"
                            class="mt-1 w-full rounded-lg border-gray-300 p-2"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600"
                            >Ciudad</label
                        >
                        <input
                            v-model="filterCity"
                            type="text"
                            placeholder="Buscar por ciudad"
                            class="mt-1 w-full rounded-lg border-gray-300 p-2"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600"
                            >Estadio</label
                        >
                        <input
                            v-model="filterStadium"
                            type="text"
                            placeholder="Buscar por estadio"
                            class="mt-1 w-full rounded-lg border-gray-300 p-2"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600"
                            >Jugadores</label
                        >
                        <input
                            v-model="filterPlayers"
                            type="number"
                            placeholder="Buscar por jugadores"
                            class="mt-1 w-full rounded-lg border-gray-300 p-2"
                        />
                    </div>
                </div>

                <!-- Botones -->
                <div class="mt-6 flex gap-3">
                    <Button
                    class="px-4 py-2 text-[#D62027] border-[#D62027]"
                        variant="outline"
                        @click="
                            filterName = '';
                            filterCity = '';
                            filterStadium = '';
                            filterPlayers = '';
                        "
                    >
                        Limpiar
                    </Button>

                    <Button class="px-4 py-2 bg-[#D62027] text-white" ><i class="fa-solid fa-magnifying-glass"></i>Buscar</Button>
                </div>
            </div>

            <!-- 📋 Card listado -->
            <div
                class="rounded-xl border border-gray-200 bg-white p-6 shadow-md"
            >
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">
                        Listado de equipos
                    </h2>

                    <Button class="px-4 py-2 bg-[#D62027] text-white" variant="outline"><i class="fa-solid fa-download"></i>Descargar Excel</Button>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-sm text-gray-700">
                                <th class="p-3">Nombre</th>
                                <th class="p-3">Ciudad</th>
                                <th class="p-3">Estadio</th>
                                <th class="p-3">Capacidad</th>
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
                                <td class="p-3 text-center">{{ team.stadium }}</td>
                                <td class="p-3 text-center">{{ team.capacity }}</td>
                                <td class="p-3 text-center">{{ team.players_count }}</td>
                                <td class="flex justify-center gap-2 p-3">
                                    <Link
                                        :href="
                                            route('admin.teams.edit', team.id)
                                        "
                                    >
                                        <Button size="sm">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </Button>
                                    </Link>

                                    <Button
                                        size="sm"
                                        @click="destroyTeam(team.id)"
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

                <!-- Pagination -->
                <div
                    class="mt-4 flex items-center justify-between text-sm text-gray-600"
                >
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

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { route } from 'ziggy-js';

interface Team {
    name: string;
}

interface Match {
    id: number;
    season: string;
    match_date_time: string;
    home_team?: Team | null;
    away_team?: Team | null;
    goal_home?: number | null;
    goal_away?: number | null;
}

const props = defineProps<{
    matches: Match[];
}>();

/* -------------------------
   🟦 Filtros
--------------------------*/
const searchSeason = ref('');
const searchTeam = ref('');
const searchDate = ref('');

/* -------------------------
   🔍 Búsqueda y filtrado
--------------------------*/
const filtered = computed(() => {
    return props.matches.filter((m) => {
        const text =
            `${m.season} ${m.home_team?.name} ${m.away_team?.name} ${m.match_date_time}`.toLowerCase();

        return (
            text.includes(searchSeason.value.toLowerCase()) &&
            text.includes(searchTeam.value.toLowerCase()) &&
            text.includes(searchDate.value.toLowerCase())
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
   ❌ Eliminar partido
--------------------------*/
function destroyMatch(id: number) {
    if (confirm('¿Seguro que deseas eliminar este partido?')) {
        router.delete(route('admin.matches.destroy', id));
    }
}
</script>

<template>
    <Head title="Partidos" />

    <AppLayout>
        <div class="space-y-8 p-6">
            <!-- 🧭 Encabezado -->
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-800">
                    Partidos de la liga
                </h1>

                <Link :href="route('admin.matches.create')">
                    <Button class="px-4 py-2  bg-[#D62027] text-white" variant="outline"><i class="fa-solid fa-circle-plus"></i>Crear</Button>
                </Link>
            </div>

            <!-- 🔍 Card de Filtros -->
            <div
                class="rounded-xl border border-gray-200 bg-white p-6 shadow-md"
            >
                <h2 class="mb-4 text-lg font-semibold text-gray-800">
                    Filtros de Búsqueda
                </h2>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div>
                        <label class="text-sm font-medium text-gray-600"
                            >Temporada</label
                        >
                        <input
                            v-model="searchSeason"
                            type="text"
                            placeholder="Buscar por temporada"
                            class="mt-1 w-full rounded-lg border-gray-300 p-2"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600"
                            >Equipo</label
                        >
                        <input
                            v-model="searchTeam"
                            type="text"
                            placeholder="Buscar por equipo"
                            class="mt-1 w-full rounded-lg border-gray-300 p-2"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600"
                            >Fecha</label
                        >
                        <input
                            v-model="searchDate"
                            type="text"
                            placeholder="Buscar por fecha"
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
                            searchSeason = '';
                            searchTeam = '';
                            searchDate = '';
                        "
                        >Limpiar</Button
                    >

                    <Button class="px-4 py-2 bg-[#D62027] text-white" ><i class="fa-solid fa-magnifying-glass"></i>Buscar</Button>
                </div>
            </div>

            <!-- 📋 Card Listado -->
            <div
                class="rounded-xl border border-gray-200 bg-white p-6 shadow-md"
            >
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">
                        Listado de partidos
                    </h2>

                    <Button class="px-4 py-2 bg-[#D62027] text-white" variant="outline"><i class="fa-solid fa-download"></i>Descargar Excel</Button>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-sm text-gray-700">
                                <th class="p-3">Fecha y Hora</th>
                                <th class="p-3">Temporada</th>
                                <th class="p-3">Equipos</th>
                                <th class="p-3">Marcador</th>
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
                                    {{
                                        new Date(
                                            match.match_date_time,
                                        ).toLocaleString()
                                    }}
                                </td>

                                <td class="p-3 text-center">{{ match.season }}</td>

                                <td class="p-3 text-center">
                                    {{ match.home_team?.name ?? '—' }}
                                    <strong>vs</strong>
                                    {{ match.away_team?.name ?? '—' }}
                                </td>

                                <td class="p-3 text-center">
                                    {{ match.goal_home ?? '-' }} -
                                    {{ match.goal_away ?? '-' }}
                                </td>

                                <td class="flex justify-center gap-2 p-3">
                                    <Link
                                        :href="
                                            route(
                                                'admin.matches.edit',
                                                match.id,
                                            )
                                        "
                                    >
                                        <Button size="sm"
                                            ><i class="fa-regular fa-pen-to-square"></i></Button
                                        >
                                    </Link>

                                    <Button
                                        size="sm"
                                        @click="destroyMatch(match.id)"
                                    >
                                        <i class="fa-solid fa-delete-left"></i>
                                    </Button>
                                </td>
                            </tr>

                            <tr v-if="filtered.length === 0">
                                <td
                                    colspan="5"
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

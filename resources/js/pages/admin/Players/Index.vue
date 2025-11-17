<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { route } from 'ziggy-js';

/* -------------------------------
   🔹 Tipos
--------------------------------*/
interface Player {
    id: number;
    fullname: string;
    birth_date: string;
    position: string;
    team?: { name: string } | null;
}

/* -------------------------------
   🔹 Props
--------------------------------*/
const props = defineProps<{
    players: Player[];
}>();

/* -------------------------------
   🔹 Filtros de búsqueda
--------------------------------*/
const filterName = ref('');
const filterBirth = ref('');
const filterPosition = ref('');
const filterTeam = ref('');

/* -------------------------------
   🔎 Filtrado
--------------------------------*/
const filtered = computed(() => {
    return props.players.filter((p) => {
        return (
            p.fullname.toLowerCase().includes(filterName.value.toLowerCase()) &&
            p.birth_date
                .toLowerCase()
                .includes(filterBirth.value.toLowerCase()) &&
            p.position
                .toLowerCase()
                .includes(filterPosition.value.toLowerCase()) &&
            (p.team?.name ?? '')
                .toLowerCase()
                .includes(filterTeam.value.toLowerCase())
        );
    });
});

/* -------------------------------
   🔹 Paginación
--------------------------------*/
const itemsPerPage = 10;
const currentPage = ref(1);

const paginated = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    return filtered.value.slice(start, start + itemsPerPage);
});

/* -------------------------------
   ❌ Eliminar jugador
--------------------------------*/
function destroyPlayer(id: number) {
    if (confirm('¿Seguro que deseas eliminar este jugador?')) {
        router.delete(route('admin.players.destroy', id));
    }
}
</script>

<template>
    <Head title="Jugadores" />

    <AppLayout>
        <div class="space-y-8 p-6">
            <!-- 🧭 Encabezado -->
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-800">
                    Jugadores de la liga
                </h1>

                <Link :href="route('admin.players.create')">
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

                <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
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
                            >Cumpleaños</label
                        >
                        <input
                            v-model="filterBirth"
                            type="text"
                            placeholder="Buscar por fecha"
                            class="mt-1 w-full rounded-lg border-gray-300 p-2"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600"
                            >Posición</label
                        >
                        <input
                            v-model="filterPosition"
                            type="text"
                            placeholder="Buscar por posición"
                            class="mt-1 w-full rounded-lg border-gray-300 p-2"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600"
                            >Equipo</label
                        >
                        <input
                            v-model="filterTeam"
                            type="text"
                            placeholder="Buscar por equipo"
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
                            filterBirth = '';
                            filterPosition = '';
                            filterTeam = '';
                        "
                    >
                        Limpiar
                    </Button>

                    <Button class="px-4 py-2 bg-[#D62027] text-white" ><i class="fa-solid fa-magnifying-glass"></i>Buscar</Button>
                </div>
            </div>

            <!-- 📋 Card del listado -->
            <div
                class="rounded-xl border border-gray-200 bg-white p-6 shadow-md"
            >
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">
                        Listado de jugadores
                    </h2>

                    <Button class="px-4 py-2 bg-[#D62027] text-white" variant="outline"><i class="fa-solid fa-download"></i>Descargar Excel</Button>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-sm text-gray-700">
                                <th class="p-3">Nombre Completo</th>
                                <th class="p-3">Cumpleaños</th>
                                <th class="p-3">Posición</th>
                                <th class="p-3">Equipo</th>
                                <th class="p-3">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="player in paginated"
                                :key="player.id"
                                class="border-t hover:bg-gray-50"
                            >
                                <td class="p-3 text-center">{{ player.fullname }}</td>
                                <td class="p-3 text-center">{{ player.birth_date }}</td>
                                <td class="p-3 text-center">{{ player.position }}</td>
                                <td class="p-3 text-center">
                                    {{ player.team?.name ?? 'Sin equipo' }}
                                </td>

                                <td class="flex justify-center gap-2 p-3">
                                    <Link
                                        :href="
                                            route(
                                                'admin.players.edit',
                                                player.id,
                                            )
                                        "
                                    >
                                        <Button size="sm"
                                            ><i class="fa-regular fa-pen-to-square"></i></Button
                                        >
                                    </Link>

                                    <Button
                                        size="sm"
                                        @click="destroyPlayer(player.id)"
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

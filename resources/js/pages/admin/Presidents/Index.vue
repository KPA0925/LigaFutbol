<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { route } from 'ziggy-js';

interface President {
    id: number;
    DNI: string;
    name: string;
    lastname: string;
    birth_date: string;
    elected_date: string;
    team?: { name: string };
}

const props = defineProps<{
    presidents: President[];
}>();

/* -------------------------
   🟦 Filtros
--------------------------*/
const filterDNI = ref('');
const filterName = ref('');
const filterLastname = ref('');
const filterBirth = ref('');
const filterElected = ref('');
const filterTeam = ref('');

/* -------------------------
   🔍 Búsqueda y filtrado
--------------------------*/
const filtered = computed(() => {
    return props.presidents.filter((p) => {
        const text =
            `${p.DNI} ${p.name} ${p.lastname} ${p.team?.name ?? ''} ${p.birth_date} ${p.elected_date}`.toLowerCase();

        return (
            text.includes(filterDNI.value.toLowerCase()) &&
            text.includes(filterName.value.toLowerCase()) &&
            text.includes(filterLastname.value.toLowerCase()) &&
            text.includes(filterBirth.value.toLowerCase()) &&
            text.includes(filterElected.value.toLowerCase()) &&
            text.includes(filterTeam.value.toLowerCase())
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
function destroyPresident(id: number) {
    if (confirm('¿Seguro que deseas eliminar este presidente?')) {
        router.delete(route('admin.presidents.destroy', id));
    }
}
</script>

<template>
    <Head title="Presidentes" />

    <AppLayout>
        <div class="space-y-8 p-6">
            <!-- 🧭 Encabezado -->
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-800">
                    Presidentes de la liga
                </h1>

                <Link :href="route('admin.presidents.create')">
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
                            >DNI</label
                        >
                        <input
                            v-model="filterDNI"
                            type="text"
                            placeholder="Buscar por DNI"
                            class="mt-1 w-full rounded-lg border-gray-300 p-2"
                        />
                    </div>

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
                            >Apellido</label
                        >
                        <input
                            v-model="filterLastname"
                            type="text"
                            placeholder="Buscar por apellido"
                            class="mt-1 w-full rounded-lg border-gray-300 p-2"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600"
                            >Nacimiento</label
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
                            >Elegido</label
                        >
                        <input
                            v-model="filterElected"
                            type="text"
                            placeholder="Buscar por fecha"
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
                            filterDNI = '';
                            filterName = '';
                            filterLastname = '';
                            filterBirth = '';
                            filterElected = '';
                            filterTeam = '';
                        "
                    >
                        Limpiar
                    </Button>

                    <Button class="px-4 py-2 bg-[#D62027] text-white" ><i class="fa-solid fa-magnifying-glass"></i>Buscar</Button>
                </div>
            </div>

            <!-- 📋 Card Listado -->
            <div
                class="rounded-xl border border-gray-200 bg-white p-6 shadow-md"
            >
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">
                        Listado de presidentes
                    </h2>

                    <Button class="px-4 py-2 bg-[#D62027] text-white" variant="outline"><i class="fa-solid fa-download"></i>Descargar Excel</Button>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-sm text-gray-700">
                                <th class="p-3">DNI</th>
                                <th class="p-3">Nombre</th>
                                <th class="p-3">Nacimiento</th>
                                <th class="p-3">Elegido</th>
                                <th class="p-3">Equipo</th>
                                <th class="p-3 text-center">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="p in paginated"
                                :key="p.id"
                                class="border-t hover:bg-gray-50"
                            >
                                <td class="p-3 text-center">{{ p.DNI }}</td>
                                <td class="p-3 text-center">
                                    {{ p.name }} {{ p.lastname }}
                                </td>
                                <td class="p-3 text-center">{{ p.birth_date }}</td>
                                <td class="p-3 text-center">{{ p.elected_date }}</td>
                                <td class="p-3 text-center">
                                    {{ p.team?.name ?? 'Sin asignar' }}
                                </td>

                                <td class="flex justify-center gap-2 p-3">
                                    <Link
                                        :href="
                                            route('admin.presidents.edit', p.id)
                                        "
                                    >
                                        <Button size="sm"
                                            ><i class="fa-regular fa-pen-to-square"></i></Button
                                        >
                                    </Link>

                                    <Button
                                        size="sm"
                                        @click="destroyPresident(p.id)"
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
    </AppLayout>
</template>

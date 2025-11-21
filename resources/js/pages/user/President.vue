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
}

interface President {
    id: number;
    dni: string;
    name: string;
    lastname: string;
    birth_date: string;
    elected_date: string;
    team?: Team | null;
}

const props = defineProps<{
    presidents: President[];
}>();

const filterDni = ref('');
const filterName = ref('');
const filterLastname = ref('');
const filterTeam = ref('');

const activeFilters = ref({
    dni: '',
    name: '',
    lastname: '',
    team: '',
});

const appliedFilters = ref(false);

const filtered = computed(() => {
    if (!appliedFilters.value) return props.presidents;

    const f = activeFilters.value;

    return props.presidents.filter((p) => {
        return (
            (f.dni === '' || p.dni.includes(f.dni)) &&
            (f.name === '' ||
                p.name.toLowerCase().includes(f.name.toLowerCase())) &&
            (f.lastname === '' ||
                p.lastname.toLowerCase().includes(f.lastname.toLowerCase())) &&
            (f.team === '' ||
                (p.team?.name ?? '')
                    .toLowerCase()
                    .includes(f.team.toLowerCase()))
        );
    });
});

function applyFilters() {
    appliedFilters.value = true;

    activeFilters.value = {
        dni: filterDni.value,
        name: filterName.value,
        lastname: filterLastname.value,
        team: filterTeam.value,
    };
}

function clearFilters() {
    filterDni.value = '';
    filterName.value = '';
    filterLastname.value = '';
    filterTeam.value = '';

    activeFilters.value = {
        dni: '',
        name: '',
        lastname: '',
        team: '',
    };

    appliedFilters.value = false;
}

const breadcrumbs = [{ title: 'Presidentes', href: dashboard().url }];
</script>

<template>
    <Head title="Presidentes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-8 p-6">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-800">
                    Presidentes de los Equipos
                </h1>

                <a
                    :href="
                        route('export.excel', {
                            module: 'presidents',
                            dni: activeFilters.dni || undefined,
                            name: activeFilters.name || undefined,
                            lastname: activeFilters.lastname || undefined,
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

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow">
                <h2 class="mb-4 text-lg font-semibold text-gray-800">
                    Filtros de búsqueda
                </h2>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
                    <div>
                        <label class="text-sm font-medium text-gray-600">DNI</label>
                        <input
                            v-model="filterDni"
                            type="text"
                            placeholder="Buscar DNI"
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
                            placeholder="Buscar nombre"
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
                            placeholder="Buscar apellido"
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
                            placeholder="Buscar equipo"
                            class="mt-1 w-full rounded-lg border-gray-300 p-2"
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

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="p in filtered"
                        :key="p.id"
                        class="rounded-2xl bg-white p-6 shadow hover:shadow-lg transition border border-gray-100"
                    >
                        <div class="flex items-center gap-4">
                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-full bg-red-600 text-white text-xl"
                            >
                                <i class="fa-solid fa-crown"></i>
                            </div>

                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">
                                    {{ p.name }} {{ p.lastname }}
                                </h2>
                                <p class="text-sm text-gray-500">
                                    <i class="fa-solid fa-shield"></i>
                                    {{ p.team?.name ?? 'Sin equipo' }}
                                </p>
                            </div>
                        </div>

                        <hr class="my-4" />

                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div>
                                <p class="text-lg font-bold">{{ p.birth_date }}</p>
                                <p class="text-xs text-gray-500">Nacimiento</p>
                            </div>

                            <div>
                                <p class="text-lg font-bold">{{ p.elected_date }}</p>
                                <p class="text-xs text-gray-500">Elegido</p>
                            </div>

                            <div>
                                <p class="text-lg font-bold">{{ p.dni }}</p>
                                <p class="text-xs text-gray-500">DNI</p>
                            </div>
                        </div>
                    </div>

                    <p
                        v-if="filtered.length === 0"
                        class="col-span-full text-center text-gray-500 py-5"
                    >
                        No hay resultados.
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
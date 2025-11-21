<script setup lang="ts">
import ConfirmDeleteModal from '@/components/ConfirmDeleteModal.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { route } from 'ziggy-js';

interface Comment {
    id: number;
    description: string;
    created_at: string;
    user?: { name: string } | null;
}

const props = defineProps<{
    comments: Comment[];
}>();

const searchUser = ref('');
const searchDescription = ref('');

const appliedFilters = ref({
    user: '',
    desc: '',
});

function formatDate(dateStr: string) {
    const date = new Date(dateStr);

    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');

    return `${year}-${month}-${day} ${hours}:${minutes}`;
}

const filtered = computed(() => {
    return props.comments.filter((c) => {
        return (
            (appliedFilters.value.user === '' ||
                (c.user?.name ?? '')
                    .toLowerCase()
                    .includes(appliedFilters.value.user.toLowerCase())) &&
            (appliedFilters.value.desc === '' ||
                c.description
                    .toLowerCase()
                    .includes(appliedFilters.value.desc.toLowerCase()))
        );
    });
});

const itemsPerPage = 5;
const currentPage = ref(1);

const paginated = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    return filtered.value.slice(start, start + itemsPerPage);
});

function applyFilters() {
    appliedFilters.value = {
        user: searchUser.value,
        desc: searchDescription.value,
    };

    currentPage.value = 1;
}

function clearFilters() {
    searchUser.value = '';
    searchDescription.value = '';

    appliedFilters.value = {
        user: '',
        desc: '',
    };

    currentPage.value = 1;
}

const showDeleteModal = ref(false);
const deletingId = ref<number | null>(null);

function openDelete(id: number) {
    deletingId.value = id;
    showDeleteModal.value = true;
}

function confirmDelete() {
    if (deletingId.value) {
        router.delete(route('admin.comments.destroy', deletingId.value));
    }
    showDeleteModal.value = false;
}

function cancelDelete() {
    showDeleteModal.value = false;
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Comentarios', href: route('admin.comments.index') },
];
</script>

<template>
    <Head title="Comentarios" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-8 p-6">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-800">Comentarios</h1>

                <Link :href="route('admin.comments.create')">
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

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-gray-600"
                            >Usuario</label
                        >
                        <input
                            v-model="searchUser"
                            type="text"
                            placeholder="Buscar por usuario"
                            class="mt-1 w-full rounded-lg border-gray-300 p-2"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600"
                            >Descripción</label
                        >
                        <input
                            v-model="searchDescription"
                            type="text"
                            placeholder="Buscar por descripción"
                            class="mt-1 w-full rounded-lg border-gray-300 p-2"
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
                        @click="applyFilters"
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
                        Listado de comentarios
                    </h2>

                    <a
                        :href="
                            route('admin.export.excel', {
                                module: 'comments',
                                user: appliedFilters.user || undefined,
                                desc: appliedFilters.desc || undefined,
                            })
                        "
                        target="_blank"
                    >
                        <Button class="bg-[#D62027] px-4 py-2 text-white">
                            <i class="fa-solid fa-download"></i> Exportar Excel
                        </Button>
                    </a>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-sm text-gray-700">
                                <th class="p-3">Usuario</th>
                                <th class="p-3">Descripción</th>
                                <th class="p-3">Fecha</th>
                                <th class="p-3">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="c in paginated"
                                :key="c.id"
                                class="border-t hover:bg-gray-50"
                            >
                                <td class="p-3 text-center">
                                    {{ c.user?.name ?? 'Usuario eliminado' }}
                                </td>

                                <td class="p-3 text-center">
                                    {{ c.description }}
                                </td>

                                <td class="p-3 text-center">
                                    {{ formatDate(c.created_at) }}
                                </td>

                                <td class="flex justify-center gap-2 p-3">
                                    <Link
                                        :href="
                                            route('admin.comments.edit', c.id)
                                        "
                                    >
                                        <Button size="sm">
                                            <i
                                                class="fa-regular fa-pen-to-square"
                                            ></i>
                                        </Button>
                                    </Link>

                                    <Button size="sm" @click="openDelete(c.id)">
                                        <i class="fa-solid fa-delete-left"></i>
                                    </Button>
                                </td>
                            </tr>

                            <tr v-if="filtered.length === 0">
                                <td
                                    colspan="4"
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

        <ConfirmDeleteModal
            :show="showDeleteModal"
            title="Eliminar comentario"
            message="¿Estás seguro de que deseas eliminar este comentario?"
            @confirm="confirmDelete"
            @cancel="cancelDelete"
        />
    </AppLayout>
</template>

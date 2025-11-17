<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
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

/* -------------------------
   🟦 Filtros
--------------------------*/
const searchUser = ref('');
const searchDescription = ref('');
const searchDate = ref('');

/* -------------------------
   🔍 Filtrado
--------------------------*/
const filtered = computed(() => {
    return props.comments.filter((c) => {
        const textFull =
            `${c.user?.name ?? ''} ${c.description} ${c.created_at}`.toLowerCase();

        return (
            textFull.includes(searchUser.value.toLowerCase()) &&
            textFull.includes(searchDescription.value.toLowerCase()) &&
            textFull.includes(searchDate.value.toLowerCase())
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
   🕒 Formatear fecha
--------------------------*/
function formatDate(dateStr: string) {
    const date = new Date(dateStr);
    return date.toLocaleString('es-CO', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: true,
    });
}

/* -------------------------
   ❌ Eliminar
--------------------------*/
function destroyComment(id: number) {
    if (confirm('¿Seguro que deseas eliminar este comentario?')) {
        router.delete(route('admin.comments.destroy', id));
    }
}
</script>

<template>
    <Head title="Comentarios" />

    <AppLayout>
        <div class="space-y-8 p-6">
            <!-- 🧭 Encabezado -->
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-800">Comentarios</h1>

                <Link :href="route('admin.comments.create')">
                    <Button class="px-4 py-2  bg-[#D62027] text-white" variant="outline"><i class="fa-solid fa-circle-plus"></i>Crear</Button>
                </Link>
            </div>

            <!-- 🔍 Card Filtros -->
            <div
                class="rounded-xl border border-gray-200 bg-white p-6 shadow-md"
            >
                <h2 class="mb-4 text-lg font-semibold text-gray-800">
                    Filtros de Búsqueda
                </h2>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
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
                            searchUser = '';
                            searchDescription = '';
                            searchDate = '';
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
                        Listado de comentarios
                    </h2>

                    <Button class="px-4 py-2 bg-[#D62027] text-white" variant="outline"><i class="fa-solid fa-download"></i>Descargar Excel</Button>
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
                                        <Button size="sm"
                                            ><i class="fa-regular fa-pen-to-square"></i></Button
                                        >
                                    </Link>

                                    <Button
                                        size="sm"
                                        @click="destroyComment(c.id)"
                                    >
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

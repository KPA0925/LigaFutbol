<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

defineProps<{
    comments: {
        id: number;
        description: string;
        created_at: string;
        user?: { name: string } | null;
    }[];
}>();

// Formatear fecha
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

function destroyComment(id: number) {
    if (confirm('¿Seguro que deseas eliminar este comentario?')) {
        router.delete(route('admin.comments.destroy', id));
    }
}
</script>

<template>
    <Head title="Comentarios" />

    <AppLayout>
        <div class="p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold">💬 Comentarios</h1>

                <Link :href="route('admin.comments.create')">
                    <Button>➕ Nuevo Comentario</Button>
                </Link>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                <Card v-for="c in comments" :key="c.id">
                    <CardContent class="space-y-2 p-4">
                        <p>
                            <strong>Usuario:</strong>
                            {{ c.user?.name ?? 'Usuario eliminado' }}
                        </p>

                        <p>
                            <strong>Descripción:</strong>
                            {{ c.description }}
                        </p>

                        <p class="text-sm text-gray-500">
                            <strong>Fecha:</strong>
                            {{ formatDate(c.created_at) }}
                        </p>

                        <div class="flex justify-between pt-2">
                            <Link :href="route('admin.comments.edit', c.id)">
                                <Button variant="outline" size="sm"
                                    >Editar</Button
                                >
                            </Link>

                            <Button
                                size="sm"
                                @click="destroyComment(c.id)"
                            >
                                Eliminar
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

defineProps<{
    presidents: {
        id: number;
        DNI: string;
        name: string;
        lastname: string;
        birth_date: string;
        elected_date: string;
        team?: { name: string };
    }[];
}>();

function destroyPresident(id: number) {
    if (confirm('¿Seguro que deseas eliminar este presidente?')) {
        router.delete(route('admin.presidents.destroy', id));
    }
}
</script>

<template>
    <Head title="Presidentes" />

    <AppLayout>
        <div class="p-6">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold">🏛️ Presidentes</h1>

                <Link :href="route('admin.presidents.create')">
                    <Button>➕ Nuevo Presidente</Button>
                </Link>
            </div>

            <!-- Grid de tarjetas -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                <Card v-for="p in presidents" :key="p.id">
                    <CardContent class="space-y-3 p-4">
                        <!-- Nombre -->
                        <h2 class="text-lg font-semibold">
                            {{ p.name }} {{ p.lastname }}
                        </h2>

                        <!-- DNI -->
                        <p>
                            <strong>DNI:</strong>
                            {{ p.DNI }}
                        </p>

                        <!-- Fecha nacimiento -->
                        <p>
                            <strong>Fecha nacimiento:</strong>
                            {{ p.birth_date }}
                        </p>

                        <!-- Fecha elección -->
                        <p>
                            <strong>Elegido:</strong>
                            {{ p.elected_date }}
                        </p>

                        <!-- Equipo -->
                        <p>
                            <strong>Equipo:</strong>
                            {{ p.team?.name ?? 'Sin asignar' }}
                        </p>

                        <!-- Acciones -->
                        <div class="flex justify-between pt-4">
                            <Link :href="route('admin.presidents.edit', p.id)">
                                <Button variant="outline" size="sm">
                                    Editar
                                </Button>
                            </Link>

                            <Button
                                size="sm"
                                @click="destroyPresident(p.id)"
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

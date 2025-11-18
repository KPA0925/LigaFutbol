<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { type BreadcrumbItem } from '@/types';

defineProps<{
    teams: { id: number; name: string }[];
}>();

const form = useForm({
    match_date_time: '',
    season: '',
    id_home_team: '',
    id_away_team: '',
});

function submit() {
    form.post(route('admin.matches.store'));
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Partidos', href: route('admin.matches.index') },
    { title: 'Crear Partido', href: '' },
];
</script>

<template>
    <Head title="Nuevo Partido" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex justify-center py-10">
            <div
                class="w-full max-w-xl rounded-xl border border-gray-200 bg-white p-8 shadow-lg"
            >
                <!-- Título -->
                <h2 class="mb-1 text-2xl font-semibold text-gray-800">
                    Crear Nuevo Partido
                </h2>
                <p class="mb-6 text-gray-500">
                    Ingrese los datos del nuevo partido de la liga.
                </p>

                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Fecha -->
                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Fecha y Hora</Label>
                        <Input
                            type="datetime-local"
                            v-model="form.match_date_time"
                            class="h-11 rounded-lg border-gray-300 focus:border-blue-500"
                            required
                        />
                    </div>

                    <!-- Temporada -->
                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Temporada</Label>
                        <Input
                            placeholder="Ej: 2024"
                            v-model="form.season"
                            class="h-11 rounded-lg border-gray-300 focus:border-blue-500"
                            required
                        />
                    </div>

                    <!-- Local -->
                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Equipo Local</Label>
                        <select
                            v-model="form.id_home_team"
                            class="h-11 rounded-lg border border-gray-300 px-3 text-gray-700 focus:border-blue-500 focus:outline-none"
                            required
                        >
                            <option value="">Seleccione un equipo</option>
                            <option v-for="team in teams" :value="team.id">
                                {{ team.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Visitante -->
                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Equipo Visitante</Label>
                        <select
                            v-model="form.id_away_team"
                            class="h-11 rounded-lg border border-gray-300 px-3 text-gray-700 focus:border-blue-500 focus:outline-none"
                            required
                        >
                            <option value="">Seleccione un equipo</option>
                            <option v-for="team in teams" :value="team.id">
                                {{ team.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end gap-3 pt-3">
                        <Link :href="route('admin.matches.index')">
                            <Button
                                variant="outline"
                                class="px-4 py-2 text-[#D62027] border-[#D62027]"
                            >
                                Cancelar
                            </Button>
                        </Link>

                        <Button
                            type="submit"
                            class="px-4 py-2 bg-[#D62027] text-white"
                        >
                            Guardar
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

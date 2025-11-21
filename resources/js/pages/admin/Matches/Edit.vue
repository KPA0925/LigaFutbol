<script setup>
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const props = defineProps({
    match: Object,
    teams: Array,
    id_home_team: Number,
    id_away_team: Number,
});

const form = useForm({
    match_date_time: props.match.match_date_time_formatted,
    season: props.match.season,
    id_home_team: props.id_home_team,
    id_away_team: props.id_away_team,
});

function submit() {
    form.put(route('admin.matches.update', props.match.id));
}

const breadcrumbs = [
    { title: 'Partidos', href: route('admin.matches.index') },
    { title: 'Editar Partido', href: '' },
];
</script>

<template>
    <Head title="Editar Partido" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex justify-center py-10">
            <div
                class="w-full max-w-xl rounded-xl border border-gray-200 bg-white p-8 shadow-lg"
            >
                <h2 class="mb-1 text-2xl font-semibold text-gray-800">
                    Editar Partido
                </h2>
                <p class="mb-6 text-gray-500">
                    Modifique los datos del partido seleccionado.
                </p>

                <div
                    v-if="form.errors.match_date_time"
                    class="mb-4 rounded-lg border border-red-300 bg-red-100 px-4 py-3 text-red-700"
                >
                    {{ form.errors.match_date_time }}
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Fecha y Hora</Label>
                        <Input
                            type="datetime-local"
                            v-model="form.match_date_time"
                            class="h-11 rounded-lg border-gray-300 focus:border-blue-500"
                            required
                        />
                        <p
                            v-if="form.errors.match_date_time"
                            class="text-sm text-red-600"
                        >
                            {{ form.errors.match_date_time }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Temporada</Label>
                        <Input
                            v-model="form.season"
                            placeholder="Ej: 2024"
                            class="h-11 rounded-lg border-gray-300 focus:border-blue-500"
                            required
                        />
                        <p
                            v-if="form.errors.season"
                            class="text-sm text-red-600"
                        >
                            {{ form.errors.season }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Equipo Local</Label>
                        <select
                            v-model="form.id_home_team"
                            class="h-11 rounded-lg border border-gray-300 px-3 text-gray-700 focus:border-blue-500 focus:outline-none"
                            required
                        >
                            <option
                                v-for="team in teams"
                                :key="team.id"
                                :value="team.id"
                            >
                                {{ team.name }}
                            </option>
                        </select>

                        <p
                            v-if="form.errors.id_home_team"
                            class="text-sm text-red-600"
                        >
                            {{ form.errors.id_home_team }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Equipo Visitante</Label>
                        <select
                            v-model="form.id_away_team"
                            class="h-11 rounded-lg border border-gray-300 px-3 text-gray-700 focus:border-blue-500 focus:outline-none"
                            required
                        >
                            <option
                                v-for="team in teams"
                                :key="team.id"
                                :value="team.id"
                            >
                                {{ team.name }}
                            </option>
                        </select>

                        <p
                            v-if="form.errors.id_away_team"
                            class="text-sm text-red-600"
                        >
                            {{ form.errors.id_away_team }}
                        </p>
                    </div>

                    <div class="flex justify-end gap-3 pt-3">
                        <Link :href="route('admin.matches.index')">
                            <Button
                                variant="outline"
                                class="border-[#D62027] px-4 py-2 text-[#D62027]"
                            >
                                Cancelar
                            </Button>
                        </Link>

                        <Button
                            :disabled="form.processing"
                            class="bg-[#D62027] px-4 py-2 text-white"
                        >
                            Actualizar
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

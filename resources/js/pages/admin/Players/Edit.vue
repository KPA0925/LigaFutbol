<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const props = defineProps<{
    player: any;
    teams: { id: number; name: string }[];
}>();

const form = useForm({
    fullname: props.player.fullname,
    birth_date: props.player.birth_date,
    position: props.player.position,
    id_team: props.player.id_team,
});

function submit() {
    form.put(route('admin.players.update', props.player.id));
}

const breadcrumbs = [
    { title: 'Jugadores', href: route('admin.players.index') },
    { title: 'Editar Jugador', href: '' },
];
</script>

<template>
    <Head title="Editar Jugador" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex justify-center py-10">
            <div
                class="w-full max-w-xl rounded-xl border border-gray-200 bg-white p-8 shadow-lg"
            >
                <h2 class="mb-1 text-2xl font-semibold text-gray-800">
                    Editar Jugador
                </h2>
                <p class="mb-6 text-gray-500">
                    Modifique los datos del jugador seleccionado.
                </p>

                <form @submit.prevent="submit" class="space-y-5">
                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Nombre Completo</Label>
                        <Input
                            v-model="form.fullname"
                            class="h-11 rounded-lg border-gray-300 focus:border-blue-500"
                            required
                        />
                    </div>

                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Fecha de Nacimiento</Label>
                        <Input
                            type="date"
                            v-model="form.birth_date"
                            class="h-11 rounded-lg border-gray-300 focus:border-blue-500"
                            required
                        />
                    </div>

                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Posición</Label>
                        <Input
                            v-model="form.position"
                            class="h-11 rounded-lg border-gray-300 focus:border-blue-500"
                            required
                        />
                    </div>

                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Equipo</Label>
                        <select
                            v-model="form.id_team"
                            class="h-11 rounded-lg border border-gray-300 px-3 text-gray-700 focus:border-blue-500 focus:outline-none"
                            required
                        >
                            <option value="">Seleccione un equipo</option>
                            <option
                                v-for="team in teams"
                                :key="team.id"
                                :value="team.id"
                            >
                                {{ team.name }}
                            </option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-3 pt-3">
                        <Link :href="route('admin.players.index')">
                            <Button
                                variant="outline"
                                class="px-4 py-2 text-[#D62027] border-[#D62027]"
                            >
                                Cancelar
                            </Button>
                        </Link>

                        <Button
                            type="submit"
                            :disabled="form.processing"
                            class="px-4 py-2 bg-[#D62027] text-white"
                        >
                            Actualizar
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

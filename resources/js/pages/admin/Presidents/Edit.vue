<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const props = defineProps<{
    president: {
        id: number;
        dni: string;
        name: string;
        lastname: string;
        birth_date: string;
        elected_date: string;
        id_team: number;
    };
    teams: { id: number; name: string }[];
}>();

const form = useForm({
    dni: props.president.dni,
    name: props.president.name,
    lastname: props.president.lastname,
    birth_date: props.president.birth_date,
    elected_date: props.president.elected_date,
    id_team: props.president.id_team,
});

function submit() {
    form.put(route('admin.presidents.update', props.president.id));
}

const breadcrumbs = [
    { title: 'Presidentes', href: route('admin.presidents.index') },
    { title: 'Editar Presidente', href: '' },
];
</script>

<template>
    <Head title="Editar Presidente" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex justify-center py-10">
            <div
                class="w-full max-w-xl rounded-xl border border-gray-200 bg-white p-8 shadow-lg"
            >
                <h2 class="mb-1 text-2xl font-semibold text-gray-800">
                    Editar Presidente
                </h2>
                <p class="mb-6 text-gray-500">
                    Actualice la información del presidente.
                </p>

                <form @submit.prevent="submit" class="space-y-5">
                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">DNI</Label>
                        <Input
                            v-model="form.dni"
                            class="h-11 rounded-lg border-gray-300"
                            required
                        />
                    </div>

                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Nombre</Label>
                        <Input
                            v-model="form.name"
                            class="h-11 rounded-lg border-gray-300"
                            required
                        />
                    </div>

                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Apellido</Label>
                        <Input
                            v-model="form.lastname"
                            class="h-11 rounded-lg border-gray-300"
                            required
                        />
                    </div>

                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Fecha de nacimiento</Label>
                        <Input
                            type="date"
                            v-model="form.birth_date"
                            class="h-11 rounded-lg border-gray-300"
                            required
                        />
                    </div>

                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Fecha de elección</Label>
                        <Input
                            type="date"
                            v-model="form.elected_date"
                            class="h-11 rounded-lg border-gray-300"
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
                            <option
                                v-for="team in teams"
                                :key="team.id"
                                :value="team.id"
                            >
                                {{ team.name }}
                            </option>
                        </select>

                        <div
                            v-if="form.errors.id_team"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.id_team }}
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-3">
                        <Link :href="route('admin.presidents.index')">
                            <Button
                                variant="outline"
                                class="border-[#D62027] px-4 py-2 text-[#D62027]"
                            >
                                Cancelar
                            </Button>
                        </Link>

                        <Button
                            type="submit"
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

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

defineProps<{ teams: { id: number; name: string }[] }>();

const form = useForm({
    dni: '',
    name: '',
    lastname: '',
    birth_date: '',
    elected_date: '',
    id_team: '',
});

function submit() {
    form.post(route('admin.presidents.store'));
}

const breadcrumbs = [
    { title: 'Presidentes', href: route('admin.presidents.index') },
    { title: 'Crear Presidente', href: '' },
];
</script>

<template>
    <Head title="Nuevo Presidente" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex justify-center py-10">
            <div
                class="w-full max-w-xl rounded-xl border border-gray-200 bg-white p-8 shadow-lg"
            >
                <h2 class="mb-1 text-2xl font-semibold text-gray-800">
                    Registrar Presidente
                </h2>
                <p class="mb-6 text-gray-500">
                    Complete los datos del nuevo presidente.
                </p>

                <form @submit.prevent="submit" class="space-y-5">
                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">DNI</Label>
                        <Input
                            v-model="form.dni"
                            placeholder="Ej: 1089374352"
                            class="h-11 rounded-lg border-gray-300"
                            required
                        />
                    </div>

                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Nombre</Label>
                        <Input
                            v-model="form.name"
                            placeholder="Ej: Juan"
                            class="h-11 rounded-lg border-gray-300"
                            required
                        />
                    </div>

                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Apellido</Label>
                        <Input
                            v-model="form.lastname"
                            placeholder="Ej: Pérez"
                            class="h-11 rounded-lg border-gray-300"
                            required
                        />
                    </div>

                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Fecha de nacimiento</Label>
                        <Input
                            type="date"
                            v-model="form.birth_date"
                            placeholder="Ej: 1990-01-01"
                            class="h-11 rounded-lg border-gray-300"
                            required
                        />
                        <p
                            v-if="form.errors.birth_date"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ form.errors.birth_date }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Fecha de elección</Label>
                        <Input
                            type="date"
                            v-model="form.elected_date"
                            placeholder="Ej: 2023-05-15"
                            class="h-11 rounded-lg border-gray-300"
                            required
                        />
                        <p
                            v-if="form.errors.elected_date"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ form.errors.elected_date }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Equipo</Label>
                        <select
                            v-model="form.id_team"
                            class="h-11 rounded-lg border border-gray-300 px-3 text-gray-700 focus:border-blue-500 focus:outline-none"
                            required
                        >
                            <option value="">Seleccione un equipo</option>
                            <option v-for="team in teams" :value="team.id">
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
                                class="border-[#D62027] px-4 py-2 text-[#D62027]"
                                variant="outline"
                            >
                                Cancelar
                            </Button>
                        </Link>

                        <Button
                            type="submit"
                            class="bg-[#D62027] px-4 py-2 text-white"
                        >
                            Guardar
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

// 🔥 Recibimos la prop correctamente
const props = defineProps<{ team: any }>();

// 🔥 Ahora sí usamos props.team
const form = useForm({
    name: props.team.name,
    founded_date: props.team.founded_date,
    city: props.team.city,
    stadium: props.team.stadium,
    capacity: props.team.capacity,
});

function submit() {
    form.put(route('admin.teams.update', props.team.id));
}

const breadcrumbs = [
    { title: 'Equipos', href: route('admin.teams.index') },
    { title: 'Editar Equipo', href: '' },
];
</script>

<template>
    <Head title="Editar Equipo" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex justify-center py-10">
            <div
                class="w-full max-w-xl rounded-xl border border-gray-200 bg-white p-8 shadow-lg"
            >
                <!-- Título -->
                <h2 class="mb-1 text-2xl font-semibold text-gray-800">
                    Editar Equipo
                </h2>
                <p class="mb-6 text-gray-500">
                    Modifique los datos del equipo seleccionado.
                </p>

                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Nombre -->
                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Nombre del equipo</Label>
                        <Input
                            v-model="form.name"
                            class="h-11 rounded-lg border-gray-300 focus:border-blue-500"
                            required
                        />
                    </div>

                    <!-- Fecha fundación -->
                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Fecha de fundación</Label>
                        <Input
                            type="date"
                            v-model="form.founded_date"
                            class="h-11 rounded-lg border-gray-300 focus:border-blue-500"
                            required
                        />
                    </div>

                    <!-- Ciudad -->
                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Ciudad</Label>
                        <Input
                            v-model="form.city"
                            class="h-11 rounded-lg border-gray-300 focus:border-blue-500"
                            required
                        />
                    </div>

                    <!-- Estadio -->
                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Estadio</Label>
                        <Input
                            v-model="form.stadium"
                            class="h-11 rounded-lg border-gray-300 focus:border-blue-500"
                            required
                        />
                    </div>

                    <!-- Capacidad -->
                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Capacidad</Label>
                        <Input
                            type="number"
                            v-model="form.capacity"
                            class="h-11 rounded-lg border-gray-300 focus:border-blue-500"
                            required
                        />
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end gap-3 pt-3">
                        <Link :href="route('admin.teams.index')">
                            <Button
                                class="px-4 py-2 text-[#D62027] border-[#D62027]"
                                variant="outline"
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

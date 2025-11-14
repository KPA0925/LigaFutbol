<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const form = useForm({
    name: '',
    founded_date: '',
    city: '',
    stadium: '',
    capacity: '',
});

function submit() {
    form.post(route('admin.teams.store'));
}
</script>

<template>
    <Head title="Nuevo Equipo" />
    <AppLayout>
        <div class="mx-auto max-w-xl p-6">
            <h1 class="mb-4 text-2xl font-bold">➕ Registrar Equipo</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <Label>Nombre del equipo</Label>
                    <Input v-model="form.name" required />
                </div>

                <div>
                    <Label>Fecha de fundación</Label>
                    <Input type="date" v-model="form.founded_date" required />
                </div>

                <div>
                    <Label>Ciudad</Label>
                    <Input v-model="form.city" required />
                </div>

                <div>
                    <Label>Estadio</Label>
                    <Input v-model="form.stadium" required />
                </div>

                <div>
                    <Label>Capacidad</Label>
                    <Input
                        type="number"
                        v-model="form.capacity"
                        min="1000"
                        required
                    />
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <Link :href="route('admin.teams.index')">
                        <Button variant="outline">Cancelar</Button>
                    </Link>
                    <Button type="submit" :disabled="form.processing"
                        >Guardar</Button
                    >
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

defineProps<{ teams: { id: number; name: string }[] }>();

const form = useForm({
    DNI: '',
    name: '',
    lastname: '',
    birth_date: '',
    elected_date: '',
    id_team: '',
});

function submit() {
    form.post(route('admin.presidents.store'));
}
</script>

<template>
    <Head title="Nuevo Presidente" />
    <AppLayout>
        <div class="mx-auto max-w-xl p-6">
            <h1 class="mb-4 text-2xl font-bold">➕ Registrar Presidente</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <Label>DNI</Label>
                    <Input v-model="form.DNI" required />
                </div>

                <div>
                    <Label>Nombre</Label>
                    <Input v-model="form.name" required />
                </div>

                <div>
                    <Label>Apellido</Label>
                    <Input v-model="form.lastname" required />
                </div>

                <div>
                    <Label>Fecha de nacimiento</Label>
                    <Input type="date" v-model="form.birth_date" required />
                </div>

                <div>
                    <Label>Fecha de elección</Label>
                    <Input type="date" v-model="form.elected_date" required />
                </div>

                <div>
                    <Label>Equipo</Label>
                    <select
                        v-model="form.id_team"
                        class="w-full rounded border p-2"
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

                <div class="flex justify-end gap-2 pt-4">
                    <Link :href="route('admin.presidents.index')">
                        <Button variant="outline">Cancelar</Button>
                    </Link>
                    <Button type="submit" :disabled="form.processing">
                        Guardar
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

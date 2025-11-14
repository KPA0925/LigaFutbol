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
        DNI: string;
        name: string;
        lastname: string;
        birth_date: string;
        elected_date: string;
        id_team: number;
    };
    teams: { id: number; name: string }[];
}>();

const form = useForm({
    DNI: props.president.DNI,
    name: props.president.name,
    lastname: props.president.lastname,
    birth_date: props.president.birth_date,
    elected_date: props.president.elected_date,
    id_team: props.president.id_team,
});

function submit() {
    form.put(route('admin.presidents.update', props.president.id));
}
</script>

<template>
    <Head title="Editar Presidente" />
    <AppLayout>
        <div class="mx-auto max-w-xl p-6">
            <h1 class="mb-4 text-2xl font-bold">✏ Editar Presidente</h1>

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
                        Actualizar
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

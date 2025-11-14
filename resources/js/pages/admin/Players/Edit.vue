<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

// ✅ Recibir props correctamente
const props = defineProps<{
    player: any;
    teams: { id: number; name: string }[];
}>();

// ✅ Usar props.player
const form = useForm({
    fullname: props.player.fullname,
    birth_date: props.player.birth_date,
    position: props.player.position,
    id_team: props.player.id_team,
});

function submit() {
    form.put(route('admin.players.update', props.player.id));
}
</script>

<template>
    <Head title="Editar Jugador" />
    <AppLayout>
        <div class="mx-auto max-w-xl p-6">
            <h1 class="mb-4 text-2xl font-bold">✏️ Editar Jugador</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <Label>Nombre Completo</Label>
                    <Input v-model="form.fullname" required />
                </div>

                <div>
                    <Label>Fecha de Nacimiento</Label>
                    <Input type="date" v-model="form.birth_date" required />
                </div>

                <div>
                    <Label>Posición</Label>
                    <Input v-model="form.position" required />
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
                            v-for="team in props.teams"
                            :key="team.id"
                            :value="team.id"
                        >
                            {{ team.name }}
                        </option>
                    </select>
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <Link :href="route('admin.players.index')">
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

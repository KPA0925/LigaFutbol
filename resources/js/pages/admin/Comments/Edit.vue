<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const props = defineProps<{
    comment: any;
    users: any[];
}>();

const form = useForm({
    description: props.comment.description,
    id_user: props.comment.id_user,
});

function submit() {
    form.put(route('admin.comments.update', props.comment.id));
}
</script>

<template>
    <Head title="Editar Comentario" />
    <AppLayout>
        <div class="mx-auto max-w-xl p-6">
            <h1 class="mb-4 text-2xl font-bold">✏️ Editar Comentario</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <Label>Usuario</Label>
                    <select
                        v-model="form.id_user"
                        class="w-full rounded border p-2"
                        required
                    >
                        <option
                            v-for="user in props.users"
                            :key="user.id"
                            :value="user.id"
                        >
                            {{ user.name }}
                        </option>
                    </select>
                </div>

                <div>
                    <Label>Descripción</Label>
                    <Input v-model="form.description" required />
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <Link :href="route('admin.comments.index')">
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

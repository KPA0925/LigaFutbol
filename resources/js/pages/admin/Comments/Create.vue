<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { type BreadcrumbItem } from '@/types';

defineProps<{ users: { id: number; name: string }[] }>();

const form = useForm({
    description: '',
    id_user: '',
});

function submit() {
    form.post(route('admin.comments.store'));
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Comentarios', href: route('admin.comments.index') },
    { title: 'Crear Comentario', href: '' },
];
</script>

<template>
    <Head title="Nuevo Comentario" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex justify-center py-10">
            <div
                class="w-full max-w-xl rounded-xl border border-gray-200 bg-white p-8 shadow-lg"
            >
                <!-- Título -->
                <h2 class="mb-1 text-2xl font-semibold text-gray-800">
                    Crear Nuevo Comentario
                </h2>
                <p class="mb-6 text-gray-500">
                    Ingrese los datos del nuevo comentario.
                </p>

                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Usuario -->
                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Usuario</Label>
                        <select
                            v-model="form.id_user"
                            class="h-11 rounded-lg border border-gray-300 px-3 text-gray-700 focus:border-blue-500 focus:outline-none"
                            required
                        >
                            <option value="">Seleccione un usuario</option>
                            <option
                                v-for="user in users"
                                :key="user.id"
                                :value="user.id"
                            >
                                {{ user.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Descripción -->
                    <div class="flex flex-col gap-1">
                        <Label class="text-gray-700">Descripción</Label>
                        <Input
                            v-model="form.description"
                            placeholder="Escribe el comentario..."
                            class="h-11 rounded-lg border-gray-300 focus:border-blue-500"
                            required
                        />
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end gap-3 pt-3">
                        <Link :href="route('admin.comments.index')">
                            <Button
                                variant="outline"
                                class="px-4 py-2 text-[#D62027] border-[#D62027]"
                            >
                                Cancelar
                            </Button>
                        </Link>

                        <Button
                            type="submit"
                            class="px-4 py-2 bg-[#D62027] text-white"
                            :disabled="form.processing"
                        >
                            Guardar
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

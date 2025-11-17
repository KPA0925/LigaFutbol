<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { Head, useForm } from '@inertiajs/vue3';

interface User {
    id: number;
    name: string;
}

interface Comment {
    id: number;
    description: string;
    user?: User | null;
}

const props = defineProps<{
    comments: Comment[];
}>();

const form = useForm({
    description: '',
});

function submit() {
    form.post('/admin/comments');
}

const breadcrumbs = [{ title: 'Comentarios', href: dashboard().url }];
</script>

<template>
    <Head title="Comentarios" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="rounded-xl bg-white p-6 shadow dark:bg-gray-900">
            <h1 class="mb-4 text-xl font-bold text-[#003366]">
                💬 Comentarios
            </h1>

            <form @submit.prevent="submit" class="mb-4">
                <textarea
                    v-model="form.description"
                    class="w-full rounded-lg border p-3"
                    placeholder="Escribe tu comentario..."
                ></textarea>

                <button
                    class="mt-2 rounded-lg bg-blue-600 px-4 py-2 text-white shadow hover:bg-blue-700"
                >
                    Comentar
                </button>
            </form>

            <div class="space-y-3">
                <div
                    v-for="c in props.comments"
                    :key="c.id"
                    class="rounded-lg bg-gray-100 p-3 dark:bg-gray-700"
                >
                    <p class="font-semibold text-gray-700 dark:text-gray-300">
                        {{ c.user?.name }} dijo:
                    </p>
                    <p class="text-gray-600 dark:text-gray-200">
                        {{ c.description }}
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

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
    created_at: string;
}

const props = defineProps<{
    comments: Comment[];
}>();

const form = useForm({
    description: '',
});

function submit() {
    form.post('/user/comentarios', {
        onSuccess: () => {
            form.reset();
        },
    });
}

function getInitials(name: string | undefined): string {
    if (!name) return 'U';
    return name.charAt(0).toUpperCase();
}

function getTimeAgo(date: string): string {
    const now = new Date();
    const commentDate = new Date(date);
    const diffInHours = Math.floor(
        (now.getTime() - commentDate.getTime()) / (1000 * 60 * 60)
    );

    if (diffInHours < 1) return 'Hace menos de 1 hora';
    if (diffInHours === 1) return 'Hace 1 hora';
    if (diffInHours < 24) return `Hace ${diffInHours} horas`;

    const diffInDays = Math.floor(diffInHours / 24);
    if (diffInDays === 1) return 'Hace 1 día';
    if (diffInDays < 7) return `Hace ${diffInDays} días`;

    return commentDate.toLocaleDateString('es-ES');
}

const breadcrumbs = [{ title: 'Comentarios', href: dashboard().url }];
</script>

<template>
    <Head title="Comentarios" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-4xl space-y-6 p-6">
            <!-- 💬 TÍTULO -->
            <div class="flex items-center gap-3">
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-600 text-white"
                >
                    <i class="fa-solid fa-comment text-xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800">Comentarios</h1>
            </div>

            <!-- ✍️ FORMULARIO -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow">
                <form @submit.prevent="submit" class="space-y-4">
                    <textarea
                        v-model="form.description"
                        rows="4"
                        class="w-full rounded-lg border border-gray-300 p-4 focus:border-red-500 focus:ring-2 focus:ring-red-200"
                        placeholder="Escribe tu comentario..."
                        :disabled="form.processing"
                    ></textarea>

                    <div class="flex justify-end">
                        <button
                            type="submit"
                            :disabled="form.processing || !form.description"
                            class="flex items-center gap-2 rounded-lg bg-red-600 px-6 py-2.5 text-white shadow hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <i class="fa-solid fa-paper-plane"></i>
                            Comentar
                        </button>
                    </div>
                </form>
            </div>

            <!-- 📋 LISTA DE COMENTARIOS -->
            <div class="space-y-4">
                <div
                    v-for="c in props.comments"
                    :key="c.id"
                    class="rounded-xl border border-gray-200 bg-white p-6 shadow transition hover:shadow-md"
                >
                    <div class="flex items-start gap-4">
                        <!-- Avatar -->
                        <div
                            class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600 font-semibold text-lg"
                        >
                            {{ getInitials(c.user?.name) }}
                        </div>

                        <!-- Contenido -->
                        <div class="flex-1">
                            <div
                                class="mb-2 flex items-center justify-between"
                            >
                                <div>
                                    <p
                                        class="font-semibold text-gray-900"
                                    >
                                        {{ c.user?.name || 'Usuario' }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        dijo:
                                    </p>
                                </div>

                                <span class="text-sm text-gray-500">
                                    {{ getTimeAgo(c.created_at) }}
                                </span>
                            </div>

                            <p class="text-gray-700 leading-relaxed">
                                {{ c.description }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Sin comentarios -->
                <div
                    v-if="props.comments.length === 0"
                    class="rounded-xl border border-gray-200 bg-white p-12 text-center"
                >
                    <i
                        class="fa-regular fa-comment-dots text-6xl text-gray-300 mb-4"
                    ></i>
                    <p class="text-gray-500 text-lg">
                        No hay comentarios aún. ¡Sé el primero en comentar!
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
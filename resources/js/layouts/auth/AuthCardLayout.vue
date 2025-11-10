<script setup lang="ts">
import Icon from '@/components/Icon.vue';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
    back?: string | null;
}>();
</script>

<template>
    <div
        class="flex min-h-screen items-center justify-center bg-cover bg-center relative"
        style="background-image: url('/img/fondo-login.webp')"
    >
        <!-- Capa oscura suave -->
        <div class="absolute inset-0 bg-black/40"></div>

        <!-- Contenedor del login -->
        <div class="relative z-10 w-full max-w-md px-6">
            <Card
                class="rounded-2xl shadow-2xl border border-white/10 bg-white/10 dark:bg-gray-900/20 backdrop-blur-xl"
            >
                <CardHeader class="px-10 pb-0 pt-8 text-center">
                    <div v-if="back" class="mb-4">
                        <Link :href="back">
                            <Icon
                                name="arrow-left"
                                class="size-9 text-white hover:text-gray-300 transition"
                                title="Ir atrás"
                            />
                        </Link>
                    </div>
                    <CardTitle class="text-2xl font-bold text-white">
                        {{ title }}
                    </CardTitle>
                    <CardDescription class="text-gray-200">
                        {{ description }}
                    </CardDescription>
                </CardHeader>
                <CardContent class="px-10 py-8 text-white">
                    <slot />
                </CardContent>
            </Card>
        </div>

        <!-- Texto inferior -->
        <div class="absolute bottom-6 left-6 z-10 text-white drop-shadow-lg">
            <h2 class="text-3xl font-bold">Liga de Fútbol</h2>
        </div>
    </div>
</template>


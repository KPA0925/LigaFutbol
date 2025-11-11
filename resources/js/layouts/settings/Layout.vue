<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { appearance } from '@/routes';
import { edit as editPassword } from '@/routes/password';
import { edit } from '@/routes/profile';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';

const sidebarNavItems: NavItem[] = [
    { title: 'Perfil', href: edit() },
    { title: 'Contraseña', href: editPassword() },
    { title: 'Apariencia', href: appearance() },
];

const currentPath =
    typeof window !== 'undefined' ? window.location.pathname : '';
</script>

<template>
    <div class="px-4 py-6 text-gray-900 dark:text-gray-100">
        <Heading
            title="Configuraciones"
            description="Gestiona tu perfil y la configuración de la cuenta"
        />

        <div class="flex flex-col lg:flex-row lg:space-x-12">
            <aside
                class="w-full max-w-xl border-gray-200 lg:w-48 dark:border-gray-700"
            >
                <nav class="flex flex-col space-x-0 space-y-1">
                    <Button
                        v-for="item in sidebarNavItems"
                        :key="
                            typeof item.href === 'string'
                                ? item.href
                                : item.href?.url
                        "
                        variant="ghost"
                        :class="[
                            'w-full justify-start',
                            currentPath ===
                            (typeof item.href === 'string'
                                ? item.href
                                : item.href?.url)
                                ? 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white'
                                : 'text-gray-700 hover:bg-gray-200/70 dark:text-gray-300 dark:hover:bg-gray-700/60',
                        ]"
                        as-child
                    >
                        <Link :href="item.href">{{ item.title }}</Link>
                    </Button>
                </nav>
            </aside>

            <Separator class="my-6 lg:hidden dark:bg-gray-700" />

            <div class="flex-1 md:max-w-2xl">
                <section class="max-w-xl space-y-12">
                    <slot />
                </section>
            </div>
        </div>
    </div>
</template>

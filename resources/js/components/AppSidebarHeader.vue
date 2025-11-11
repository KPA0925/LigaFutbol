<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItemType } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItemType[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);

const page = usePage();
const user = page.props.auth?.user;
</script>

<template>
    <header
        class="flex h-16 shrink-0 items-center justify-between border-b border-[#003366] bg-white px-6 text-[#003366] transition-colors"
    >
        <div class="flex items-center gap-3">
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>

        <DropdownMenu>
            <DropdownMenuTrigger class="flex items-center gap-3 focus:outline-none">
                <div
                    class="flex h-8 w-8 items-center justify-center rounded-full bg-[#003366] font-semibold text-white"
                >
                    {{ user?.name?.charAt(0) || 'U' }}
                </div>

                <span
                    class="text-sm font-medium text-[#003366] truncate max-w-[160px]"
                    :title="user?.name"
                >
                    {{ user?.name || 'Usuario' }}
                </span>
            </DropdownMenuTrigger>

            <DropdownMenuContent
                align="end"
                class="w-64 bg-white p-2 rounded-xl shadow-md border border-[#003366]/20"
            >
                <DropdownMenuLabel
                    class="text-sm font-medium text-gray-700 whitespace-normal break-words leading-snug"
                >
                    {{ user?.email }}
                </DropdownMenuLabel>

                <DropdownMenuSeparator />

                <DropdownMenuItem>
                    <Link
                        href="/profile"
                        class="block w-full text-left text-[#003366] hover:text-[#1A4E9A]"
                    >
                        Perfil
                    </Link>
                </DropdownMenuItem>

                <DropdownMenuSeparator />

                <DropdownMenuItem>
                    <Link
                        href="/logout"
                        method="post"
                        as="button"
                        class="block w-full text-left text-[#D62027] font-medium hover:text-red-700"
                    >
                        Cerrar sesión
                    </Link>
                </DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>
    </header>
</template>

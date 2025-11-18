<script setup lang="ts">
import AppSidebar from '@/components/AppSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import AppContent from '@/components/AppContent.vue';
import type { BreadcrumbItemType } from '@/types';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItemType[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);
</script>

<template>
    <div class="flex h-screen w-full overflow-hidden bg-gray-50">
        <!-- Sidebar fijo -->
        <AppSidebar />

        <!-- Contenedor principal con header y contenido -->
        <div class="
        flex flex-1 flex-col
        absolute
        w-full left-0        
        md:w-[75%] lg:w-[83%] lg:left-[17%] md:left-[25%]
    ">
            <!-- Header fijo -->
            <AppSidebarHeader :breadcrumbs="breadcrumbs" />

            <!-- Contenido con scroll -->
            <AppContent>
                <slot />
            </AppContent>
        </div>
    </div>
</template>
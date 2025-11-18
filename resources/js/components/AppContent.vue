<script setup lang="ts">
import { SidebarInset } from '@/components/ui/sidebar';
import { computed } from 'vue';

interface Props {
    variant?: 'header' | 'sidebar';
    class?: string;
}

const props = defineProps<Props>();
const className = computed(() => props.class);
</script>

<template>
    <SidebarInset
        v-if="props.variant === 'sidebar'"
        :class="[
            'flex flex-1 flex-col overflow-y-auto bg-gray-50 transition-colors duration-300 dark:bg-gray-900 pt-16 md:ml-64 w-full',
            className,
        ]"
    >
        <div class="mx-auto w-full">
            <slot />
        </div>
    </SidebarInset>

    <main
        v-else
        :class="[
            'flex-1 overflow-y-auto  bg-gray-50 transition-colors duration-300 dark:bg-gray-900 pt-16 h-screen w-full',
            className,
        ]"
    >
        <div class="mx-auto w-full">
            <slot />
        </div>
    </main>
</template>
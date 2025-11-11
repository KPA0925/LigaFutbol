<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Panel Admin', href: '/admin' },
    { title: 'Presidentes', href: '/admin/presidents' },
];
</script>

<template>
    <Head title="Presidentes - Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <h1>👔 Gestión de Presidentes</h1>
            <p>Aquí el administrador puede gestionar los presidentes.</p>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import Login from './auth/Login.vue';
</script>

<template>
    <Head title="Bienvenido">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <main>
        <Login :canResetPassword="true" />
    </main>
</template>

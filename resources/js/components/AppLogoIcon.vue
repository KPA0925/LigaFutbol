<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <img
        src="/img/logo.webp"
        alt="Logo"
        :class="className"
        class="h-8 w-auto object-contain"
        v-bind="$attrs"
    />
</template>

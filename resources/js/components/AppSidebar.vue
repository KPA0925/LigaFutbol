<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Role {
    id: number;
    name: string;
}

interface User {
    id: number;
    name: string;
    email: string;
    roles?: Role[];
}

const user = computed<User | null>(() => usePage().props.auth.user as User);

const isAdmin = computed(() => {
    return user.value?.roles?.some((role) => role.name === 'admin') ?? false;
});

const isUser = computed(() => {
    return user.value?.roles?.some((role) => role.name === 'user') ?? false;
});
</script>

<template>
    <aside
        class="flex h-screen w-64 flex-col justify-between overflow-hidden rounded-r-[50px] border-r-4 border-[#003366] bg-[#D62027] text-white shadow-lg"
    >
        <div class="flex flex-col items-center py-6">
            <img
                src="/img/logo.webp"
                alt="Logo"
                class="mb-4 h-10 object-contain"
            />
        </div>

        <nav class="flex-1 space-y-2 px-4">
            <template v-if="isUser">
                <Link
                    href="/estadisticas"
                    class="flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-[#B81E23]"
                >
                    <i class="fas fa-chart-line w-4 text-white"></i>
                    <span class="cursor-pointer text-white">Estadísticas</span>
                </Link>

                <Link
                    href="/dashboard"
                    class="flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-[#B81E23]"
                >
                    <i class="fas fa-futbol w-4 text-white"></i>
                    <span class="cursor-pointer text-white">Partidos</span>
                </Link>

                <Link
                    href="/equipos"
                    class="flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-[#B81E23]"
                >
                    <i class="fas fa-users w-4 text-white"></i>
                    <span class="cursor-pointer text-white">Equipos</span>
                </Link>

                <Link
                    href="/jugadores"
                    class="flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-[#B81E23]"
                >
                    <i class="fas fa-user w-4 text-white"></i>
                    <span class="cursor-pointer text-white">Jugadores</span>
                </Link>

                <Link
                    href="/presidentes"
                    class="flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-[#B81E23]"
                >
                    <i class="fas fa-user-tie w-4 text-white"></i>
                    <span class="cursor-pointer text-white">Presidentes</span>
                </Link>

                <Link
                    href="/comentarios"
                    class="flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-[#B81E23]"
                >
                    <i class="fas fa-comments w-4 text-white"></i>
                    <span class="cursor-pointer text-white">Comentarios</span>
                </Link>
            </template>

            <template v-if="isAdmin">
                <Link
                    href="/admin"
                    class="flex items-center gap-3 rounded-lg bg-[#B81E23] px-3 py-2 transition hover:bg-[#A51920]"
                >
                    <i class="fas fa-crown w-4 text-yellow-400"></i>
                    <span class="cursor-pointer font-semibold text-yellow-400"
                        >Estadísticas</span
                    >
                </Link>

                <Link
                    href="/admin/matches"
                    class="flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-[#B81E23]"
                >
                    <i class="fas fa-futbol w-4 text-white"></i>
                    <span class="cursor-pointer text-white">Partidos</span>
                </Link>

                <Link
                    href="/admin/teams"
                    class="flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-[#B81E23]"
                >
                    <i class="fas fa-users w-4 text-white"></i>
                    <span class="cursor-pointer text-white">Equipos</span>
                </Link>

                <Link
                    href="/admin/players"
                    class="flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-[#B81E23]"
                >
                    <i class="fas fa-user w-4 text-white"></i>
                    <span class="cursor-pointer text-white">Jugadores</span>
                </Link>

                <Link
                    href="/admin/goals"
                    class="flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-[#B81E23]"
                >
                    <i class="fas fa-user w-4 text-white"></i>
                    <span class="cursor-pointer text-white">Goles</span>
                </Link>

                <Link
                    href="/admin/presidents"
                    class="flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-[#B81E23]"
                >
                    <i class="fas fa-user-tie w-4 text-white"></i>
                    <span class="cursor-pointer text-white">Presidentes</span>
                </Link>

                <Link
                    href="/admin/comments"
                    class="flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-[#B81E23]"
                >
                    <i class="fas fa-comments w-4 text-white"></i>
                    <span class="cursor-pointer text-white">Comentarios</span>
                </Link>
            </template>
        </nav>

        <div class="border-t border-[#003366]/30 py-4 text-center text-xs">
            © 2025 Liga de Fútbol
        </div>
    </aside>
</template>

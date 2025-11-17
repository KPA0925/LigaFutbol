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

const currentUrl = computed(() => usePage().url);

function isActive(path: string) {
    return currentUrl.value.startsWith(path);
}

// Estilos base
const activeClass = 'bg-[#A51920] font-semibold';
const defaultClass = 'hover:bg-[#B81E23]';
</script>

<template>
    <aside
        class="flex w-64 flex-col justify-between overflow-hidden rounded-r-[50px] border-r-4 border-[#003366] bg-[#D62027] text-white shadow-lg"
    >
        <div class="flex flex-col items-center py-6">
            <img
                src="/img/logo.webp"
                alt="Logo"
                class="mb-4 h-10 object-contain"
            />
        </div>

        <nav class="flex-1 space-y-2 px-4">
            <!-- USER MENU -->
            <template v-if="isUser">
                <Link
                    href="/user/estadisticas"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2 transition',
                        isActive('/user/estadisticas')
                            ? activeClass
                            : defaultClass,
                    ]"
                >
                    <i class="fas fa-chart-line w-4 text-white"></i>
                    <span>Estadísticas</span>
                </Link>

                <Link
                    href="/user/dashboard"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2 transition',
                        isActive('/user/dashboard')
                            ? activeClass
                            : defaultClass,
                    ]"
                >
                    <i class="fas fa-futbol w-4 text-white"></i>
                    <span>Partidos</span>
                </Link>

                <Link
                    href="/user/equipos"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2 transition',
                        isActive('/user/equipos') ? activeClass : defaultClass,
                    ]"
                >
                    <i class="fas fa-users w-4 text-white"></i>
                    <span>Equipos</span>
                </Link>

                <Link
                    href="/user/jugadores"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2 transition',
                        isActive('/user/jugadores')
                            ? activeClass
                            : defaultClass,
                    ]"
                >
                    <i class="fas fa-user w-4 text-white"></i>
                    <span>Jugadores</span>
                </Link>

                <Link
                    href="/user/goles"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2 transition',
                        isActive('/user/goles') ? activeClass : defaultClass,
                    ]"
                >
                    <i class="fas fa-futbol w-4 text-white"></i>
                    <span>Goles</span>
                </Link>

                <Link
                    href="/user/presidentes"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2 transition',
                        isActive('/user/presidentes')
                            ? activeClass
                            : defaultClass,
                    ]"
                >
                    <i class="fas fa-user-tie w-4 text-white"></i>
                    <span>Presidentes</span>
                </Link>

                <Link
                    href="/user/comentarios"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2 transition',
                        isActive('/user/comentarios')
                            ? activeClass
                            : defaultClass,
                    ]"
                >
                    <i class="fas fa-comments w-4 text-white"></i>
                    <span>Comentarios</span>
                </Link>
            </template>

            <!-- ADMIN MENU -->
            <template v-if="isAdmin">
                <Link
                    href="/admin"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2 transition',
                        isActive('/admin') ? activeClass : defaultClass,
                    ]"
                >
                    <i class="fas fa-chart-line w-4 text-white"></i>
                    <span>Estadísticas</span>
                </Link>

                <Link
                    href="/admin/matches"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2 transition',
                        isActive('/admin/matches') ? activeClass : defaultClass,
                    ]"
                >
                    <i class="fas fa-futbol w-4 text-white"></i>
                    <span>Partidos</span>
                </Link>

                <Link
                    href="/admin/teams"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2 transition',
                        isActive('/admin/teams') ? activeClass : defaultClass,
                    ]"
                >
                    <i class="fas fa-users w-4 text-white"></i>
                    <span>Equipos</span>
                </Link>

                <Link
                    href="/admin/players"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2 transition',
                        isActive('/admin/players') ? activeClass : defaultClass,
                    ]"
                >
                    <i class="fas fa-user w-4 text-white"></i>
                    <span>Jugadores</span>
                </Link>

                <Link
                    href="/admin/goals"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2 transition',
                        isActive('/admin/goals') ? activeClass : defaultClass,
                    ]"
                >
                    <i class="fas fa-futbol w-4 text-white"></i>
                    <span>Goles</span>
                </Link>

                <Link
                    href="/admin/presidents"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2 transition',
                        isActive('/admin/presidents')
                            ? activeClass
                            : defaultClass,
                    ]"
                >
                    <i class="fas fa-user-tie w-4 text-white"></i>
                    <span>Presidentes</span>
                </Link>

                <Link
                    href="/admin/comments"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2 transition',
                        isActive('/admin/comments')
                            ? activeClass
                            : defaultClass,
                    ]"
                >
                    <i class="fas fa-comments w-4 text-white"></i>
                    <span>Comentarios</span>
                </Link>
            </template>
        </nav>

        <div class="border-t border-[#003366]/30 py-4 text-center text-xs">
            © 2025 Liga de Fútbol
        </div>
    </aside>
</template>

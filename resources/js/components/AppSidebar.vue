<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

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

const isAdmin = computed(
    () => user.value?.roles?.some((role) => role.name === 'admin') ?? false,
);

const isUser = computed(
    () => user.value?.roles?.some((role) => role.name === 'user') ?? false,
);

const currentUrl = computed(() => usePage().url);

const isMobileMenuOpen = ref(false);

function isActive(path: string, exact = false) {
    return exact
        ? currentUrl.value === path
        : currentUrl.value.startsWith(path);
}

function toggleMobileMenu() {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
}

function closeMobileMenu() {
    isMobileMenuOpen.value = false;
}

const activeClass = 'bg-[#A51920] font-semibold';
const defaultClass = 'hover:bg-[#B81E23] transition';
</script>

<template>
    <button
        @click="toggleMobileMenu"
        class="fixed left-4 top-2 z-50 flex h-12 w-12 flex-col items-center justify-center gap-1.5 md:hidden"
        aria-label="Toggle menu"
    >
        <span class="block h-0.5 w-6 bg-[#D62027] transition-all"></span>
        <span class="block h-0.5 w-6 bg-[#D62027] transition-all"></span>
        <span class="block h-0.5 w-6 bg-[#D62027] transition-all"></span>
    </button>

    <div
        v-if="isMobileMenuOpen"
        @click="closeMobileMenu"
        class="fixed inset-0 z-40 bg-black/50 md:hidden"
    ></div>

    <aside
        :class="[
            'fixed left-0 top-0 z-50 flex h-screen w-[60%] flex-col justify-between overflow-hidden rounded-r-[50px] border-r-4 border-[#003366] bg-[#D62027] text-white shadow-lg transition-transform duration-300 md:w-[25%] lg:w-[17%]',
            isMobileMenuOpen
                ? 'translate-x-0'
                : '-translate-x-full md:translate-x-0',
        ]"
    >
        <button
            @click="closeMobileMenu"
            class="absolute right-4 top-4 flex h-10 w-10 items-center justify-center rounded-lg text-white md:hidden"
            aria-label="Close menu"
        >
            <i class="fas fa-times text-xl"></i>
        </button>

        <div class="flex flex-col items-center py-6">
            <img
                src="/img/logo.webp"
                alt="Logo"
                class="mb-4 h-10 object-contain"
            />
        </div>

        <nav class="flex-1 space-y-2 overflow-y-auto px-4">
            <template v-if="isUser">
                <Link
                    href="/user/dashboard"
                    @click="closeMobileMenu"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2',
                        isActive('/user/dashboard')
                            ? activeClass
                            : defaultClass,
                    ]"
                >
                    <i class="fas fa-chart-line w-4"></i>
                    <span>Estadísticas</span>
                </Link>

                <Link
                    href="/user/partidos"
                    @click="closeMobileMenu"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2',
                        isActive('/user/partidos') ? activeClass : defaultClass,
                    ]"
                >
                    <i class="fas fa-futbol w-4"></i>
                    <span>Partidos</span>
                </Link>

                <Link
                    href="/user/equipos"
                    @click="closeMobileMenu"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2',
                        isActive('/user/equipos') ? activeClass : defaultClass,
                    ]"
                >
                    <i class="fas fa-users w-4"></i>
                    <span>Equipos</span>
                </Link>

                <Link
                    href="/user/jugadores"
                    @click="closeMobileMenu"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2',
                        isActive('/user/jugadores')
                            ? activeClass
                            : defaultClass,
                    ]"
                >
                    <i class="fas fa-user w-4"></i>
                    <span>Jugadores</span>
                </Link>

                <Link
                    href="/user/goles"
                    @click="closeMobileMenu"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2',
                        isActive('/user/goles') ? activeClass : defaultClass,
                    ]"
                >
                    <i class="fas fa-futbol w-4"></i>
                    <span>Goles</span>
                </Link>

                <Link
                    href="/user/presidentes"
                    @click="closeMobileMenu"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2',
                        isActive('/user/presidentes')
                            ? activeClass
                            : defaultClass,
                    ]"
                >
                    <i class="fas fa-user-tie w-4"></i>
                    <span>Presidentes</span>
                </Link>

                <Link
                    href="/user/comentarios"
                    @click="closeMobileMenu"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2',
                        isActive('/user/comentarios')
                            ? activeClass
                            : defaultClass,
                    ]"
                >
                    <i class="fas fa-comments w-4"></i>
                    <span>Comentarios</span>
                </Link>
            </template>

            <template v-if="isAdmin">
                <Link
                    href="/admin"
                    @click="closeMobileMenu"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2',
                        isActive('/admin', true) ? activeClass : defaultClass,
                    ]"
                >
                    <i class="fas fa-chart-line w-4"></i>
                    <span>Estadísticas</span>
                </Link>

                <Link
                    href="/admin/matches"
                    @click="closeMobileMenu"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2',
                        isActive('/admin/matches') ? activeClass : defaultClass,
                    ]"
                >
                    <i class="fas fa-futbol w-4"></i>
                    <span>Partidos</span>
                </Link>

                <Link
                    href="/admin/teams"
                    @click="closeMobileMenu"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2',
                        isActive('/admin/teams') ? activeClass : defaultClass,
                    ]"
                >
                    <i class="fas fa-users w-4"></i>
                    <span>Equipos</span>
                </Link>

                <Link
                    href="/admin/players"
                    @click="closeMobileMenu"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2',
                        isActive('/admin/players') ? activeClass : defaultClass,
                    ]"
                >
                    <i class="fas fa-user w-4"></i>
                    <span>Jugadores</span>
                </Link>

                <Link
                    href="/admin/goals"
                    @click="closeMobileMenu"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2',
                        isActive('/admin/goals') ? activeClass : defaultClass,
                    ]"
                >
                    <i class="fas fa-futbol w-4"></i>
                    <span>Goles</span>
                </Link>

                <Link
                    href="/admin/presidents"
                    @click="closeMobileMenu"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2',
                        isActive('/admin/presidents')
                            ? activeClass
                            : defaultClass,
                    ]"
                >
                    <i class="fas fa-user-tie w-4"></i>
                    <span>Presidentes</span>
                </Link>

                <Link
                    href="/admin/comments"
                    @click="closeMobileMenu"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2',
                        isActive('/admin/comments')
                            ? activeClass
                            : defaultClass,
                    ]"
                >
                    <i class="fas fa-comments w-4"></i>
                    <span>Comentarios</span>
                </Link>
            </template>
        </nav>

        <div class="border-t border-[#003366]/30 py-4 text-center text-xs">
            © 2025 Liga de Fútbol
        </div>
    </aside>
</template>

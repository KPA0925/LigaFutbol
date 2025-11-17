<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { Head } from '@inertiajs/vue3';

interface Goal {
    id: number;
    minute: number;
    player: {
        fullname: string;
    };
    match: {
        home_team: string;
        away_team: string;
    };
}

interface StadiumStat {
    stadium: string;
    matches: number;
}

const props = defineProps<{
    goals: Goal[];
    stadiumStats: StadiumStat[];
}>();

const breadcrumbs = [{ title: 'Estadísticas', href: dashboard().url }];
</script>

<template>
    <Head title="Estadísticas" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- GOLES -->
            <div class="rounded-xl bg-white p-6 shadow dark:bg-gray-900">
                <h2 class="mb-3 text-xl font-bold text-[#003366]">
                    ⚽ Goles y autores
                </h2>

                <div class="space-y-3">
                    <div
                        v-for="g in props.goals"
                        :key="g.id"
                        class="rounded-lg border-l-4 border-blue-500 bg-blue-50 p-4 shadow dark:bg-blue-900/30"
                    >
                        <p class="font-semibold">
                            {{ g.player.fullname }} anotó al minuto
                            <b>{{ g.minute }}</b>
                        </p>
                        <p class="text-sm text-gray-600">
                            Partido: {{ g.match.home_team }} vs
                            {{ g.match.away_team }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- ESTADIOS -->
            <div class="rounded-xl bg-white p-6 shadow dark:bg-gray-900">
                <h2 class="mb-3 text-xl font-bold text-[#003366]">
                    🏟 Estadios y Partidos Jugados
                </h2>

                <div
                    v-for="st in props.stadiumStats"
                    :key="st.stadium"
                    class="rounded-lg bg-gray-100 p-4 shadow dark:bg-gray-700"
                >
                    <p class="text-lg font-semibold">{{ st.stadium }}</p>
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        Partidos jugados: <b>{{ st.matches }}</b>
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

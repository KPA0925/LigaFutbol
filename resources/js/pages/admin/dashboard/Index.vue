<script setup lang="ts">
import { Card, CardContent } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

import {
    ArcElement,
    BarElement,
    CategoryScale,
    Chart as ChartJS,
    Legend,
    LinearScale,
    Title,
    Tooltip,
} from 'chart.js';
import { Bar, Pie } from 'vue-chartjs';

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    ArcElement,
);

// Props desde el backend
const props = defineProps<{
    stats: {
        players: number;
        teams: number;
        goals: number;
        comments: number;
        matches: number;
    };
    topScorers: { fullname: string; goals_count: number }[];
    teamGoals: { team: string; goals: number }[];
    stadiumMatches: { stadium: string; matches: number }[];
}>();

// Goleadores
const topScorersData = {
    labels: props.topScorers.map((s) => s.fullname),
    datasets: [
        {
            label: 'Goles anotados',
            data: props.topScorers.map((s) => s.goals_count),
        },
    ],
};

// Estadios
const stadiumMatchesData = {
    labels: props.stadiumMatches.map((s) => s.stadium),
    datasets: [
        {
            label: 'Partidos jugados',
            data: props.stadiumMatches.map((s) => s.matches),
        },
    ],
};

// Opciones de los gráficos
const chartOptions = {
    responsive: true,
    plugins: {
        legend: {
            position: 'top',
        },
    },
};
</script>

<template>
    <Head title="Panel de Administración" />

    <AppLayout>
        <div class="space-y-10 p-6">
            <!-- Título -->
            <h1 class="text-3xl font-bold">📊 Dashboard del Sistema</h1>

            <!-- MINI ESTADÍSTICAS -->
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
                <Card
                    v-for="(value, key) in stats"
                    :key="key"
                    class="text-center shadow-sm"
                >
                    <CardContent class="p-3">
                        <h2 class="text-xs uppercase text-gray-500">
                            {{ key }}
                        </h2>
                        <p class="text-xl font-bold">{{ value }}</p>
                    </CardContent>
                </Card>
            </div>

            <!-- SECCIÓN: GOLES Y QUIÉN LOS REALIZÓ -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <Card>
                    <CardContent class="p-4">
                        <h2 class="mb-4 text-xl font-semibold">
                            🏅 Goles anotados y quién los hizo
                        </h2>
                        <Bar :data="topScorersData" />
                    </CardContent>
                </Card>

                <!-- Tabla clara -->
                <Card>
                    <CardContent class="p-4">
                        <h2 class="mb-4 text-xl font-semibold">
                            📋 Lista de goleadores
                        </h2>
                        <table class="w-full rounded border text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="p-2 text-left">Jugador</th>
                                    <th class="p-2 text-center">Goles</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="s in topScorers"
                                    :key="s.fullname"
                                    class="border-t"
                                >
                                    <td class="p-2">{{ s.fullname }}</td>
                                    <td class="p-2 text-center font-semibold">
                                        {{ s.goals_count }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </CardContent>
                </Card>
            </div>

            <!-- SECCIÓN: ESTADIOS -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <Card>
                    <CardContent class="p-4">
                        <h2 class="mb-4 text-xl font-semibold">
                            🏟️ Partidos jugados por estadio
                        </h2>
                        <Pie
                            :data="stadiumMatchesData"
                        />
                    </CardContent>
                </Card>

                <!-- Tabla estadios -->
                <Card>
                    <CardContent class="p-4">
                        <h2 class="mb-4 text-xl font-semibold">
                            📋 Estadios y número de partidos
                        </h2>

                        <table class="w-full rounded border text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="p-2 text-left">Estadio</th>
                                    <th class="p-2 text-center">Partidos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="s in stadiumMatches"
                                    :key="s.stadium"
                                    class="border-t"
                                >
                                    <td class="p-2">{{ s.stadium }}</td>
                                    <td class="p-2 text-center font-semibold">
                                        {{ s.matches }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>

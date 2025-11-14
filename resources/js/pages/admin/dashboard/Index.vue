<script setup lang="ts">
import { Card, CardContent } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import {
    BarElement,
    CategoryScale,
    Chart as ChartJS,
    Legend,
    LinearScale,
    Title,
    Tooltip,
} from 'chart.js';
import { Bar } from 'vue-chartjs';

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
);

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
}>();

const topScorersData = {
    labels: props.topScorers.map((s) => s.fullname),
    datasets: [
        {
            label: 'Goles',
            data: props.topScorers.map((s) => s.goals_count),
        },
    ],
};

const teamGoalsData = {
    labels: props.teamGoals.map((t) => t.team),
    datasets: [
        {
            label: 'Goles por Equipo',
            data: props.teamGoals.map((t) => t.goals),
        },
    ],
};

const chartOptions = {
    responsive: true,
    plugins: {
        legend: {
            position: 'top' as const,
        },
        title: {
            display: true,
            text: 'Estadísticas',
        },
    },
};
</script>

<template>
    <Head title="Panel de Administración" />
    <AppLayout>
        <div class="space-y-6 p-6">
            <h1 class="text-2xl font-bold">Estadísticas</h1>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
                <Card
                    v-for="(value, key) in stats"
                    :key="key"
                    class="text-center"
                >
                    <CardContent class="p-4">
                        <h2 class="text-sm uppercase text-gray-500">
                            {{ key }}
                        </h2>
                        <p class="text-2xl font-bold">{{ value }}</p>
                    </CardContent>
                </Card>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <Card>
                    <CardContent class="p-4">
                        <h2 class="mb-4 text-lg font-semibold">
                            🏅 Máximos Goleadores
                        </h2>
                        <Bar :data="topScorersData" :options="chartOptions" />
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="p-4">
                        <h2 class="mb-4 text-lg font-semibold">
                            ⚽ Goles por Equipo
                        </h2>
                        <Bar :data="teamGoalsData" :options="chartOptions" />
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>

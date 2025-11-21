<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

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

const labelMap: Record<string, string> = {
    players: 'Jugadores',
    teams: 'Equipos',
    goals: 'Goles',
    comments: 'Comentarios',
    matches: 'Partidos',
};

const hueRanges = [
    { min: 0, max: 20 },
    { min: 20, max: 45 },
    { min: 190, max: 240 },
];

function randomHue() {
    const range = hueRanges[Math.floor(Math.random() * hueRanges.length)];
    return Math.floor(Math.random() * (range.max - range.min + 1)) + range.min;
}

function generateUniqueColors(count: number) {
    const usedHues = new Set<number>();
    const colors = [];

    while (colors.length < count) {
        const hue = randomHue();
        if (!usedHues.has(hue)) {
            usedHues.add(hue);
            colors.push(`hsl(${hue}, 70%, 55%)`);
        }
    }

    return colors;
}

const barColors = generateUniqueColors(props.topScorers.length);

const topScorersData = {
    labels: props.topScorers.map((s) => s.fullname),
    datasets: [
        {
            label: 'Top Goles anotados',
            data: props.topScorers.map((s) => s.goals_count),
            backgroundColor: barColors,
            borderColor: '#003366',
            borderWidth: 1,
        },
    ],
};

const stadiumMatchesData = {
    labels: props.stadiumMatches.map((s) => s.stadium),
    datasets: [
        {
            label: 'Partidos jugados',
            data: props.stadiumMatches.map((s) => s.matches),
            backgroundColor: barColors,
            borderColor: '#003366',
            borderWidth: 1,
        },
    ],
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Estadísticas', href: dashboard().url },
];
</script>

<template>
    <Head title="Panel de Administración" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-10 p-6">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold">Estadísticas generales</h1>

                <a
                    :href="
                        route('admin.export.excel', { module: 'statistics' })
                    "
                    target="_blank"
                >
                    <Button class="bg-[#D62027] px-4 py-2 text-white">
                        <i class="fa-solid fa-download"></i> Exportar
                        Estadísticas
                    </Button>
                </a>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
                <Card
                    v-for="(value, key) in stats"
                    :key="key"
                    class="bg-white text-center shadow-sm"
                >
                    <CardContent class="p-3">
                        <h2 class="text-xs uppercase text-gray-500">
                            {{ labelMap[key] ?? key }}
                        </h2>
                        <p class="text-xl font-bold">{{ value }}</p>
                    </CardContent>
                </Card>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <Card class="bg-white">
                    <CardContent class="p-4">
                        <h2 class="mb-4 text-xl font-semibold">
                            Goles anotados y quién los hizo
                        </h2>
                        <Bar :data="topScorersData" />
                    </CardContent>
                </Card>

                <Card class="bg-white">
                    <CardContent class="bg-white p-4">
                        <h2 class="mb-4 text-xl font-semibold">
                            Top 5 Lista de goleadores
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

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <Card class="bg-white">
                    <CardContent class="bg-white p-4">
                        <h2 class="mb-4 text-xl font-semibold">
                            Partidos jugados por estadio
                        </h2>
                        <Pie :data="stadiumMatchesData" />
                    </CardContent>
                </Card>

                <Card class="bg-white">
                    <CardContent class="bg-white p-4">
                        <h2 class="mb-4 text-xl font-semibold">
                            Estadios y número de partidos
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

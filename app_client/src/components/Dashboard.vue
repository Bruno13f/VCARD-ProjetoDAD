<script setup>
import Chart from 'chart.js/auto';
import moment from 'moment';
import 'chart.js';
import 'chartjs-adapter-moment';
import { onMounted, ref } from 'vue';
import axios from 'axios';
import { useUserStore } from '@/stores/user.js';

const transactions = ref([]);
const userStore = useUserStore();
const flag = userStore.user.user_type == 'A' ? false : true;

const loadTransactions = async () => {
    try {
        const response = await axios.get(`vcards/${userStore.user.id}/transactions`);
        transactions.value = response.data.data;
    } catch (error) {
        console.log(error);
    }
};

const createChart = () => {
    const ctx = document.getElementById('myChart');

    // Ensure dates are properly formatted for time scale
    const formattedDates = transactions.value.map((transaction) => moment(transaction.datetime));

    const newBalances = transactions.value.map((transaction) => transaction.new_balance);

    const minBalance = Math.min(...newBalances);
    const maxBalance = Math.max(...newBalances);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: formattedDates,
            datasets: [
                {
                    label: 'New Balances',
                    data: newBalances,
                    borderWidth: 1,
                    fill: false,
                },
            ],
        },
        options: {
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'day',
                        tooltipFormat: 'YYYY-MM-DD HH:mm:ss', // Adjust the format as needed
                    },
                    title: {
                        display: true,
                        text: 'Date',
                    },
                },
                y: {
                    min: minBalance,
                    max: maxBalance,
                    beginAtZero: false,
                    title: {
                        display: true,
                        text: 'New Balance',
                    },
                },
            },
            plugins: {
                legend: {
                    display: true,
                },
            },
            layout: {
                padding: {
                    left: 10,
                    right: 10,
                    top: 10,
                    bottom: 10,
                },
            },
        },
    });
};

onMounted(async () => {
    await loadTransactions();
    console.log(transactions);
    if (flag) {
        createChart();
    }
});
</script>

<template>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
    </div>
    <div>
        <canvas id="myChart"></canvas>
    </div>
</template>

<script setup>
import Chart from 'chart.js/auto'
import { onMounted, ref} from 'vue'
import { useUserStore } from '@/stores/user.js'

const transactions = ref([])
const userStore = useUserStore()

const loadTransactions = async () => {
    try {
        const response = flag ? await axios.get(`vcards/${userStore.user.id}/transactions`, { params: params }) : await axios.get('transactions', { params: params })
        transactions.value = response.data.data
    } catch (error) {
        console.log(error)
    }
}

onMounted(() => {
    loadTransactions()
    console.log(transactions.value)
    const ctx = document.getElementById('myChart');
    const src = "https://cdn.jsdelivr.net/npm/chart.js"
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: transactions.value.date,
            datasets: [{
                label: 'transactions por ...',
                data: transactions.value.id,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
})
</script>

<template>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
    </div>
    <div>
        <canvas id="myChart"></canvas>
    </div>
</template>


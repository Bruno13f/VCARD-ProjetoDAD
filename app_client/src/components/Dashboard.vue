<script setup>
import Chart from 'chart.js/auto';
import moment from 'moment';
import 'chart.js';
import 'chartjs-adapter-moment';
import { onMounted, ref } from 'vue';
import axios from 'axios'
import { useUserStore } from '@/stores/user.js'

const transactions = ref([])
const categories = ref([])
const userStore = useUserStore()

const flag = userStore.user.user_type == 'A' ? false : true

const vcards = ref([])
const filterByBlocked = ref(null)

const loadTransactions = async () => {
    try {
        const response = flag ? await axios.get(`vcards/${userStore.user.id}/transactions`) : ''
        transactions.value = response.data.data
    } catch (error) {
        console.log(error)
    }
};

const loadCategories = async () => {
    try {
        const response = flag ? await axios.get(`transactions/${userStore.user.id}/categories`) : ''
        categories.value = response.data
    } catch (error) {
        console.log(error)
    }
};

const createChartLine = () => {
    const ctx = document.getElementById('myChartLine')

    // Ensure dates are properly formatted for time scale
    const formattedDates = transactions.value.map((transaction) => moment(transaction.datetime))

    const newBalances = transactions.value.map((transaction) => transaction.new_balance)

    const minBalance = Math.floor(Math.min(...newBalances) / 10) * 10;
    const maxBalance = Math.ceil(Math.max(...newBalances) / 10) * 10;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: formattedDates,
            datasets: [
                {
                    label: 'New Balance',
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
                    min: minBalance + 0,
                    max: maxBalance + 50,
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
    })
}

const createChartPie = () => {

    const ctx = document.getElementById('myChartPie')

    const categoriesName = categories.value.map((categorie) => categorie.name)

    const categoriesNumbers = categories.value.map((categorie) => categorie.count)

    new Chart(ctx, {
        type: 'radar',
        data: {
            labels: categoriesName,
            datasets: [
                {
                    label: 'Uses',
                    data: categoriesNumbers,
                    borderWidth: 1,
                    fill: false,
                    backgroundColor: [
                        'rgb(0,0,0)',
                        'rgb(255,0,0)',
                        'rgb(0,255,0)',
                        'rgb(0,0,255)',
                        'rgb(255,255,0)',
                        'rgb(0,255,255)',
                        'rgb(255,0,255)',
                        'rgb(192,192,192)',
                        'rgb(128,128,128)',
                        'rgb(128,0,0)',
                        'rgb(128,128,0)',
                        'rgb(0,128,0)',
                        'rgb(128,0,128)',
                        'rgb(0,128,128)',
                        'rgb(0,0,128)'
                    ]
                },
            ]
        },
        options: {
            scale: {
                pointLabels: {
                    fontSize: 12,
                },
                ticks: {
                    //beginAtZero: true,
                    //min: 0,
                    stepSize: 1,
                },

            }
        },
    })
}

const loadVcards = async () => {
    try{
      const response = await axios.get('vcards', {params: {
        blocked: filterByBlocked.value,
      }})
      vcards.value = response.data.data
    }catch(error){
      console.log(error)
    }
  }




onMounted(async () => {
    await loadTransactions()
    await loadCategories()
    await loadVcards()
    console.log(transactions)
    console.log(categories)
    console.log(vcards)
    if (flag) {
        createChartLine()
        createChartPie()
    }
});
</script>

<template>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
    </div>
    <div class="d-flex">
        <div>
            <canvas id="myChartLine"></canvas>
        </div>
        <div>
            <canvas id="myChartPie"></canvas>
        </div>
    </div>
</template>

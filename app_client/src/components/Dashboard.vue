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
const numberOfTransactions = ref(null)
const balance = ref(null)
const numberOfCategories = ref(null)

const vcards = ref([])
const filterByBlocked = ref(null)

const loadTransactions = async () => {
    try {
        const response = flag ? await axios.get(`vcards/${userStore.user.id}/transactions`) : ''
        transactions.value = response.data.data
        numberOfTransactions.value = response.data.meta.total
        balance.value = transactions.value[0]['new_balance']
    } catch (error) {
        console.log(error)
    }
};

const loadCategories = async () => {
    try {
        const response = flag ? await axios.get(`transactions/${userStore.user.id}/categories`) : ''
        categories.value = response.data
        numberOfCategories.value = categories.value.length
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
                    label: 'Balance',
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
                        text: 'Balance',
                    },
                    ticks: {
                    callback: function (value, index, values) {
                        return value + '€'  
                    },
                },
                },
            },
            plugins: {
                legend: {
                    display: true,
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            var label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.parsed.y.toFixed(2) + ' €'; // Add the € symbol to tooltip values
                            return label;
                            }
                    }
                }
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
    const ctx = document.getElementById('myChartPie');
    const categoriesName = categories.value.map((categorie) => categorie.name);
    const categoriesNumbers = categories.value.map((categorie) => categorie.count);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: categoriesName,
            datasets: [
                {
                    label: 'Transactions per Category',
                    data: categoriesNumbers,
                    borderWidth: 1,
                    borderColor: 'rgba(255, 77, 77, 1)',
                    backgroundColor: 'rgba(255, 77, 77, 1)',
                    fill: false,
                },
            ],
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            plugins: {
                datalabels: {
                    anchor: 'end',
                    align: 'end',
                },
            },
            scales: {
                x: {
                    beginAtZero: true,
                },
                y: {
                    ticks: {
                        stepSize: 1,
                    },
                    pointLabels: {
                        fontSize: 12,
                    },
                },
            },
        },
    });
};


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
    <div class="container-fluid">
        <div class="row mt-5">
            <!-- Second Row -->
            <div class="col-md-4 d-flex justify-content-center mb-2">
                <div class="card text-white bg-success" style="width: 18rem;">
                    <div class="card-body text-center">
                        <h5 class="card-title">Vcard Balance: {{ balance }} €</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4 d-flex justify-content-center mb-2">
                <div class="card text-white bg-warning" style="width: 18rem;">
                    <div class="card-body text-center">
                        <h5 class="card-title">Transactions: {{ numberOfTransactions }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4 d-flex justify-content-center mb-2">
                <div class="card text-white bg-danger" style="width: 18rem;">
                    <div class="card-body text-center">
                        <h5 class="card-title">Used Categories: {{ numberOfCategories }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <!-- First Row -->
            <div class="col-md-6">
                <canvas id="myChartLine"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="myChartPie"></canvas>
            </div>
        </div>
    </div>
</template>

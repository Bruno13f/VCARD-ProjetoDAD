<script setup>
  import axios from 'axios'
  import { ref, computed, onMounted } from 'vue'
  import {useRouter} from 'vue-router'
  import TransactionTable from "./TransactionTable.vue"
  import { useToast } from "vue-toastification"
  import { useUserStore } from '@/stores/user.js'

  const toast = useToast();
  const router = useRouter();

  const userStore = useUserStore() 
  const flag = userStore.user.user_type == 'A' ? false : true
  

  const loadTransactions = async () => {
    try{
      let apiUrl = 'transactions';

      if (flag) {
      apiUrl = `vcards/${userStore.user.id}/transactions`;
    }

      const response = await axios.get(apiUrl)
      transactions.value = response.data.data
    }catch(error){
      console.log(error)
    }
  }

  const loadUsers = async () => {
    try{
      const response = await axios.get('users')
      users.value = response.data.data
    }catch(error){
      console.log(error)
    }
  }

  const addTransaction = () => {
      router.push({ name: 'NewTransaction'})
      console.log("Navigate to New Transaction")
  }
  
  const editTransaction = (transaction) => {
      router.push({name: 'Transaction', params: { id: transaction.id }})
      console.log('Navigate to Edit Transaction with id = ' + transaction.id)
  }

  const deleteTransaction = async (transaction) => {
    // nao implementado 
      axios.delete('transactions/' + transaction.id).then((response) => {
          let deletedTransaction = response.data.data
          let idx = transactions.value.findIndex((t) => t.id === deletedTransaction.id)
          if (idx >= 0) {
            transactions.value.splice(idx, 1)
          }
          toast.success('Transaction #' + response.data.data.id + ' was deleted successfully.')
        })
        .catch((error) => {
          if (error.response.status == 422){
            toast.error("Can't delete Transaction - Vcard exists")
          }else {
            toast.error("Transaction wasn't deleted due to unknown server error!")
          }
      });
  }


  const transactions = ref([])
  const users = ref([])
  const filterByType = ref(null)
  const filterByPaymentType = ref(null)

  const filteredTransactions = computed(()=>{
    return transactions.value.filter(t =>
        (!filterByType.value
          || filterByType.value == t.type
        ) &&
        (!filterByPaymentType.value
          || filterByPaymentType.value == t.payment_type
        ))
  })

  const totalTransactions = computed(()=>{
    return transactions.value.reduce((c, t) =>
    (!filterByType.value
          || filterByType.value == t.type
        ) &&
        (!filterByPaymentType.value
          || filterByPaymentType.value == t.payment_type
        ) ? c + 1 : c, 0)
  })

  onMounted(() => {
    loadUsers()
    loadTransactions()
  })

</script>

<template>
  <div class="d-flex justify-content-between">
    <div class="mx-2">
      <h3 class="mt-4">Transactions</h3>
    </div>
    <div class="mx-2 total-filtro">
      <h5 class="mt-4">Total: {{ totalTransactions }}</h5>
    </div>
  </div>
  <hr>
  <div class="mb-3 d-flex justify-content-between flex-wrap">
    <div class="mx-2 mt-2 flex-grow-1 filter-div">
      <label
        for="selectType"
        class="form-label"
      >Filter by Type:</label>
      <select
        class="form-select"
        id="selectType"
        v-model="filterByType"
      >
        <option :value="null"></option>
        <option value="C">Credit</option>
        <option value="D">Debit</option>
      </select>
    </div>
    <div class="mx-2 mt-2 flex-grow-1 filter-div">
      <label
        for="selectStatus"
        class="form-label"
      >Filter by Payment Type:</label>
      <select
        class="form-select"
        id="selectStatus"
        v-model="filterByPaymentType"
      >
        <option :value="null"></option>
        <option value="VCARD">VCARD</option>
        <option value="MBWAY">MBWAY</option>
        <option value="PAYPAL">PAYPAL</option>
        <option value="IBAN">IBAN</option>
        <option value="Multibanco">MB</option>
        <option value="VISA">VISA</option>
      </select>
    </div>
  </div>
  <transaction-table
    :transactions="filteredTransactions"
    :showId="true"
    :showDates="true"
    @edit="editTransaction"
    @delete="deleteTransaction"
  ></transaction-table>
</template>

<style scoped>
.filter-div {
  min-width: 12rem;
}
.total-filtro {
  margin-top: 0.35rem;
}
.btn-addprj {
  margin-top: 1.85rem;
}
</style>

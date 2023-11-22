<script setup>
  import axios from 'axios'
  import { ref, watch, computed } from 'vue'

  import TransactionTable from "../transactions/TransactionTable.vue"

  const props = defineProps({
    id: {
      type: Number,
      default: null
    }
  })

  const emptyVcard = () => {
      return {
        id: null,
        name: '',
        status_name: '',
        transactions: []
      }
    }
    
  const loadVcard = (phone_number) => {
      if (!phone_number || phone_number < 0) {
        vcard.value = emptyVcard.value()
      } else {
        axios.get('vcards/' + phone_number + '/transactions')
          .then((response) => {
            vcard.value = response.data.data
          })
          .catch((error) => {
            console.log(error)
          })
      }
    }

  const editVcard = () => {
      console.log('Navigate to Edit Vcard with ID = ' + props.phone_number)
    }

  const addTransaction = () => {
      console.log('Navigate to New Transaction')
    }

  const editTransaction = (transction) => {
      console.log('Navigate to Edit Transction with ID = ' + transction.id)
    }

  const deletedTransaction = (deletedTransaction) => {
      let idx = vcard.value.transactions.findIndex((t) => t.id === deletedTransaction.id)
      if (idx >= 0) {
        vcard.value.transactions.splice(idx, 1)
      }
    }

  const vcard= ref(emptyVcard())

  const filterByCompleted = ref(0)
  
  watch(
    () => props.id,
    (newValue) => {
          loadVcard(newValue)
    }, 
    { immediate: true}
  )

  const filteredTransactions = computed(() => {
    return vcard.value.transactions.filter(t =>
        filterByCompleted.value == -1
        || filterByCompleted.value == 0 && !t.completed
        || filterByCompleted.value == 1 && t.completed
      )
  })

  const totalTransactions = computed(() => {
    return vcard.value.transactions.reduce((c, t) =>
        filterByCompleted.value == -1
          || filterByCompleted.value == 0 && !t.completed
          || filterByCompleted.value == 1 && t.completed
          ? c + 1 : c, 0)    
  })

</script>

<template>
  <div class="mx-2">
    <h3 class="mt-4">Vcard # {{vcard.id}} : {{vcard.name}}</h3>
  </div>
  <hr>
  <div class="d-flex justify-content-between">
    <div class="mx-2">
      <h5 class="mt-4">Vcard status: {{vcard.status_name}}</h5>
    </div>
    <div class="mx-2 total-filtro">
      <h5 class="mt-4">Total transactions: {{ totalTransactions }}</h5>
    </div>
  </div>
  <div class="mb-4 d-flex flex-wrap justify-content-between">
    <div class="mx-2 mt-2 flex-grow-1">
      <label
        for="selectCompleted"
        class="form-label"
      >Filter by completed:</label>
      <select
        class="form-select"
        id="selectCompleted"
        v-model="filterByCompleted"
      >
        <option value="-1">Any</option>
        <option value="0">Pending Transactions</option>
        <option value="1">Completed Transactions</option>
      </select>
    </div>
    <div class="mx-2 mt-2">
      <button
        type="button"
        class="btn btn-secondary px-4 btn-top"
        @click="editVcard"
      ><i class="bi bi-xs bi-pencil"></i>&nbsp; Edit Vcard</button>
    </div>
    <div class="mx-2 mt-2">
      <button
        type="button"
        class="btn btn-success px-4 btn-top"
        @click="addVcard"
      ><i class="bi bi-xs bi-plus-circle"></i>&nbsp; Add Vcard</button>
    </div>
  </div>

  <transaction-table
    :transactions="filteredTransactions"
    :showId="true"
    :showOwner="true"
    :showVcard="false"
    @edit="editTransaction"
    @deleted="deletedTransaction"
  ></transaction-table>
</template>


<style scoped>
.btn-top {
  margin-top: 1.85rem;
}
</style>

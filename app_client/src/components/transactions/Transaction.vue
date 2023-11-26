<script setup>
  import axios from 'axios'
  import { ref, watch, computed, onMounted} from 'vue'
  import {useRouter} from 'vue-router'
  import TransactionDetail from "./TransactionDetail.vue"
  import { useToast } from "vue-toastification"
  
  const toast = useToast()

  const router = useRouter()
  const newTransaction = () => { 
    return {
      vcard: '',
      date: '',
      datetime:'',
      type: '',
      value: '',
      old_balance: '',
      new_balance: '',
      payment_type:'',
      payment_reference:'',
    }
  }

  const loadTransaction = (id) => {
      if (!id || id <0) {
        transaction.value = newTransaction()
      } else {
        axios.get('transactions/' + id)
          .then((response) => {
            transaction.value = response.data.data
          })
          .catch((error) => {
            console.log(error)
          })
      }
    }

  const save = () => {
      if (operation.value == 'insert') {
        axios.post('transactions', transaction.value)
          .then((response) => {
            console.log('Transaction Created')
            console.dir(response.data.data)
            toast.success('Transaction #' + response.data.data.id + ' was created successfully.')
            router.back()
          })
          .catch((error) => {
            if (error.response.status == 422) {
              toast.error('Transaction was not created due to validation errors!')
            } else {
              toast.error('Transaction was not created due to unknown server error!')
            }
          })
      } else {
        axios.put('transactions/' + props.id, transaction.value)
          .then((response) => {
            console.log('Transaction Updated')
            console.dir(response.data.data)
            toast.success('Transaction #' + response.data.data.id + ' was edited successfully.')
            router.back()
          })
          .catch((error) => {
            if (error.response.status == 422) {
              toast.error('Transaction was not edited due to validation errors!')
            } else {
              toast.error('Transaction was not edited due to unknown server error!')
            }
          })
      }
    }


  const cancel = () => {
      // Replace this code to navigate back
      router.back()
      //loadTransaction(props.id)
  }

  const props = defineProps({
    id: {
        type: Number,
        default: null
      }
    })

  const transaction = ref(newTransaction())  
  const users = ref([])  

  const operation = computed(() => {
    return (!props.id || (props.id<0 )) ? 'insert' : 'update'
  })

  watch(
    () => props.id, 
    (newValue) => {
          loadTransaction(newValue)
    }, {
      immediate: true,
    }
  )

  onMounted (() => {
    users.value = []
    axios.get('users')
      .then((response) => {
        users.value = response.data.data
      })
      .catch((error) => {
        console.log(error)
      })
  })
</script>


<template>
  <TransactionDetail
    :operationType="operation"
    :transaction="transaction"
    :users="users"
    @save="save"
    @cancel="cancel"
  ></TransactionDetail>
</template>

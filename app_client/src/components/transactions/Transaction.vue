<script setup>
import axios from 'axios'
import { ref, watch, computed, onMounted} from 'vue'
import { useRouter, onBeforeRouteLeave } from 'vue-router'
import TransactionDetail from "./TransactionDetail.vue"
import { useToast } from "vue-toastification"

const toast = useToast()
const router = useRouter()

const newTransaction = () => {
  return {
    vcard: '',
    date: '',
    datetime: '',
    type: '',
    value: '',
    old_balance: '',
    new_balance: '',
    payment_type: '',
    payment_reference: '',
  }
}

const transaction = ref(newTransaction())
const errors = ref(null)
const confirmationLeaveDialog = ref(null)
let originalValueStr = ''

const loadTransaction = async (id) => {
  originalValueStr = ''
  errors.value = null
  if (!id || id < 0) {
    transaction.value = newTransaction()
    originalValueStr = JSON.stringify(transaction.value)
  } else {
    try {
      const response = await axios.get('transactions/' + id)
      transaction.value = response.data.data
      originalValueStr = JSON.stringify(transaction.value)
    } catch (error) {
      console.log(error)
    }
  }
}

const save = async () => {
  errors.value = null
  if (operation.value == 'insert') {
    try {
      const response = await axios.post('transactions', transaction.value)
      transaction.value = response.data.data
      originalValueStr = JSON.stringify(transaction.value)
      console.log(response)
      toast.success('Transaction #' + response.data.data.id + ' was created successfully.')
      router.back();
    } catch (error) {
      if (error.response.status == 422) {
        errors.value = error.response.data.errors
        toast.error('Transaction was not created due to validation errors!')
      } else {
        toast.error('Transaction was not created due to unknown server error!')
      }
    }
  } else {
    try {
      console.log(transaction.value)
      const response = await axios.put('transactions/' + props.id, transaction.value)
      transaction.value = response.data.data
      originalValueStr = JSON.stringify(transaction.value)
      console.log('Transaction Updated')
      console.dir(response.data.data)
      toast.success('Transaction #' + response.data.data.id + ' was edited successfully.')
      router.back()
    } catch (error) {
      if (error.response.status == 422) {
        errors.value = error.response.data.errors
        console.log(errors)
        toast.error('Transaction was not edited due to validation errors!')
      } else {
        toast.error('Transaction was not edited due to unknown server error!')
      }
    }
  }
}


const cancel = () => {
  originalValueStr = JSON.stringify(transaction.value)  
  router.back()
}

const props = defineProps({
  id: {
    type: Number,
    default: null
  }
})

const operation = computed(() => {
  return (!props.id || (props.id < 0)) ? 'insert' : 'update'
})

watch(
  () => props.id,
  (newValue) => {
    loadTransaction(newValue)
  }, {
  immediate: true,
}
)

onMounted(async () => {
})


let nextCallBack = null
const leaveConfirmed = () => {
  if (nextCallBack) {
    nextCallBack()
  }
}

onBeforeRouteLeave((to, from, next) => {
  nextCallBack = null;
  let newValueStr = JSON.stringify(transaction.value);
  if (originalValueStr !== newValueStr) {
    nextCallBack = next;
    confirmationLeaveDialog.value.show();
  } else {
    next();
  }
});
</script>


<template>
  <confirmation-dialog
    ref="confirmationLeaveDialog"
    confirmationBtn="Discard changes and leave"
    msg="Do you really want to leave? You have unsaved changes!"
    @confirmed="leaveConfirmed"
  >
  </confirmation-dialog>  
  <TransactionDetail 
  :operationType="operation" 
  :transaction="transaction"
  :errors="errors" 
  @save="save"
  @cancel="cancel">
  </TransactionDetail>
</template>

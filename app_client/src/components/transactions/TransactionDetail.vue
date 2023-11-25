<script setup>
  import { ref, watch, computed } from 'vue'

  const props = defineProps({
    transaction: {
      type: Object,
      required: true
    },
    operationType: {
      type: String,
      default: 'insert'  // insert / update
    },
    users: {
      type: Array,
      required: true
    }    
  })

  const emit = defineEmits(['save', 'cancel'])

  const editingTransaction = ref(props.transaction)

  watch(
    () => props.transaction, 
    (newTransaction) => { 
      editingTransaction.value = newTransaction
    }
  )

  const save = () => {
      emit('save', editingTransaction.value)
  }

  const cancel = () => {
      //console.log(operationType.value)
      emit('cancel', editingTransaction.value)
  }

</script>

<template>
  <form
    class="row g-3 needs-validation"
    novalidate
    @submit.prevent="save"
  >
    <h3 class="mt-5 mb-3"></h3>
    <hr>

    <div class="mb-3">
      <label
        for="inputName"
        class="form-label"
      >Vcard *</label>
      <input
        type="text"
        class="form-control"
        id="inputName"
        placeholder="Vcard"
        required
        v-model="editingTransaction.vcard"
      >
    </div>

    <div class="d-flex flex-wrap justify-content-between">
      <div class="mb-3 me-3 flex-grow-1">
        <label
          for="inputValue"
          class="form-label"
        >Value *</label>
        <input
        type="text"
        class="form-control"
        id="inputValue"
        placeholder="Value"
        required
        v-model="editingTransaction.value"
      >
      </div>

      
      <div class="mx-2 mt-2 flex-grow-1 filter-div">
        <label
          for="selectType"
         class="form-label"
        >Filter by Type: *</label>
        <select
          class="form-select"
          id="selectType"
        >
          <option :value="null"></option>
          <option value="C">Credit</option>
          <option value="D">Debit</option>
        </select>
      </div>

      <div class="mb-3 ms-xs-3 flex-grow-1">
        <label
          for="inputPaymentReference"
          class="form-label"
        >Payment Reference *</label>
        <input
        type="text"
        class="form-control"
        id="inputPaymentReference"
        placeholder="Payment Reference"
        required
        v-model="editingTransaction.payment_reference"
      >
      </div>
    </div>

    <div class="mx-2 mt-2 filter-div">
      <label
        for="selectStatus"
        class="form-label"
      >Payment Type: *</label>
      <select
        class="form-select"
        id="selectStatus"
      >
        <option :value="null"></option>
        <option value="VCARD">VCARD</option>
        <option value="MBWAY">MBWAY</option>
        <option value="PAYPAL">PAYPAL</option>
        <option value="IBAN">IBAN</option>
        <option value="MB">MB</option>
        <option value="VISA">VISA</option>
      </select>
    </div>

    <div class="d-flex flex-wrap justify-content-between">
    </div>

    <div class="mb-3 d-flex justify-content-center ">
      <button
        type="button"
        class="btn btn-success px-5 mx-2"
        @click="save"
      >Save</button>
      <button
        type="button"
        class="btn btn-dark px-5"
        @click="cancel"
      >Cancel</button>
    </div>

  </form>
</template>

<style scoped>
.total_price {
  width: 26rem;
}
.checkBilled {
  margin-top: 0.4rem;
  min-height: 2.375rem;
}
</style>

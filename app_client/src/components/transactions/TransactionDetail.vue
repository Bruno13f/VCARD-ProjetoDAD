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

const editingTransaction = ref({
  vcard: { phone_number: '' },
  value: '',
  payment_reference: props.transaction.payment_type,
  type: props.transaction.type,
  status: null,
  description: '',
});

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
  <form class="row g-3 needs-validation" novalidate @submit.prevent="save">
    <h3 class="mt-5 mb-3"></h3>
    <hr>

    <div class="mb-3" v-if="props.operationType == 'insert'">
      <label for="inputName" class="form-label">Vcard *</label>
      <input type="text" class="form-control" id="inputName" placeholder="Vcard" required
        v-model="editingTransaction.vcard.phone_number">
    </div>

    <div class="mb-3" v-if="props.operationType == 'update'">
      <label for="inputName" class="form-label">Vcard *</label>
      <input type="text" class="form-control" id="inputName" placeholder="Vcard" required
        v-model="editingTransaction.vcard.phone_number" :disabled="true">
    </div>

    <div class="d-flex flex-wrap justify-content-between">

      <div class="mb-3 me-3 flex-grow-1" v-if="props.operationType == 'insert'">
        <label for="inputValue" class="form-label">Value *</label>
        <input type="text" class="form-control" id="inputValue" placeholder="Value" required
          v-model="editingTransaction.value">
      </div>

      <div class="mb-3 me-3 flex-grow-1" v-if="props.operationType == 'update'">
        <label for="inputValue" class="form-label">Value *</label>
        <input type="text" class="form-control" id="inputValue" placeholder="Value" required
          v-model="editingTransaction.value" :disabled="true">
      </div>

      <div class="mb-3 ms-xs-3 flex-grow-1" v-if="props.operationType == 'update'">
        <label for="inputPaymentReference" class="form-label">Payment Reference *</label>
        <input type="text" class="form-control" id="inputPaymentReference" placeholder="Payment Reference" required
          v-model="editingTransaction.payment_reference" :disabled="true">
      </div>

      <div class="mb-3 ms-xs-3 flex-grow-1" v-if="props.operationType == 'insert'">
        <label for="inputPaymentReference" class="form-label">Payment Reference *</label>
        <input type="text" class="form-control" id="inputPaymentReference" placeholder="Payment Reference" required
          v-model="editingTransaction.payment_reference">
      </div>
    </div>

    <div class="mb-3 ms-xs-3 flex-grow-1" v-if="props.operationType == 'insert'">
      <label for="selectType" class="form-label">Type: *</label>
      <select class="form-select" id="selectType" v-model="editingTransaction.type">
        <option value="D">Debit</option>
      </select>
    </div>

    <div class="mb-3 ms-xs-3 flex-grow-1" v-if="props.operationType == 'update'">
      <label for="selectType" class="form-label">Type: *</label>
      <select class="form-select" id="selectType" v-model="editingTransaction.type">
        <option :value="null"></option>
        <option value="C">Credit</option>
        <option value="D">Debit</option>
      </select>
    </div>

    <div class="mb-3 ms-xs-3 flex-grow-1" v-if="props.operationType == 'insert'">
      <label for="selectPaymentType" class="form-label">Payment Type: *</label>
      <select class="form-select" id="selectPaymentType" v-model="editingTransaction.payment_type">
        <option :value="null"></option>
        <option value="VCARD">VCARD</option>
        <option value="MBWAY">MBWAY</option>
        <option value="PAYPAL">PAYPAL</option>
        <option value="IBAN">IBAN</option>
        <option value="MB">MB</option>
        <option value="VISA">VISA</option>
      </select>
    </div>

    <div class="mb-3 ms-xs-3 flex-grow-1" v-if="props.operationType == 'update'">
      <label for="selectPaymentType" class="form-label">Payment Type: *</label>
      <select class="form-select" id="selectPaymentType" v-model="editingTransaction.payment_type" :disabled="true">
        <option :value="null"></option>
        <option value="VCARD">VCARD</option>
        <option value="MBWAY">MBWAY</option>
        <option value="PAYPAL">PAYPAL</option>
        <option value="IBAN">IBAN</option>
        <option value="MB">MB</option>
        <option value="VISA">VISA</option>
      </select>
    </div>

    <div class="mb-3 ms-xs-3 flex-grow-1">
      <label for="inputDescription" class="form-label">Description *</label>
      <input type="text" class="form-control" id="inputDescription" placeholder="Description" required
        v-model="editingTransaction.description">
    </div>

    <div class="d-flex flex-wrap justify-content-between">
    </div>

    <div class="mb-3 d-flex justify-content-center ">
      <button type="button" class="btn btn-success px-5 mx-2" @click="save">Save</button>
      <button type="button" class="btn btn-dark px-5" @click="cancel">Cancel</button>
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

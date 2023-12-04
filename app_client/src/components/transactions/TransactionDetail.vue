<script setup>
import { ref, watch, computed, onMounted } from 'vue'
import { useUserStore } from '@/stores/user.js'

const props = defineProps({
  transaction: {
    type: Object,
    required: true
  },
  operationType: {
    type: String,
    default: 'insert'  // insert / update
  },
  errors: {
    type: Object,
    required: false,
  },
})

const emit = defineEmits(['save', 'cancel'])
const userStore = useUserStore()
const flagType = props.transaction == 'D' ? true : false
const flagOperation = props.operationType == 'insert' ? false : true
const flagUser = userStore.user.user_type == 'A' && !flagOperation ? false : true

// const editingTransaction = ref({
//   vcard: userStore.user?.user_type === 'A'
//       ? ''
//       : userStore.user?.id || ''
//   ,
//   value: '',
//   payment_reference: props.transaction.payment_type,
//   type: props.transaction.type,
//   description: '',
// });

props.transaction.vcard = userStore.user?.user_type == 'A' ? '' : userStore.user.id;

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
  emit('cancel', editingTransaction.value)
}

</script>

<template>
  <form class="row g-3 needs-validation" novalidate @submit.prevent="save">
    <h3 class="mt-5 mb-3"></h3>
    <hr>

    <div class="mb-3" v-if="flagOperation">
      <div class="mb-3">
        <label for="inputName" class="form-label">Vcard *</label>
        <input type="text" class="form-control" id="inputName" placeholder="Vcard Phone Number" required
          v-model="editingTransaction.vcard.phone_number" :disabled=flagUser>
        <field-error-message :errors="errors" fieldName="vcard"></field-error-message>
      </div>
    </div>

    <div class="mb-3" v-if="!flagOperation">
      <div class="mb-3">
        <label for="inputName" class="form-label">Vcard *</label>
        <input type="text" class="form-control" id="inputName" placeholder="Vcard Phone Number" required
          v-model="editingTransaction.vcard" :disabled=flagUser>
        <field-error-message :errors="errors" fieldName="vcard"></field-error-message>
      </div>
    </div>

    <div class="d-flex flex-wrap justify-content-between">

      <div class="mb-3 me-3 flex-grow-1">
        <label for="inputValue" class="form-label">Value *</label>
        <input type="text" class="form-control" id="inputValue" placeholder="Value" required
          v-model="editingTransaction.value" :disabled=flagOperation>
        <field-error-message :errors="errors" fieldName="value"></field-error-message>
      </div>

      <div class="mb-3 ms-xs-3 flex-grow-1">
        <label for="inputPaymentReference" class="form-label">Payment Reference *</label>
        <input type="text" class="form-control" id="inputPaymentReference" placeholder="Payment Reference" required
          v-model="editingTransaction.payment_reference" :disabled=flagOperation>
        <field-error-message :errors="errors" fieldName="payment_reference"></field-error-message>
      </div>
    </div>

    <div class="mb-3 ms-xs-3 flex-grow-1">
      <label for="selectType" class="form-label">Type: *</label>
      <select class="form-select" id="selectType" v-model="editingTransaction.type" required :disabled=flagOperation>
        <option v-show=flagOperation value="null"></option>
        <option value="D" :selected=flagType>Debit</option>
        <option v-show=flagOperation value="C" :selected=flagType>Credit</option>
      </select>
<field-error-message :errors="errors" fieldName="type"></field-error-message>
    </div>

    <div class="mb-3 ms-xs-3 flex-grow-1">
      <label for="selectPaymentType" class="form-label">Payment Type: *</label>
      <select class="form-select" id="selectPaymentType" v-model="editingTransaction.payment_type" :disabled=flagOperation
        required>
        <option :value="null"></option>
        <option value="VCARD">VCARD</option>
        <option value="MBWAY">MBWAY</option>
        <option value="PAYPAL">PAYPAL</option>
        <option value="IBAN">IBAN</option>
        <option value="MB">MB</option>
        <option value="VISA">VISA</option>
      </select>
      <field-error-message :errors="errors" fieldName="payment_type"></field-error-message>
    </div>

    <div class="mb-3 ms-xs-3 flex-grow-1">
      <label for="inputDescription" class="form-label">Description </label>
      <input type="text" class="form-control" id="inputDescription" placeholder="Description"
        v-model="editingTransaction.description">
      <field-error-message :errors="errors" fieldName="description"></field-error-message>
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

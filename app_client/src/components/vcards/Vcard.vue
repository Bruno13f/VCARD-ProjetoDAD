<script setup>
import axios from 'axios'
import { ref, watch, computed, onMounted} from 'vue'
import VcardDetail from "./VcardDetail.vue"
import { useRouter, onBeforeRouteLeave } from 'vue-router'
import { useToast } from "vue-toastification"

const toast = useToast()
const router = useRouter()

const newVcard = () => {
  return {
    phone_number: null,
    name: '',
    email: '',
    photo_url: '',
    password: '',
    confirmation_code: '',
    blocked: 0,
    balance: 0,
    max_debit: 2500,
    created_at: null,
    updated_at: null,
    deleted_at: null,
  }
}

  const vcard = ref(newVcard())  
  const users = ref([]) 
  const errors = ref(null)
  const confirmationLeaveDialog = ref(null)
  let originalValueStr = ''


const loadVcard = async (phone_number) => {
  originalValueStr = ''
  errors.value = null
  if (!phone_number || phone_number < 0) {
    vcard.value = newVcard()
    originalValueStr = JSON.stringify(vcard.value)
  } else {
    try {
      const response = await axios.get('vcards/' + phone_number)
      vcard.value = response.data.data
      originalValueStr = JSON.stringify(vcard.value)
    } catch (error) {
      console.log(error)
    }
  }
}

const save = async () => {
  errors.value = null
  if (operation.value == 'insert') {
    try {
      const response = await axios.post('vcards', vcard.value)
      vcard.value = response.data.data
      originalValueStr = JSON.stringify(vcard.value)
      console.log(response)
      toast.success('Vcard #' + response.data.data.phone_number + ' was created successfully.')
      router.back();
    } catch (error) {
      if (error.response.status == 422) {
        errors.value = error.response.data.errors
        toast.error('Vcard was not created due to validation errors!')
      } else {
        toast.error('Vcard was not created due to unknown server error!')
      }
    }
  } else {
    try {
      const response = await axios.put('vcards/' + props.phone_number, vcard.value)
      vcard.value = response.data.data
      originalValueStr = JSON.stringify(vcard.value)
      console.log('Vcard Updated')
      console.dir(response.data.data)
      toast.success('Vcard #' + response.data.data.phone_number + ' was edited successfully.')
      router.back()
    } catch (error) {
      if (error.response.status == 422) {
        errors.value = error.response.data.errors
        console.log(errors)
        toast.error('Vcard was not edited due to validation errors!')
      } else {
        toast.error('Vcard was not edited due to unknown server error!')
      }
    }
  }
}


const cancel = () => {
  originalValueStr = JSON.stringify(vcard.value)  
  router.back()
}

const props = defineProps({
  phone_number: {
    type: Number,
    default: null
  }
})

const operation = computed(() => {
  return (!props.phone_number || (props.phone_number < 0)) ? 'insert' : 'update'
})

watch(
  () => props.phone_number,
  (newValue) => {
    loadVcard(newValue)
  }, {
  immediate: true,
}
)

onMounted(async () => {
  users.value = []
  try {
    const response = await axios.get('users')
    users.value = response.data.data
  } catch (error) {
    console.log(error)
  }
})

let nextCallBack = null
const leaveConfirmed = () => {
  if (nextCallBack) {
    nextCallBack()
  }
}

onBeforeRouteLeave((to, from, next) => {
  nextCallBack = null;
  let newValueStr = JSON.stringify(vcard.value);
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
  <VcardDetail 
  :operationType="operation" 
  :vcard="vcard" 
  :users="users" 
  :errors="errors" 
  @save="save" 
  @cancel="cancel">
  </VcardDetail>
</template>

<script setup>
import axios from 'axios'
import UserDetail from "./UserDetail.vue"
import { useToast } from "vue-toastification"
import { useUserStore } from '../../stores/user.js'
import { ref, watch} from 'vue'
import { useRouter, onBeforeRouteLeave } from 'vue-router'

const toast = useToast()
const router = useRouter()
const errors = ref([]) 
const userStore = useUserStore()
const confirmationLeaveDialog = ref(null)
let originalValueStr = ''

const props = defineProps({
  id: {
    type: Number,
    default: null
  }
})

const newUser = () => {
  return {
    id: null,
    name: '',
    email: '',
    photo_url: null
  }
}

const loadUser = async (id) => {
  originalValueStr = ''
  errors.value = null
  if (!id || (id < 0)) {
    user.value = newUser()
  } else {
      try {
        const response = await axios.get('users/' + id)
        user.value = response.data.data
        originalValueStr = JSON.stringify(user.value)
      } catch (error) {
        console.log(error)
      }
  }
}

const save = async () => {
  errors.value = null
  try {
    const response = await axios.put('admins/' + props.id, user.value)
    user.value = response.data.data
    originalValueStr = JSON.stringify(user.value)
    toast.success('User #' + user.value.id + ' was updated successfully.')
    if (user.value.id == userStore.userId) {
      await userStore.loadUser()
    }
    router.back()
  } catch (error) {
    if (error.response.status == 422) {
      errors.value = error.response.data.errors
      toast.error('User #' + props.id + ' was not updated due to validation errors!')
    } else {
      toast.error('User #' + props.id + ' was not updated due to unknown server error!')
    }
  }
}

const cancel = () => {
  originalValueStr = JSON.stringify(user.value)
  router.back()
}

const user = ref(newUser())

watch(
  () => props.id,
  (newValue) => {
    loadUser(newValue)
  },
  { immediate: true }
)

let nextCallBack = null
const leaveConfirmed = () => {
  if (nextCallBack) {
    nextCallBack()
  }
}



onBeforeRouteLeave((to, from, next) => {
  nextCallBack = null
  let newValueStr = JSON.stringify(user.value)
  if (originalValueStr != newValueStr) {
    nextCallBack = next
    confirmationLeaveDialog.value.show()
  } else {
    next()
  }
}) 
</script>

<template>
  <confirmation-dialog
    ref="confirmationLeaveDialog"
    confirmationBtn="Discard changes and leave"
    msg="Do you really want to leave? You have unsaved changes!"
    @confirmed="leaveConfirmed"
  >
  </confirmation-dialog>  
  <user-detail 
  :user="user" 
  @save="save" 
  @cancel="cancel">
  </user-detail>
</template>

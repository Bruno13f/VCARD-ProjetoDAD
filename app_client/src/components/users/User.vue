<script setup>
import axios from 'axios'
import { ref, watch} from 'vue'
import UserDetail from "./UserDetail.vue"

import { useRouter, onBeforeRouteLeave } from 'vue-router'
const router = useRouter()

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

const loadUser = (id) => {
  if (!id || (id < 0)) {
    user.value = newUser()
  } else {
    axios.get('users/' + id)
      .then((response) => {
        user.value = response.data.data
      })
      .catch((error) => {
        console.log(error)
      })
  }
}

const save = () => {
  axios.put('users/' + props.id, user.value)
    .then((response) => {
      console.log('User Updated')
      console.dir(response.data.data)
    })
    .catch((error) => {
      console.dir(error)
    })
}

const cancel = () => {
  // Replace this code to navigate back
  router.back()
  //loadUser(props.id)
}

const user = ref(newUser())

watch(
  () => props.id,
  (newValue) => {
    loadUser(newValue)
  },
  { immediate: true }
)

const initialUserState = ref(JSON.stringify(newUser()))

onBeforeRouteLeave((to, from, next) => {
  if (JSON.stringify(user.value) !== initialUserState.value) {
    if (window.confirm("You have unsaved changes. Do you really want to leave?")) {
      next();
    } else {
      next(false);
    }
  } else {
    next();
  }
});
</script>

<template>
  <user-detail 
  :user="user" 
  @save="save" 
  @cancel="cancel">
  </user-detail>
</template>

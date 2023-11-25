<script setup>
  import axios from 'axios'
  import { ref, computed, onMounted } from 'vue'
  import {useRouter} from 'vue-router'
  import UserTable from "./UserTable.vue"

  const users = ref([])
  const router = useRouter()

  const totalUsers = computed(() => {
    return users.value.length
  })

  const filterTypeOfUser = ref(null)

  const filteredUsers = computed(()=>{
    return users.value.filter(user =>
        (!filterTypeOfUser.value
          || filterTypeOfUser.value == user.user_type
        ))
  })

  const loadUsers = () => {
    axios.get('users')
        .then((response) => {
          users.value = response.data.data
        })
        .catch((error) => {
          console.log(error)
        })
    }

  const editUser = (user) => {
      console.log('Navigate to Edit User with ID = ' + user.id)
      router.push({name: 'User', params: { id: user.id }})
  }

  onMounted (() => {
    loadUsers()
  })
</script>

<template>
  <div class="d-flex justify-content-between">
    <div class="mx-2">
      <h3 class="mt-4">Users</h3>
    </div>
    <div class="mx-2 total-filtro">
      <h5 class="mt-4">Total: {{ totalUsers }}</h5>
    </div>
  </div>
  <hr>
  <div class="mb-3 d-flex justify-content-between flex-wrap">
    <div class="mx-2 mt-2 filter-div">
      <label
        for="selectTypeOfUser"
        class="form-label"
      >Type of User:</label>
      <select
        class="form-select"
        id="selectTypeOfUser"
        v-model="filterTypeOfUser"
      >
        <option :value="null"></option>
        <option value="A">Administrator</option>
        <option value="V">Vcard Owner</option>
      </select>
    </div>
  </div>
  <user-table
    :users="filteredUsers"
    :showId="false"
    @edit="editUser"
  ></user-table>
</template>

<style scoped>
.filter-div {
  min-width: 12rem;
}
.total-filtro {
  margin-top: 2.3rem;
}
</style>


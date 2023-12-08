<script setup>
  import axios from 'axios'
  import { ref, computed, onMounted } from 'vue'
  import {useRouter} from 'vue-router'
  import VcardTable from "./VcardTable.vue"
  import { Bootstrap5Pagination } from 'laravel-vue-pagination';

  import { useToast } from "vue-toastification"
  const toast = useToast()

  const paginationData = ref({})
  const router = useRouter();

  const loadVcards = async (page = 1) => {
    try{
      const response = await axios.get(`vcards?page=${page}`)
      vcards.value = response.data.data
      paginationData.value = response.data
    }catch(error){
      console.log(error)
    }
  }

  const loadUsers = async () => {
    try{
      const response = await axios.get('users')
      users.value = response.data.data
    }catch(error){
      console.log(error)
    }
  }

  const addVcard = () => {
      router.push({ name: 'NewVcard'})
      console.log("Navigate to New Vcard")
  }
  
  const editVcard = (vcard) => {
      router.push({name: 'Vcard', params: { phone_number: vcard.phone_number }})
      console.log('Navigate to Edit Vcard with Phone_Number = ' + vcard.phone_number)
  }

  const deleteVcard = async (vcard) => { 
    try{
      const response = await axios.delete('vcards/' + vcard.phone_number)
      let deletedVcard = response.data.data
      let idx = vcards.value.findIndex((t) => t.phone_number === deletedVcard.phone_number)
      if (idx >= 0) {
        vcards.value.splice(idx, 1)
      }
      toast.success('Vcard ' + response.data.data.phone_number + ' was deleted successfully.')
    }catch(error){
      if (error.response.status == 422){
      toast.error("Can't delete Vcard - Balance different than 0")
      }else {
        toast.error("Vcard wasn't deleted due to unknown server error!")
      }
    }
  }

  const blockVcard = async (vcard) => {
    let blocked = vcard.blocked ? 'unblocked' : 'blocked'
    try{
      const response = await axios.patch('vcards/' + vcard.phone_number + '/blocked', { blocked: vcard.blocked ? '0' : '1' })
      toast.success('Vcard ' + response.data.data.phone_number + ' was ' + blocked + ' successfully.')

    }catch(error){
      toast.error('Vcard was not ' + blocked + ' due to unknown server error!')
    }
    loadVcards()
  }
  

  const vcards = ref([])
  const users = ref([])
  const filterByOwnerId = ref(null)
  const filterByBlocked = ref(null)

  const filteredVcards = computed(()=>{
    return vcards.value.filter(p =>
        (!filterByOwnerId.value
          || filterByOwnerId.value == p.phone_number
        ) &&
        (!filterByBlocked.value
          || filterByBlocked.value == p.blocked
        ))
  })

  const totalVcards = computed(()=>{
    return vcards.value.reduce((c, p) =>
        (!filterByOwnerId.value
          || filterByOwnerId.value == p.phone_number
        ) &&
          (!filterByBlocked.value
            || filterByBlocked.value == p.blocked
          ) ? c + 1 : c, 0)
  })

  onMounted(() => {
    loadUsers()
    loadVcards()
  })

</script>

<template>
  <div class="d-flex justify-content-between">
    <div class="mx-2">
      <h3 class="mt-4">Vcards</h3>
    </div>
    <div class="mx-2 total-filtro">
      <h5 class="mt-4">Total: {{ totalVcards }}</h5>
    </div>
  </div>
  <hr>
  <div class="mb-3 d-flex justify-content-between flex-wrap">
    <div class="mx-2 mt-2 flex-grow-1 filter-div">
      <label
        for="selectOwner"
        class="form-label"
      >Filter by owner:</label>
      <select
        class="form-select"
        id="selectOwner"
        v-model="filterByOwnerId"
      >
        <option :value="null"></option>
        <option
          v-for="user in users"
          :key="user.id"
          :value="user.id"
        >{{user.name}}</option>
      </select>
    </div>
    <div class="mx-2 mt-2 flex-grow-1 filter-div">
      <label
        for="selectStatus"
        class="form-label"
      >Filter by blocked:</label>
      <select
        class="form-select"
        id="selectStatus"
        v-model="filterByBlocked"
      >
        <option :value="null"></option>
        <option value="1">Blocked</option>
        <option value="0">Not Blocked</option>
      </select>
    </div>
    <div class="mx-2 mt-2">
      <button
        type="button"
        class="btn btn-success px-4 btn-addprj"
        @click="addVcard"
      ><i class="bi bi-xs bi-plus-circle"></i>&nbsp; Add Vcard</button>
    </div>
  </div>
  <vcard-table
    :vcards="filteredVcards"
    :showId="true"
    :showDates="true"
    @edit="editVcard"
    @delete="deleteVcard"
    @block="blockVcard"
  ></vcard-table>
  <Bootstrap5Pagination
  :data="paginationData"
  @pagination-change-page="loadVcards"
  :limit="2">
  </Bootstrap5Pagination>
</template>

<style scoped>
.filter-div {
  min-width: 12rem;
}
.total-filtro {
  margin-top: 0.35rem;
}
.btn-addprj {
  margin-top: 1.85rem;
}
</style>

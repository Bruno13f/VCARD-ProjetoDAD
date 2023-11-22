<script setup>
  import axios from 'axios'
  import { ref, computed, onMounted } from 'vue'
  import VcardTable from "./VcardTable.vue"

  const loadVcards = () => {
      axios.get('vcards')
        .then((response) => {
          vcards.value = response.data.data
        })
        .catch((error) => {
          console.log(error)
        })
  }

  const loadUsers = () => {
      axios.get('users')
        .then((response) => {
          users.value = response.data.data
        })
        .catch((error) => {
          console.log(error)
        })
  }

  const addVcard = () => {
      console.log('Navigate to New Vcard')
  }
  
  const editVcard = (vcard) => {
      console.log('Navigate to Edit Vcard with Phone_Number = ' + vcard.phone_number)
  }

  const deleteVcard = (vcard) => {
      axios.delete('vcards/' + vcard.phone_number)
        .then((response) => {
          let deletedVcard = response.data.data
          let idx = vcards.value.findIndex((t) => t.id === deletedVcard.id)
          if (idx >= 0) {
            vcards.value.splice(idx, 1)
          }
        })
        .catch((error) => {
          console.log(error)
        })
  }
  

  const vcards = ref([])
  const users = ref([])
  const filterByOwnerId = ref(null)
  const filterByStatus = ref(null)

  const filteredVcards = computed(()=>{
    return vcards.value.filter(p =>
        (!filterByOwnerId.value
          || filterByOwnerId.value == p.responsible_id
        ) &&
        (!filterByStatus.value
          || filterByStatus.value == p.status
        ))
  })

  const totalVcards = computed(()=>{
    return vcards.value.reduce((c, p) =>
        (!filterByOwnerId.value
          || filterByOwnerId.value == p.responsible_id
        ) &&
          (!filterByStatus.value
            || filterByStatus.value == p.status
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
      <h5 class="mt-4">Total: {{ totalVcardss }}</h5>
    </div>
  </div>
  <hr>
  <div class="mb-3 d-flex justify-content-between flex-wrap">
    <div class="mx-2 mt-2 flex-grow-1 filter-div">
      <label
        for="selectStatus"
        class="form-label"
      >Filter by status:</label>
      <select
        class="form-select"
        id="selectStatus"
        v-model="filterByStatus"
      >
        <option :value="null"></option>
        <option value="D">Debit</option>
        <option value="C">Credit</option>
      </select>
    </div>
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
  ></vcard-table>
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

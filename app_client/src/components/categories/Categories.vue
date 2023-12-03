<script setup>

import axios from 'axios'
import { ref, computed, onMounted } from 'vue'
import { useUserStore } from '@/stores/user.js'
import CategoryTable from "./CategoryTable.vue"

const categories = ref([])
const userStore = useUserStore()

const loadCategories = async () => {
    try{
        const response1 = await axios.get(`vcards/${userStore.user.id}/categories`)
        // const response2 = await axios.get("defaultCategories")
        // const response = response1.data.data.concat(response2.data.data);
        categories.value = response1.data.data
        console.log(categories)
        
    }catch(error){
        console.log(error)
    }
}

// falta edit, delete e filter

onMounted(() => {
    loadCategories()
})

</script>

<template>
    <div class="d-flex justify-content-between">
      <div class="mx-2">
        <h3 class="mt-4">Categories</h3>
      </div>
      <div class="mx-2 total-filtro">
        <h5 class="mt-4">Total: {{ }}</h5>
      </div>
    </div>
    <hr>
    <div class="mb-3 d-flex justify-content-between flex-wrap">
      <div class="mx-2 mt-2 filter-div">
        <label
          for="selectType"
          class="form-label"
        >Filter by Type:</label>
        <select
          class="form-select"
          id="selectType"
        >
          <option :value="null"></option>
          <option value="U">User</option>
          <option value="D">Default</option>
        </select>
      </div>
    </div>
    <category-table
    :categories="categories"
  ></category-table>
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
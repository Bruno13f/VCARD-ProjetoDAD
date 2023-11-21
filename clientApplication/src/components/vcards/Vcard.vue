<script setup>
  import axios from 'axios'
  import { ref, watch, computed, onMounted} from 'vue'
  import VcardDetail from "./VcardDetail.vue"

  const newVcard = () => { 
    return {
      id: null,
      name: '',
      responsible_id: 1,  // Change it later
      status: 'P',
      preview_start_date: null,
      preview_end_date: null,
      real_start_date: null,
      real_end_date: null,
      total_hours: null,
      billed: false,
      total_price: null,
    }
  }

  const loadVcard = (id) => {
      if (!id || (id < 0)) {
        vcard.value = newVcard()
      } else {
        axios.get('vcards/' + id)
          .then((response) => {
            vcard.value = response.data.data
          })
          .catch((error) => {
            console.log(error)
          })
      }
    }

  const save = () => {
      if (operation.value == 'insert') {
        axios.post('vcards', vcard.value)
          .then((response) => {
            console.log('Vcard Created')
            console.dir(response.data.data)
          })
          .catch((error) => {
            console.dir(error)
          })
      } else {
        axios.put('vcards/' + props.id, vcard.value)
          .then((response) => {
            console.log('Vcard Updated')
            console.dir(response.data.data)
          })
          .catch((error) => {
            console.dir(error)
          })
      }
    }


  const cancel = () => {
      // Replace this code to navigate back
      loadVcard(props.id)
  }

  const props = defineProps({
      id: {
        type: Number,
        default: null
      }
    })

  const vcard = ref(newVcard())  
  const users = ref([])  

  const operation = computed(() => {
    return (!props.id || props.id < 0) ? 'insert' : 'update'
  })

  watch(
    () => props.id, 
    (newValue) => {
          loadVcard(newValue)
    }, {
      immediate: true,
    }
  )

  onMounted (() => {
    users.value = []
    axios.get('users')
      .then((response) => {
        users.value = response.data.data
      })
      .catch((error) => {
        console.log(error)
      })
  })
</script>


<template>
  <VcardDetail
    :operationType="operation"
    :vcard="vcard"
    :users="users"
    @save="save"
    @cancel="cancel"
  ></VcardDetail>
</template>

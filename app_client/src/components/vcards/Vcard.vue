<script setup>
  import axios from 'axios'
  import { ref, watch, computed, onMounted} from 'vue'
  import VcardDetail from "./VcardDetail.vue"
  import {useRouter} from 'vue-router'
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
      created_at:null,
      updated_at:null,
      deleted_at:null,
    }
  }

  const loadVcard = async (phone_number) => {
      if (!phone_number || phone_number <0) {
        vcard.value = newVcard()
      } else {
        try{
          const response = await axios.get('vcards/' + phone_number)
          vcard.value = response.data.data
        }catch(error){
          console.log(error)
        }
      }
    }

  const save = async () => {
      if (operation.value == 'insert') {
        try {
          const response = await axios.post('vcards', vcard.value)
          console.log(response)
          toast.success('Vcard #' + response.data.data.phone_number + ' was created successfully.')
          router.back();
        } catch (error) {
          if (error.response.status == 422) {
            toast.error('Vcard was not created due to validation errors!')
          } else {
            toast.error('Vcard was not created due to unknown server error!')
          }
        }
      } else {
        try {
          console.log(vcard.value)
          const response = await axios.put('vcards/' + props.phone_number, vcard.value)
          console.log('Vcard Updated')
          console.dir(response.data.data)
          toast.success('Vcard #' + response.data.data.phone_number + ' was edited successfully.')
          router.back()
        } catch (error) {
          if (error.response.status == 422) {
            toast.error('Vcard was not edited due to validation errors!')
          } else {
            toast.error('Vcard was not edited due to unknown server error!')
          }
        }
      }
    }


  const cancel = () => {
      // Replace this code to navigate back
      router.back()
      //loadVcard(props.phone_number)
  }

  const props = defineProps({
      phone_number: {
        type: Number,
        default: null
      }
    })

  const vcard = ref(newVcard())  
  const users = ref([])  

  const operation = computed(() => {
    return (!props.phone_number || (props.phone_number<0 )) ? 'insert' : 'update'
  })

  watch(
    () => props.phone_number, 
    (newValue) => {
          loadVcard(newValue)
    }, {
      immediate: true,
    }
  )

  onMounted (async () => {
    users.value = []
    try{
      const response = await axios.get('users')
      users.value = response.data.data
    }catch(error){
      console.log(error)
    }
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

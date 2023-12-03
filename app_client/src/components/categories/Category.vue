<script setup>
import axios from 'axios'
import { ref, watch, computed, onMounted} from 'vue'
import { useRouter, onBeforeRouteLeave } from 'vue-router'
import { useToast } from "vue-toastification"

const toast = useToast()
const router = useRouter()

const newCategory = () => {
  return {
    id: null,
    vcard: null,
    type: '',
    name: '',
    custom_options: '',
    custom_data: '',
    created_at: null,
    updated_at: null,
    deleted_at: null,
  }
}

const category = ref(newVCategory()) 
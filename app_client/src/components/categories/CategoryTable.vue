<script setup>
  const props = defineProps({
    categories: {
      type: Array,
      default: () => [],
    }
  })

  const emit = defineEmits(['edit', 'delete', 'block'])

//   const editClick = (vcard) => {
//       emit('edit', vcard)
//   }

//   const deleteClick = (vcard) => {
//       emit('delete', vcard)
//   }

//   const blockClick = (vcard) => {
//     emit('block', vcard)
//   }

</script>

<template>
    <table class="table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Type</th>
          <th>Vcard</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="category in categories"
          :key="category.id"
        >
          <td>{{ category.name }}</td>
          <td>{{ category.type == 'C' ? 'Credit' : 'Debit'}}</td>
          <td>{{ category.vcard.phone_number + ' - ' + category.vcard.name }}</td>
        </tr>
      </tbody>
    </table>
  </template>
  
  <style scoped>
  button {
    margin-left: 3px;
    margin-right: 3px;
  }
  </style>
  
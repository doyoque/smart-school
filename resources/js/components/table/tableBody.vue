<template>
  <tbody>
    <tr v-for="item in dataBody" :key="item.id">
      <td
        class="border border-blue-600 px-4"
        :class="[
          item.id === dataBody[dataBody.length - 1].id ? 'rounded-bl-lg' : '',
        ]"
      >
        {{ item.name }}
      </td>
      <td class="border border-blue-600 px-4">{{ item.role.name }}</td>
      <td class="border border-blue-600 px-4">{{ item.username }}</td>
      <td class="border border-blue-600 px-4">{{ item.email }}</td>
      <td class="border border-blue-600 px-4">{{ item.created_at }}</td>
      <td class="border border-blue-600 px-4">{{ item.updated_at }}</td>
      <td
        class="border border-blue-600 px-4"
        :class="[
          item.id === dataBody[dataBody.length - 1].id ? 'rounded-br-lg' : '',
        ]"
      >
        <button
          class="bg-green-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded"
        >
          update
        </button>
        <button
          class="bg-red-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded"
        >
          delete
        </button>
      </td>
    </tr>
  </tbody>
</template>

<script>
export default {
  name: "tableBody",
  props: {
    dataBody: Array,
  },
};
</script>

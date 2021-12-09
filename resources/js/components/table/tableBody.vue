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
      <td class="border border-blue-600 px-4">
        {{ emailReplace(item.email) }}
      </td>
      <td class="border border-blue-600 px-4">{{ item.created_at }}</td>
      <td class="border border-blue-600 px-4">{{ item.updated_at }}</td>
      <td
        class="border border-blue-600 px-4 pt-2 pb-2"
        :class="[
          item.id === dataBody[dataBody.length - 1].id ? 'rounded-br-lg' : '',
        ]"
      >
        <router-link
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded"
          :to="{ name: 'detail', params: { id: item.id } }"
          >view</router-link
        >
        <router-link
          class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-4 rounded"
          :to="{ name: 'update', params: { id: item.id } }"
          >update</router-link
        >
        <a
          class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-4 rounded"
          style="cursor: pointer"
          @click="removeUser(item.id)"
        >
          delete
        </a>
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
  methods: {
    async removeUser(id) {
      await axios
        .delete(`/api/v1/user/${id}`)
        .then((res) => {
          window.location.reload();
        })
        .catch((err) => console.log(err));
    },

    emailReplace(email) {
      return email.length > 24 ? email.substring(0, 24) + "..." : email;
    },
  },
};
</script>

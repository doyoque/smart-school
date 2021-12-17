<template>
  <ul
    class="text-sm font-medium overflow-y-scroll text-gray-900 bg-white border border-gray-200 rounded-lg h-56"
  >
    <li
      v-for="item in users"
      :key="item.id"
      :class="[
        'w-full px-4 py-2 border-b border-gray-200 hover:bg-gray-400 hover:text-gray-50',
        selected.name === item.name ? 'bg-gray-300 hover:bg-gray-400' : '',
      ]"
      style="cursor: pointer"
      @click="getUser(item)"
    >
      {{ item.name }}
    </li>
  </ul>
</template>

<script>
export default {
  name: "chat-users",
  props: ["users", "selected"],
  methods: {
    getUser(user) {
      this.$emit("user", {
        id: user.id,
        name: user.name,
        email: user.email,
      });
    },
  },
};
</script>

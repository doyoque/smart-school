<template>
  <ul
    class="text-sm font-medium overflow-y-scroll text-gray-900 bg-white border border-gray-200 rounded-lg h-56"
  >
    <li
      v-for="item in users"
      :key="item.id"
      :class="[
        'w-full px-4 py-2 border-b border-gray-200 hover:bg-gray-400 hover:text-gray-50',
      ]"
      style="cursor: pointer"
      @click="getUser(item)"
    >
      {{ item.name }}
      <span
        class="inline-flex items-center justify-center px-2 py-1 mr-2 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full"
        >{{ getTotalMessage() }}</span
      >
    </li>
  </ul>
</template>

<script>
export default {
  name: "chat-users",
  props: ["users", "newMessages"],
  data() {
    return {
      countNewMessage: 0,
    };
  },
  watch: {
    countNewMessage(oldVal, newVal) {
      console.log(oldVal, newVal);
    },
  },
  methods: {
    getUser(user) {
      this.$emit("user", {
        id: user.id,
        name: user.name,
        email: user.email,
      });

      this.countNewMessage = 0;
    },
    getTotalMessage() {
      console.log(this.newMessages, "getTotalMessage");
      if (this.newMessages > 0) {
        this.countNewMessage = this.countNewMessage + 1;
      } else {
        this.countNewMessage = 0;
      }

      return this.countNewMessage;
    },
  },
};
</script>

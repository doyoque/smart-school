<template>
  <div class="flex flex-row items-center bottom-0 my-2 w-full">
    <div
      class="ml-2 flex flex-row border-gray items-center w-full border rounded-3xl h-12 px-2"
    >
      <div class="w-full">
        <input
          :disabled="!user"
          type="text"
          id="message"
          class="border rounded-2xl border-transparent w-full focus:outline-none text-sm h-10 flex items-center"
          placeholder="Type your message...."
          v-model="newMessage"
          @keyup.enter="sendMessage"
        />
      </div>
    </div>
    <div class="ml-3 mr-2"></div>
    <div>
      <button
        id="self"
        :disabled="!user"
        class="flex items-center justify-center h-10 w-10 mr-2 rounded-full bg-gray-200 hover:bg-gray-300 text-indigo-800 focus:outline-none"
        @click="sendMessage"
      >
        <svg
          class="w-5 h-5 transform rotate-90 -mr-px"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
          ></path>
        </svg>
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: "chat-form",
  props: ["user", "sender"],
  data() {
    return {
      newMessage: "",
    };
  },
  methods: {
    sendMessage() {
      this.$emit("messageSent", {
        user: this.user,
        receiver: this.sender,
        message: this.newMessage,
      });

      this.newMessage = "";
    },
  },
};
</script>

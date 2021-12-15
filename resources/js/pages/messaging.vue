<template>
  <div class="bg-white text-black rounded-lg border shadow-lg p-10">
    <div class="grid grid-cols-12">
      <div class="col-start-1 col-end-6">
        <chatUsers v-on:user="getUser" :users="users" />
      </div>
      <div class="flex flex-col col-start-6 col-end-13 bg-white">
        <chatMessage :messages="messages" :user="sender()" />
        <chatForm v-on:messageSent="addMessage" :user="selectedUser" />
      </div>
    </div>
  </div>
</template>

<script>
import chatMessage from "@components/chat/chatMessage.vue";
import chatForm from "@components/chat/chatForm.vue";
import chatUsers from "@components/chat/chatUsers.vue";

export default {
  namae: "messaging",
  components: {
    chatMessage,
    chatForm,
    chatUsers,
  },
  data() {
    return {
      users: null,
      messages: [],
      selectedUser: null,
    };
  },
  created() {
    this.fetchUser();

    Echo.private(`chat.${this.sender().id}`).listen("MessageEvent", (e) => {
      console.log(e, "lkasdjfklsdjl");
    });
  },
  methods: {
    async fetchUser() {
      await axios
        .get(`/api/v1/message/user`)
        .then((res) => (this.users = res.data))
        .catch((err) => console.log(err));
    },
    async fetchMessages(userId) {
      return await axios
        .get(`/api/v1/message?receiver_id=${userId}`)
        .then((res) => {
          this.messages = res.data;
        })
        .catch((err) => console.log(err));
    },
    async addMessage(newMessage) {
      const { user, message } = newMessage;
      Echo.join(`chat.${user.id}`)
        .here((users) => console.log(users))
        .joining((user) => console.log(user))
        .leaving((user) => console.log(user))
        .error((err) => console.log(err));

      if (this.messages.length > 0) {
        this.messages.unshift(newMessage);
      } else {
        this.messages.push(newMessage);
      }

      let payload = {
        receiver_id: user.id,
        message: message,
      };

      await axios.post(`/api/v1/message`, payload).catch((err) => {
        console.log(err);
      });
    },
    getUser(user) {
      Echo.leave(`chat.${this.sender().id}`);

      this.fetchMessages(user.id);
      this.selectedUser = user;
    },
    sender() {
      return JSON.parse(localStorage.getItem("user"));
    },
  },
};
</script>

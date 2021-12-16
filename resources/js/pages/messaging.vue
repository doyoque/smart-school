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
      user: null,
    };
  },
  created() {
    this.fetchUser();

    Echo.private(`chat.${this.sender().id}`)
      .listen("MessageEvent", (e) => {
        console.log(e.message);
        this.messages
          .push({
            message: e.message.message,
            user: e.user,
          })
          .reverse();
      })
      .error((err) => console.log(err));
  },
  methods: {
    async fetchUser() {
      await axios
        .get(`/api/v1/message/user`)
        .then((res) => (this.users = res.data))
        .catch((err) => console.log(err));
    },
    async fetchMessages(userId) {
      Echo.join(`chat.${userId}`)
        .joining((user) => {
          console.log(user);
        })
        .error((err) => console.log(err, "err"));

      return await axios
        .get(`/api/v1/message?receiver_id=${userId}`)
        .then((res) => {
          this.messages = res.data;
        })
        .catch((err) => console.log(err));
    },
    async addMessage(newMessage) {
      const { user, message } = newMessage;
      this.user = user;
      Echo.leaveChannel(`chat.${user.id}`);
      Echo.join(`chat.${user.id}`)
        .joining((user) => {
          console.log(user);
        })
        .listen("MessageEvent", (e) => {
          console.log(e);
        });

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
      this.fetchMessages(user.id);
      this.selectedUser = user;
    },
    sender() {
      return JSON.parse(localStorage.getItem("user"));
    },
  },
  mounted() {
    console.log(this.user);
  },
};
</script>

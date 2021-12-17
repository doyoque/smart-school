<template>
  <div class="bg-white text-black rounded-lg border shadow-lg p-10">
    <div class="grid grid-cols-12">
      <div class="col-start-1 col-end-6">
        <chatUsers
          v-on:user="getUser"
          :users="users"
          :selected="selectedUser"
        />
      </div>
      <div class="flex flex-col col-start-6 col-end-13 bg-white">
        <chatMessage :messages="messages" :user="sender()" />
        <chatForm
          v-on:messageSent="addMessage"
          :user="selectedUser"
          :sender="sender()"
        />
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
      selectedUser: {
        id: null,
        name: null,
        email: null,
      },
      user: null,
    };
  },
  created() {
    this.fetchUser();

    Echo.private(`chat.${this.sender().id}`)
      .listen("MessageEvent", (e) => {
        let { user, message } = e;
        let newMessage = {
          message: message.message,
          receiver_id: message.receiver_id,
          user: user,
          user_id: user.id,
        };

        if (this.selectedUser !== null) {
          if (this.selectedUser.id === newMessage.user_id)
            this.messages.unshift(newMessage);
        }
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
      const { user, message, receiver } = newMessage;
      let modifyNewMessage = {
        message: message,
        receiver_id: receiver.id,
        user: user,
        user_id: receiver.id,
      };

      this.user = user;
      Echo.leaveChannel(`chat.${user.id}`);
      Echo.join(`chat.${user.id}`)
        .joining((user) => {
          console.log(user);
        })
        .leaving((user) => console.log(user));

      if (this.messages.length > 0) {
        this.messages.unshift(modifyNewMessage);
      } else {
        this.messages.push(modifyNewMessage);
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
};
</script>

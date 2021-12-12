<template>
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
          <div class="panel-heading">Chats</div>

          <div class="panel-body">
            <chatMessage :messages="messages" />
          </div>
          <div class="panel-footer">
            <chatForm v-on:messageSent="addMessage" :user="user" />
            <!-- <chat-form
                        v-on:messagesent="addMessage"
                        :user="{{ Auth::user() }}"
                    ></chat-form> -->
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import chatForm from "@components/chat/chatForm.vue";
import chatMessage from "@components/chat/chatMessage.vue";

export default {
  name: "message",
  components: {
    chatForm,
    chatMessage,
  },
  data() {
    return {
      messages: [],
      user: null,
    };
  },
  created() {
    this.getUser();
    this.fetchMessages();
  },
  methods: {
    async fetchMessages() {
      await axios
        .get(`/api/v1/message`)
        .then((res) => {
          this.messages = res.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    async addMessage(message) {
      this.messages.push(message);

      await axios
        .post(`/api/v1/message`, message)
        .then((res) => {
          console.log(res);
        })
        .catch((err) => {
          console.log(err);
        });
    },

    getUser() {
      return (this.user = JSON.parse(localStorage.getItem("user")));
    },
  },
  mounted() {},
  // props: ChatForm[("user_id", "username", "message", "created_at")],
  // computed: {
  //   formatDate() {
  //     let dateAndTime = new Date(this.created_at);
  //     let year = dateAndTime.getFullYear();
  //     let months = [
  //       "Jan",
  //       "Feb",
  //       "Mar",
  //       "Apr",
  //       "May",
  //       "Jun",
  //       "Jul",
  //       "Aug",
  //       "Sept",
  //       "Oct",
  //       "Nov",
  //       "Dec",
  //     ];
  //     let month = months[dateAndTime.getMonth()];
  //     let day = dateAndTime.getDate();
  //     let time = dateAndTime.getHours() + ":" + dateAndTime.getMinutes();
  //     return `${day}  ${month}  ${year}  ${time}`;
  //   },
  // },
};
</script>

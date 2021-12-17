<template>
  <div class="w-full bg-grey-lightest" style="padding-top: 4rem">
    <div class="container mx-auto py-8">
      <div class="w-5/6 lg:w-1/2 mx-auto bg-white rounded shadow">
        <div class="py-4 px-8 text-black text-xl border-b border-grey-lighter">
          Login
        </div>
        <div class="py-4 px-8">
          <div class="mb-4">
            <label
              class="block text-grey-darker text-sm font-bold mb-2"
              for="school_name"
              >School id</label
            >
            <input
              class="appearance-none border rounded w-full py-2 px-3 text-grey-darker"
              id="school_id"
              type="text"
              placeholder="Your school id (number)"
              v-model="school_id"
            />
          </div>
          <div class="mb-4">
            <label
              class="block text-grey-darker text-sm font-bold mb-2"
              for="username"
              >Username</label
            >
            <input
              class="appearance-none border rounded w-full py-2 px-3 text-grey-darker"
              id="username"
              type="text"
              placeholder="Your username account"
              v-model="username"
            />
          </div>
          <div class="mb-4">
            <label
              class="block text-grey-darker text-sm font-bold mb-2"
              for="password"
              >Password</label
            >
            <input
              class="appearance-none border rounded w-full py-2 px-3 text-grey-darker"
              id="password"
              type="password"
              placeholder="Your secure password"
              v-model="password"
            />
            <p class="text-grey text-xs mt-1">At least 8 characters</p>
          </div>
          <div class="flex items-center justify-between mt-8">
            <button
              class="bg-blue-500 hover:bg-blue-300 text-white font-bold py-2 px-4 rounded-full"
              type="button"
              @click="login"
            >
              Login
            </button>
          </div>
        </div>
      </div>
      <p class="text-center my-4">
        <router-link
          to="/signup"
          class="text-grey-dark text-sm no-underline hover:text-grey-darker"
          >Sign up</router-link
        >
      </p>
    </div>
  </div>
</template>

<script>
import router from "@/routes/route";

export default {
  name: "login",
  data() {
    return {
      username: "",
      school_id: null,
      password: "",
    };
  },
  created() {
    if (localStorage.getItem("token") !== null) {
      window.location.href = "/dashboard";
    }
  },
  methods: {
    async login() {
      let payload = {
        username: this.username,
        school_id: this.school_id,
        password: this.password,
      };
      await axios
        .post(`api/v1/login`, payload)
        .then((res) => {
          if (res.code === 200) {
            localStorage.setItem("user", JSON.stringify(res.user));
            localStorage.setItem("token", res.token);
            localStorage.setItem("role", res.role);
            window.location.href = "/dashboard";
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
};
</script>

<template>
  <div class="w-full bg-grey-lightest" style="padding-top: 4rem">
    <div class="container mx-auto py-8">
      <div class="w-5/6 lg:w-1/2 mx-auto bg-white rounded shadow">
        <div class="py-4 px-8 text-black text-xl border-b border-grey-lighter">
          Register first
        </div>
        <div class="py-4 px-8">
          <div class="flex mb-4">
            <div class="w-1/2 mr-1">
              <label
                class="block text-grey-darker text-sm font-bold mb-2"
                for="name"
                >Name</label
              >
              <input
                class="appearance-none border rounded w-full py-2 px-3 text-grey-darker"
                id="name"
                type="text"
                placeholder="Your full name"
                v-model="name"
              />
            </div>
            <div class="w-1/2 ml-1">
              <label
                class="block text-grey-darker text-sm font-bold mb-2"
                for="username"
                >Username</label
              >
              <input
                class="appearance-none border rounded w-full py-2 px-3 text-grey-darker"
                id="username"
                type="text"
                placeholder="Your username for this account"
                v-model="username"
              />
            </div>
          </div>
          <div class="mb-4">
            <label
              class="block text-grey-darker text-sm font-bold mb-2"
              for="school_name"
              >School name</label
            >
            <input
              class="appearance-none border rounded w-full py-2 px-3 text-grey-darker"
              id="school_name"
              type="text"
              placeholder="Your school name"
              v-model="school_name"
            />
          </div>
          <div class="mb-4">
            <label
              class="block text-grey-darker text-sm font-bold mb-2"
              for="email"
              >Email Address</label
            >
            <input
              class="appearance-none border rounded w-full py-2 px-3 text-grey-darker"
              id="email"
              type="email"
              placeholder="Your email address"
              v-model="email"
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
              @click="register"
            >
              Sign Up
            </button>
          </div>
        </div>
      </div>
      <p class="text-center my-4">
        <a
          href="#"
          class="text-grey-dark text-sm no-underline hover:text-grey-darker"
          >I already have an account</a
        >
      </p>
    </div>
  </div>
</template>

<script>
export default {
  name: "index",
  data() {
    return {
      name: "",
      username: "",
      email: "",
      password: "",
      school_name: "",
    };
  },
  methods: {
    async register() {
      let payload = {
        name: this.name,
        username: this.username,
        password: this.password,
        email: this.email,
        school_name: this.school_name,
      };

      await axios
        .post(`/api/v1/signup`, payload)
        .then((res) => {
          localStorage.setItem("token", res.token);
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
};
</script>

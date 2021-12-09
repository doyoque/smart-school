<template>
  <div class="w-full bg-grey-lightest" style="padding-top: 4rem">
    <div class="container mx-auto py-8">
      <div class="w-5/6 lg:w-1/2 mx-auto bg-white rounded shadow">
        <div class="py-4 px-8 text-black text-xl border-b border-grey-lighter">
          Detail User
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
                disabled
                placeholder="Your full name"
                v-model="name"
              />
            </div>
            <div class="w-1/2 mr-1">
              <label
                class="block text-grey-darker text-sm font-bold mb-2"
                for="name"
                >Role</label
              >
              <input
                class="appearance-none border rounded w-full py-2 px-3 text-grey-darker"
                id="role"
                type="text"
                disabled
                placeholder="Your full name"
                v-model="role.name"
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
                disabled
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
              disabled
              placeholder="Your school name"
              v-model="school.name"
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
              disabled
              placeholder="Your email address"
              v-model="email"
            />
          </div>
          <div class="flex items-center justify-between mt-8">
            <router-link
              to="/dashboard"
              class="bg-blue-500 hover:bg-blue-300 text-white font-bold py-2 px-4 rounded-lg"
              >back</router-link
            >
            <router-link
              :to="{ name: 'update', params: { id: id } }"
              class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg"
              >update</router-link
            >
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "detail",
  data() {
    return {
      id: "",
      name: "",
      username: "",
      email: "",
      role: {
        name: "",
      },
      school: {
        name: "",
      },
    };
  },
  async created() {
    const { data } = await this.detailUser(this.$route.params.id);
    this.id = data.id;
    this.name = data.name;
    this.username = data.username;
    this.email = data.email;
    this.role.name = data.role.name;
    this.school.name = data.school.name;
  },
  methods: {
    detailUser(id) {
      return axios.get(`/api/v1/user/${id}`).catch((err) => {
        console.log(err);
      });
    },
  },
};
</script>

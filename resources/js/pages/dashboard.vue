<template>
  <div class="bg-white text-black font-bold rounded-lg border shadow-lg p-10">
    <div class="grid grid-cols-8">
      <router-link
        to="/messaging"
        class="bg-green-500 hover:bg-green-700 text-white text-center font-bold py-1 mb-2 px-4 rounded mr-1 col-start-1 col-end-1"
      >
        messaging
      </router-link>
      <button
        class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-4 mb-2 rounded mr-1 col-start-8 col-end-8"
        @click="logOut()"
      >
        logout
      </button>
    </div>
    <div v-if="getRole() === 'school_admin'" class="grid grid-cols-8">
      <router-link
        to="/create-user"
        class="bg-blue-500 hover:bg-blue-300 text-white text-center font-bold py-1 mb-2 px-4 rounded-lg"
        >create user</router-link
      >
    </div>

    <div v-if="getRole() === 'teacher'" class="grid grid-cols-8">
      <button
        :class="[
          'font-bold py-1 px-4 mb-2 rounded mr-1',
          stateTab === true
            ? 'bg-blue-500 hover:bg-blue-700 text-white'
            : 'bg-white hover:bg-blue-500 hover:text-white text-black border-blue-500 border-2',
        ]"
        @click="changeStateTab(stateTab)"
      >
        teachers
      </button>
      <button
        :class="[
          'font-bold py-1 px-4 mb-2 rounded mr-1',
          stateTab === false
            ? 'bg-blue-500 hover:bg-blue-700 text-white'
            : 'bg-white hover:bg-blue-500 hover:text-white text-black border-blue-500 border-2',
        ]"
        @click="changeStateTab(stateTab)"
      >
        students
      </button>
    </div>
    <table class="table-auto border-separate rounded-lg border border-blue-500">
      <tableHead
        :dataHeader="tableHeader"
        :endOfHeader="tableHeader.length"
        :dataBody="tableBody"
      />
      <tableBody :dataBody="tableBody" :role="getRole()" />
    </table>
    <pagination
      :pagination="pagination"
      @paginate="getUser(queryParams())"
      :offset="4"
    />
  </div>
</template>

<script>
import router from "@/routes/route";
import Pagination from "@components/table/pagination.vue";
import TableHead from "@components/table/tableHead.vue";
import TableBody from "@components/table/tableBody.vue";

export default {
  name: "dashboard",
  components: {
    Pagination,
    TableHead,
    TableBody,
  },
  data() {
    return {
      tableHeader: [
        { id: 1, name: "name" },
        { id: 2, name: "role" },
        { id: 3, name: "username" },
        { id: 4, name: "email" },
        { id: 5, name: "created at" },
        { id: 6, name: "update at" },
        { id: 7, name: "actions" },
      ],
      searchParams: {
        name: "",
        username: "",
        email: "",
        role_id: "",
        page: "",
      },
      stateTab: false,
      tableBody: null,
      pagination: {},
      offset: 4,
    };
  },
  created() {
    if (this.getRole() === "teacher") {
      this.searchParams.role_id = 2;
      this.stateTab = true;
    } else if (this.getRole() === "student") {
      this.searchParams.role_id = 3;
      this.stateTab = false;
    }

    this.getUser(this.queryParams());
  },
  methods: {
    changeStateTab(change) {
      if (change === true) {
        this.stateTab = false;
        this.searchParams.role_id = 3;
      } else {
        this.searchParams.role_id = 2;
        this.stateTab = true;
      }
      this.getUser(this.queryParams());
      return this.stateTab;
    },
    queryParams() {
      let currentPage = this.pagination.current_page;
      let pageNum = currentPage ? currentPage : 1;
      this.searchParams.page = pageNum;

      return new URLSearchParams(this.searchParams).toString();
    },
    async getUser(params) {
      await axios
        .get(`/api/v1/user?${params}`)
        .then((res) => {
          this.tableBody = res.data;
          this.pagination = res.meta;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getRole() {
      return localStorage.getItem("role");
    },
    async logOut() {
      await axios.post(`/api/v1/logout`).then((res) => {
        if (res.code === 200) {
          router.push({ name: "index" }).catch(() => {});
          localStorage.removeItem("token");
          localStorage.removeItem("role");
          localStorage.removeItem("user");
        }
      });
    },
  },
};
</script>

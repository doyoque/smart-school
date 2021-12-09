<template>
  <div class="bg-white text-black font-bold rounded-lg border shadow-lg p-10">
    <div class="grid grid-cols-8">
      <router-link
        to="/create-user"
        class="bg-blue-500 hover:bg-blue-300 text-white text-center font-bold py-1 mb-2 px-4 rounded-lg"
        >create user</router-link
      >
    </div>
    <table class="table-auto border-separate rounded-lg border border-blue-500">
      <tableHead
        :dataHeader="tableHeader"
        :endOfHeader="tableHeader.length"
        :dataBody="tableBody"
      />
      <tableBody :dataBody="tableBody" />
    </table>
    <pagination
      :pagination="pagination"
      @paginate="getUser(queryParams())"
      :offset="4"
    />
  </div>
</template>

<script>
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
      tableBody: null,
      pagination: {},
      offset: 4,
    };
  },
  created() {
    this.getUser(this.queryParams());
  },
  methods: {
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
  },
};
</script>

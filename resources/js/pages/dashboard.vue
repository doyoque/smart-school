<template>
  <div class="bg-white text-black font-bold rounded-lg border shadow-lg p-10">
    <table class="table-auto border-separate rounded-lg border border-blue-500">
      <tableHead
        :dataHeader="tableHeader"
        :endOfHeader="tableHeader.length"
        :dataBody="tableBody"
      />
      <tableBody :dataBody="tableBody" />
    </table>
    <pagination />
  </div>
</template>

<script>
import Pagination from "@components/table/pagination.vue";
import TableHead from "@components/table/tableHead.vue";
import TableBody from "@components/table/tableBody.vue";

export default {
  name: "dashboard",
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
      },
      tableBody: null,
    };
  },
  components: {
    Pagination,
    TableHead,
    TableBody,
  },
  async created() {
    const params = new URLSearchParams(this.searchParams).toString();

    const res = await axios.get(`/api/v1/user?${params}`).catch((err) => {
      console.log(err);
    });
    this.tableBody = res.data;
  },
  mounted() {
    console.log("dashboard working");
  },
};
</script>

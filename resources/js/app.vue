<template>
  <div class="flex items-center justify-center h-screen w-full bg-blue-500">
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  name: "app",
  mounted() {
    console.log("app work");
  },
};
</script>

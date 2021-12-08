<template>
  <div class="bg-white text-black font-bold rounded-lg border shadow-lg p-10">
    <router-link
      to="/login"
      class="bg-blue-500 hover:bg-blue-300 text-white font-bold py-2 px-4 rounded-lg"
      >sign in</router-link
    >
    <router-link
      to="/signup"
      class="bg-blue-500 hover:bg-blue-300 text-white font-bold py-2 px-4 rounded-lg"
      >signup</router-link
    >
  </div>
</template>

<script>
export default {
  name: "index",
};
</script>

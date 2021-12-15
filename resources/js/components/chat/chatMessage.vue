<template>
  <div
    id="chat"
    class="flex mt-2 flex-col-reverse overflow-y-scroll space-y-3 pb-3 h-56 max-h-56"
  >
    <div
      class="w-max ml-auto break-all mt-2 mb-1 p-2 rounded-br-none bg-blue-500 rounded-2xl text-white text-left mr-5"
      v-for="item in messages"
      :key="item.id"
    >
      {{ item.message }}
    </div>
    <!-- <div
      class="other break-all mt-2 ml-5 rounded-bl-none float-none bg-gray-300 mr-auto rounded-2xl p-2"
    >
      Aww thx!!
    </div> -->
  </div>
</template>
<script>
export default {
  name: "chat-message",
  props: ["messages"],
};
</script>

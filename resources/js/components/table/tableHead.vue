<template>
  <thead>
    <tr>
      <th
        v-for="header in dataHeader"
        :key="header.id"
        :class="[
          'border border-blue-600 px-4 bg-blue-500 text-white',
          header.id === 1 ? 'rounded-tl-lg' : '',
          header.id === dataHeader[dataHeader.length - 1].id
            ? 'rounded-tr-lg'
            : '',
          header.id === 1 && (dataBody && dataBody.length) == 0
            ? 'rounded-bl-lg'
            : '',
          header.id === endOfHeader && (dataBody && dataBody.length) == 0
            ? 'rounded-br-lg'
            : '',
        ]"
        class="border border-blue-600 px-4 bg-blue-500 text-white"
      >
        {{ header.name }}
      </th>
    </tr>
  </thead>
</template>

<script>
export default {
  name: "tableHead",
  props: {
    dataHeader: Array,
    endOfHeader: Number,
    dataBody: Array,
  },
};
</script>

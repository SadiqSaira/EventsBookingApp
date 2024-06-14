<script>
export default {
  props: {
    success: {
      type: String,
      required: false,
    },
  },
};
</script>
<style scoped>
.alert-success {
  color: #155724;
  background-color: #d4edda;
  border-color: #c3e6cb;
  padding: 10px;
  border-radius: 5px;
}
</style>
<template>
    <div v-if="success" class="alert alert-success mt-2">
      {{ success }}
    </div>
  </template>

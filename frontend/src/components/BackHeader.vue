<template>
  <q-header class="bg-white" bordered reveal>
    <q-toolbar>
      <q-btn color="dark" dense flat round @click="$router.back()">
        <q-icon name="arrow_back" size="sm" />
      </q-btn>
      <q-toolbar-title class="text-weight-medium text-dark">{{ props.label }}</q-toolbar-title>
    </q-toolbar>
  </q-header>
</template>

<script setup>
const props = defineProps({
  label: {
    type: String,
    required: true,
  },
})
</script>

<template>
  <q-card class="col-grow row items-center" bordered flat>
    <q-card-section class="row items-center" style="gap: 0.9375rem">
      <span :class="`bg-${color}-1`" style="border-radius: 9999px; padding: 0.9375em">
        <q-icon :class="`text-${color}`" :name="iconName" size="2rem" />
      </span>

      <div>
        <p class="q-mb-none text-bold text-h3">{{ count }}</p>
        <p class="q-mb-none text-subtitle1 text-grey">{{ label }}</p>
      </div>
    </q-card-section>
  </q-card>
</template>

<script setup>
import { defineProps } from 'vue'

defineProps({
  color: { type: String },
  count: { type: [String, Number] },
  label: { type: String },
  iconName: { type: String },
})
</script>

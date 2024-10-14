<template>
  <q-input :type="isPasswordVisible ? 'text' : 'password'">
    <template v-slot:append>
      <slot v-if="$slots.append" name="append" />

      <template v-else>
        <q-icon
          :name="isPasswordVisible ? 'visibility_off' : 'visibility'"
          class="cursor-pointer"
          @click="togglePasswordVisibility"
        />
      </template>
    </template>
  </q-input>
</template>

<script setup>
import { ref } from 'vue'

const isPasswordVisible = ref(false)

const togglePasswordVisibility = () => {
  isPasswordVisible.value = !isPasswordVisible.value
}
</script>

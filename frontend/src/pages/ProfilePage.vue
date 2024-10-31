<template>
  <q-card bordered flat>
    <q-card-section>
      <h2 class="text-h6">Change Password</h2>
      <p class="text-weight-regular text-subtitle2 text-grey-7">
        Ensure your account is using a long. Random password to stay secure.
      </p>
    </q-card-section>

    <q-card-section>
      <q-form class="q-gutter-y-sm" @submit="handlePasswordUpdate">
        <password-input
          label="Current password"
          color="dark"
          lazy-rules="ondemand"
          :rules="authRules.currentPassword"
          debounce
          filled
          v-model="currentPassword"
        />

        <password-input
          label="New password"
          color="dark"
          lazy-rules="ondemand"
          :rules="authRules.newPassword"
          debounce
          filled
          v-model="newPassword"
        />

        <password-input
          label="Confirm password"
          color="dark"
          lazy-rules="ondemand"
          :rules="authRules.confirmPassword"
          debounce
          filled
          v-model="confirmPassword"
        />

        <q-btn label="Save" type="submit" color="dark" no-caps unelevated />
      </q-form>
    </q-card-section>
  </q-card>
</template>

<script setup>
import { useQuasar } from 'quasar'
import { ref } from 'vue'

import { useAuthStore } from 'src/stores/authStore'

import authRules from 'src/validation/authRules'

import BackHeader from 'src/components/BackHeader.vue'
import PasswordInput from 'src/components/PasswordInput.vue'

defineOptions({
  name: 'ProfilePage',
})

const isProduction = import.meta.env.PROD

const $q = useQuasar()
const auth = useAuthStore()

const currentPassword = ref('')
const newPassword = ref('')
const confirmPassword = ref('')

const handlePasswordUpdate = async () => {
  const data = {
    current_password: currentPassword.value,
    password: newPassword.value,
    password_confirmation: confirmPassword.value,
  }

  const isSuccessful = await auth.updatePassword(data)
  if (isSuccessful) {
    $q.notify('Password changed.')
  } else {
    $q.notify('Failed updating password. Please try again.')
  }
}
</script>

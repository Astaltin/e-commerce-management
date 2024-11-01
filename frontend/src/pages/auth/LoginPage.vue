<template>
  <q-layout>
    <q-page-container>
      <q-page class="q-px-sm">
        <section style="height: 100svh" class="column">
          <div class="grow row justify-center items-center q-pt-lg">
            <q-img style="max-width: 4em" src="/assets/tradeon-logo.svg" />
          </div>

          <div class="q-pb-lg q-gutter-y-md">
            <header>
              <h1 class="q-mb-none text-h6">Log in</h1>
              <p class="text-weight-regular text-subtitle2 text-grey">Log in to your account.</p>
            </header>

            <q-form class="q-gutter-y-sm" autofocus novalidate @submit="handleLoginSubmit">
              <q-input
                label="Email"
                type="email"
                color="dark"
                lazy-rules="ondemand"
                :disable="isLoading"
                :rules="authRules.email"
                debounce
                outlined
                v-model="email"
              />

              <password-input
                label="Password"
                color="dark"
                lazy-rules="ondemand"
                :disable="isLoading"
                :rules="authRules.password"
                debounce
                outlined
                v-model="password"
              />

              <q-btn
                label="Log in"
                type="submit"
                class="q-mb-md full-width"
                color="dark"
                size="lg"
                :disable="isLoading"
                :loading="isLoading"
                no-caps
                unelevated
              />
            </q-form>
          </div>
        </section>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { useQuasar } from 'quasar'
import { ref } from 'vue'
import { isNavigationFailure, useRouter } from 'vue-router'

import PasswordInput from 'src/components/PasswordInput.vue'
import { useAuthStore } from 'src/stores/authStore'
import authRules from 'src/validation/authRules'

defineOptions({
  name: 'LoginPage',
})

const isProduction = import.meta.env.PROD

const isLoading = ref(false)
const email = ref(isProduction ? '' : 'admin@gmail.com')
const password = ref(isProduction ? '' : 'admin')

const $q = useQuasar()
const auth = useAuthStore()
const router = useRouter()

const handleLoginSubmit = async () => {
  isLoading.value = true

  const data = { email: email.value, password: password.value }

  try {
    const isLoggedIn = await auth.login(data)
    if (isLoggedIn) {
      await router.replace({ name: 'dashboard' })
    } else {
      $q.notify('Login request failed.')
    }
  } catch (error) {
    if (isNavigationFailure(error)) {
      if (isProduction) console.error('Navigation failure:', error)
    } else {
      throw error
    }
  } finally {
    isLoading.value = false
  }
}
</script>

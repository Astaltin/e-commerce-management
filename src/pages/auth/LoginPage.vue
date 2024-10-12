<template>
  <q-layout>
    <q-page-container>
      <q-page class="q-mt-xl" padding>
        <section class="q-pt-lg q-gutter-y-md">
          <header>
            <h1 class="q-mb-none text-h6">Log in</h1>
            <p class="text-weight-regular text-subtitle2 text-grey">Log in to your account.</p>
          </header>

          <q-form class="q-gutter-y-md" autofocus>
            <q-input label="Email" type="email" color="dark" outlined v-model="email" />

            <q-input
              label="Password"
              :type="!isPasswordVisible ? 'password' : 'text'"
              color="dark"
              outlined
              v-model="password"
            >
              <template v-slot:append>
                <q-icon
                  class="cursor-pointer"
                  :name="!isPasswordVisible ? 'visibility' : 'visibility_off'"
                  v-on:click="isPasswordVisible = !isPasswordVisible"
                />
              </template>
            </q-input>

            <div class="q-mt-xs row items-center justify-between">
              <q-checkbox label="Remember me?" color="dark" v-model="isRemembered" />

              <a class="text-blue" href="#">Forgot my password</a>
            </div>

            <q-btn
              to="/"
              label="Log in"
              class="full-width"
              color="dark"
              size="lg"
              unelevated
              no-caps
            />
          </q-form>
        </section>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { ref } from 'vue'

defineOptions({
  name: 'LoginPage',
})

const email = ref('')
const password = ref('')
const isPasswordVisible = ref(false)
const isRemembered = ref(false)
</script>

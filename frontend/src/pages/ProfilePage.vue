<template>
  <q-layout>
    <back-header label="Profile" />

    <q-page-container>
      <q-page class="q-gutter-y-sm" padding>
        <q-card bordered flat>
          <q-card-section tag="header">
            <h2 class="text-h6">Profile Information</h2>
            <p class="text-weight-regular text-subtitle2 text-grey-7">
              Update your account's profile information and email address.
            </p>
          </q-card-section>

          <q-card-section>
            <q-form class="q-gutter-y-md">
              <q-input label="Name" color="dark" filled v-model="name" />
              <q-input label="Email" type="email" color="dark" filled v-model="email" />
              <q-btn label="Save" type="submit" color="dark" no-caps unelevated />
            </q-form>
          </q-card-section>
        </q-card>

        <q-card bordered flat>
          <q-card-section>
            <h2 class="text-h6">Change Password</h2>
            <p class="text-weight-regular text-subtitle2 text-grey-7">
              Ensure your account is using a long. Random password to stay secure.
            </p>
          </q-card-section>

          <q-card-section>
            <q-form class="q-gutter-y-md">
              <password-input
                label="Current Password"
                v-model="currentPassword"
                color="dark"
                filled
              />
              <password-input label="New Password" v-model="newPassword" color="dark" filled />
              <password-input
                label="Confirm Password"
                v-model="confirmPassword"
                color="dark"
                filled
              />
              <q-btn label="Save" type="submit" color="dark" no-caps unelevated />
            </q-form>
          </q-card-section>
        </q-card>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script setup>
import BackHeader from 'src/components/BackHeader.vue'
import PasswordInput from 'src/components/PasswordInput.vue'
import { ref } from 'vue'

defineOptions({
  name: 'ProfilePage',
})

const name = ref('')
const email = ref('')
const currentPassword = ref('')
const newPassword = ref('')
const confirmPassword = ref('')
</script>

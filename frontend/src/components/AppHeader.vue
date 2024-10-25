<template>
  <q-header class="bg-white" bordered reveal>
    <q-toolbar class="justify-between">
      <q-img style="max-width: 2em" src="assets/tradeon-logo.svg" />

      <q-btn-dropdown class="text-dark" icon="account_circle" dense flat rounded>
        <q-list>
          <q-item-label class="text-weight-medium" header>Menu</q-item-label>

          <q-separator />

          <q-item to="/profile" clickable v-close-popup>
            <q-item-section avatar>
              <q-icon name="account_circle" />
            </q-item-section>

            <q-item-section>
              <q-item-label>Profile</q-item-label>
            </q-item-section>
          </q-item>

          <q-separator />

          <q-item clickable v-close-popup @click="handleLogout">
            <q-item-section avatar>
              <q-icon name="logout" />
            </q-item-section>

            <q-item-section>
              <q-item-label>Log out</q-item-label>
            </q-item-section>
          </q-item>
        </q-list>
      </q-btn-dropdown>
    </q-toolbar>
  </q-header>
</template>

<script setup>
import { useAuthStore } from 'src/stores/authStore'
import { isNavigationFailure, useRouter } from 'vue-router'

const isDevelopment = import.meta.env.DEV

const auth = useAuthStore()
const router = useRouter()

const handleLogout = async () => {
  try {
    const hasLoggedOut = await auth.logout()
    if (hasLoggedOut) await router.replace({ name: 'auth.login' })
  } catch (error) {
    if (isNavigationFailure(error)) {
      if (isDevelopment) console.error('Navigation failure:', error)
    } else {
      throw error
    }
  }
}
</script>

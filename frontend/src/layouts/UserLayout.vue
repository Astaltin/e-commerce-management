<template>
  <q-layout>
    <q-header class="bg-white" bordered reveal>
      <q-toolbar class="justify-between">
        <q-img style="max-width: 2em" src="src/assets/tradeon-logo.svg" />

        <q-btn-dropdown class="text-dark" icon="account_circle" dense flat rounded>
          <q-list>
            <q-item-label class="text-weight-medium" header>Menu</q-item-label>

            <q-separator />

            <template v-for="option of userMenuOptions" :key="option.label">
              <q-separator v-if="option.label === 'Log out'" />

              <q-item :to="option.href" clickable v-close-popup>
                <q-item-section avatar>
                  <q-icon :name="option.icon" />
                </q-item-section>

                <q-item-section>
                  <q-item-label>{{ option.label }}</q-item-label>
                </q-item-section>
              </q-item>
            </template>
          </q-list>
        </q-btn-dropdown>
      </q-toolbar>
    </q-header>

    <q-page-container>
      <q-page padding>
        <router-view />
      </q-page>
    </q-page-container>

    <q-footer class="bg-white" bordered reveal>
      <q-tabs
        class="text-grey"
        active-color="dark"
        v-model="defaultNavTab"
        narrow-indicator
        no-caps
      >
        <q-route-tab
          v-for="tab of navigationTabs"
          :key="tab.name"
          :to="tab.href"
          :name="tab.name"
          :icon="tab.icon"
          :label="tab.label"
        />
      </q-tabs>
    </q-footer>
  </q-layout>
</template>

<script setup>
import { ref } from 'vue'

const userMenuOptions = [
  { href: '/profile', icon: 'account_circle', label: 'Profile' },
  { href: '/settings', icon: 'settings', label: 'Settings' },
  { icon: 'logout', label: 'Log out' },
]

const navigationTabs = [
  { href: '/', name: 'dashboard', icon: 'dashboard', label: 'Dashboard' },
  { href: '/products', name: 'products', icon: 'add_box', label: 'Products' },
]
const defaultNavTab = ref('dashboard')
</script>

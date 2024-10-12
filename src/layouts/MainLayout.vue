<template>
  <q-layout>
    <q-header class="bg-white" reveal>
      <q-toolbar>
        <q-icon name="image" size="md" color="dark" />
        <q-toolbar-title class="text-weight-medium text-dark">Dashboard</q-toolbar-title>

        <q-btn-dropdown class="text-dark" icon="account_circle" dense flat rounded>
          <q-list>
            <q-item-label class="text-weight-medium" header>Menu</q-item-label>

            <q-separator />

            <template v-for="option of userMenuOptions" :key="option.label">
              <template v-if="option.label === 'Log out'">
                <q-separator />
              </template>

              <q-item clickable v-close-popup>
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
      <q-tabs class="text-grey" active-color="dark" v-model="tab" narrow-indicator no-caps>
        <q-tab
          v-for="tab of navigationTabs"
          :key="tab.name"
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
  { label: 'Profile', icon: 'account_circle' },
  { label: 'Settings', icon: 'settings' },
  { label: 'Log out', icon: 'logout' },
]

const navigationTabs = [
  { name: 'dashboard', icon: 'dashboard', label: 'Dashboard' },
  { name: 'products', icon: 'add_box', label: 'Products' },
]
const tab = ref('dashboard')
</script>

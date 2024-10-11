<template>
  <q-layout>
    <q-header class="bg-white" reveal>
      <q-toolbar>
        <q-toolbar-title class="text-weight-medium text-dark">Dashboard</q-toolbar-title>

        <q-btn-dropdown class="text-dark" icon="account_circle" flat rounded>
          <q-list>
            <template v-for="option of userMenuOptions" :key="option.label">
              <template v-if="option.label === 'Log out'">
                <q-item>
                  <q-item-section>
                    <q-btn
                      class="bg-dark text-left"
                      text-color="white"
                      :label="option.label"
                      flat
                      no-caps
                    />
                  </q-item-section>
                </q-item>
              </template>

              <template v-else>
                <q-item clickable v-close-popup>
                  <q-item-section>
                    <q-item-label>{{ option.label }}</q-item-label>
                  </q-item-section>
                </q-item>
              </template>
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

const userMenuOptions = [{ label: 'Profile' }, { label: 'Settings' }, { label: 'Log out' }]

const navigationTabs = [
  { name: 'dashboard', icon: 'dashboard', label: 'Dashboard' },
  { name: 'products', icon: 'add_box', label: 'Products' },
]
const tab = ref('dashboard')
</script>

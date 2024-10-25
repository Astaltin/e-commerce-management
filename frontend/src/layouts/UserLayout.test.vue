<template>
  <q-layout>
    <app-header v-if="router.currentRoute.value.name !== 'profile'" />
    <back-header label="Profile" v-else />

    <!-- TODO: has some weird padding fsr. idk where it came from ;-; -->
    <q-page-container>
      <q-page padding>
        <router-view />
      </q-page>
    </q-page-container>

    <q-footer v-if="router.currentRoute.value.name !== 'profile'" class="bg-white" bordered reveal>
      <q-tabs
        class="text-grey"
        active-color="dark"
        v-model="defaultNavTab"
        narrow-indicator
        no-caps
      >
        <q-route-tab
          v-for="tab in navigationTabs"
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
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'

import { useAuthStore } from 'src/stores/authStore'

import AppHeader from 'src/components/AppHeader.vue'
import BackHeader from 'src/components/BackHeader.vue'

const auth = useAuthStore()
const router = useRouter()

onMounted(() => {
  auth.load()
})

const navigationTabs = [
  { href: '/', name: 'dashboard', icon: 'dashboard', label: 'Dashboard' },
  { href: '/products', name: 'products', icon: 'add_box', label: 'Products' },
]
const defaultNavTab = ref('dashboard')
</script>

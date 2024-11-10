<template>
  <!-- TOP ROW -->
  <section class="q-gutter-sm row">
    <q-card class="col-grow" bordered flat>
      <q-card-section>
        <h3 class="text-h6">Active Products</h3>
      </q-card-section>
    </q-card>

    <q-card class="col-grow" bordered flat>
      <q-card-section>
        <h3 class="text-h6">Deleted Products</h3>
      </q-card-section>
    </q-card>

    <q-card class="col-grow" bordered flat>
      <q-card-section>
        <h3 class="text-h6">Total Stocks</h3>
      </q-card-section>
    </q-card>

    <q-card class="col-grow" bordered flat>
      <q-card-section>
        <h3 class="text-h6">Total Categories</h3>
      </q-card-section>
    </q-card>
  </section>

  <!-- SECOND ROW -->
  <section class="q-gutter-sm row">
    <q-card class="col-grow" bordered flat>
      <q-card-section>
        <h3 class="text-h6">Low Stock Products</h3>
      </q-card-section>
    </q-card>

    <q-card class="col-grow" bordered flat>
      <q-card-section>
        <h3 class="text-h6">Out of Stock Products</h3>
      </q-card-section>
    </q-card>

    <q-card class="col-grow" bordered flat>
      <q-card-section>
        <h3 class="text-h6">Total Stocks Products</h3>
      </q-card-section>
    </q-card>
  </section>

  <!-- MIDDLE SECTION -->
  <section class="q-gutter-sm row"></section>

  <!-- BOTTOM SECTION -->
  <section class="q-gutter-sm row"></section>
</template>
<script setup>
import { onMounted, reactive } from 'vue'

const data = reactive({})

onMounted(() => {
  const getDashboardData = async () => {
    try {
      const res = await api.get('/dashboard')
      data = res.data
    } catch (error) {}
  }
  getDashboardData()
})
</script>

<template>
  <!-- TOP ROW: Key Metrics Summary -->
  <section class="row" :class="$q.screen.xs ? 'card-gap-sm' : 'card-gap-md'">
    <q-card class="col-grow" bordered flat>
      <q-card-section class="row items-center">
        <q-icon name="inventory" size="32px" class="text-primary" />
        <div>
          <h3 class="text-h6">Total Active Products</h3>
          <p>{{ data.total_products_active }}</p>
        </div>
      </q-card-section>
    </q-card>

    <q-card class="col-grow" bordered flat>
      <q-card-section class="row items-center">
        <q-icon name="delete" size="32px" class="text-negative" />
        <div>
          <h3 class="text-h6">Total Deleted Products</h3>
          <p>{{ data.total_products_deleted }}</p>
        </div>
      </q-card-section>
    </q-card>

    <q-card class="col-grow" bordered flat>
      <q-card-section class="row items-center">
        <q-icon name="attach_money" size="32px" class="text-positive" />
        <div>
          <h3 class="text-h6">Total Stocks Value</h3>
          <p>{{ formatCurrency(data.total_stocks_value) }}</p>
        </div>
      </q-card-section>
    </q-card>

    <q-card class="col-grow" bordered flat>
      <q-card-section class="row items-center">
        <q-icon name="category" size="32px" class="text-primary" />
        <div>
          <h3 class="text-h6">Total Categories</h3>
          <p>{{ data.total_categories }}</p>
        </div>
      </q-card-section>
    </q-card>
  </section>

  <!-- SECOND ROW: Product Inventory Insights -->
  <section class="row" :class="$q.screen.xs ? 'card-gap-sm' : 'card-gap-md'">
    <q-card class="col-grow bg-red text-white" bordered flat>
      <q-card-section>
        <h3 class="text-h6">Low Stock Products</h3>
        <p>{{ data.low_stock_products }}</p>
      </q-card-section>
    </q-card>

    <q-card class="col-grow bg-orange text-white" bordered flat>
      <q-card-section>
        <h3 class="text-h6">Out of Stock Products</h3>
        <p>{{ data.out_of_stock_products }}</p>
      </q-card-section>
    </q-card>

    <q-card class="col-grow bg-primary text-white" bordered flat>
      <q-card-section>
        <h3 class="text-h6">Total Stocks</h3>
        <p>{{ data.total_stocks }}</p>
      </q-card-section>
    </q-card>
  </section>

  <!-- MIDDLE SECTION: Category Insights -->
  <section class="row" :class="$q.screen.xs ? 'card-gap-sm' : 'card-gap-md'">
    <!-- Left Panel: Product Count by Category -->
    <section class="grow">
      <h3 class="q-mb-xs text-h6">Product Count by Category</h3>

      <q-card bordered flat>
        <q-card-section>
          <apexchart
            :options="totalProductsByCategoryChart.chartOptions"
            :series="totalProductsByCategoryChart.series"
          ></apexchart>
        </q-card-section>
      </q-card>
    </section>

    <!-- Right Panel: Inventory Value by Category -->
    <section class="grow">
      <h3 class="q-mb-xs text-h6">Inventory Value by Category</h3>

      <q-card bordered flat>
        <q-card-section>
          <apexchart
            :options="totalInventoryValuesByCategoryChart.chartOptions"
            :series="totalInventoryValuesByCategoryChart.series"
          ></apexchart>
        </q-card-section>
      </q-card>
    </section>
  </section>

  <!-- BOTTOM SECTION: Alerts -->
  <section class="row" :class="$q.screen.xs ? 'card-gap-sm' : 'card-gap-md'">
    <!-- Recently Added Products -->
    <section class="grow">
      <h3 class="text-h6 q-m-y-none">Recently Added Products</h3>

      <section class="q-gutter-y-md">
        <q-card bordered flat v-for="product in data.recently_added_products" :key="product.id">
          <q-card-section class="row justify-between content-center">
            <p class="q-mb-none" :style="{ maxWidth: '45ch' }">
              {{ product.name }} - {{ product.description }} ({{ formatCurrency(product.price) }})
            </p>

            <q-btn flat label="View" @click="viewProduct(product)" />
          </q-card-section>
        </q-card>
      </section>
    </section>

    <!-- Recently Updated Products -->
    <section class="grow">
      <h3 class="text-h6 q-m-y-none">Recently Updated Products</h3>

      <section class="q-gutter-y-md">
        <q-card bordered flat v-for="product in data.recently_updated_products" :key="product.id">
          <q-card-section class="row justify-between content-center">
            <p class="q-mb-none" :style="{ maxWidth: '45ch' }">
              {{ product.name }} - {{ product.description }} ({{ formatCurrency(product.price) }})
            </p>

            <q-btn flat label="View" @click="viewProduct(product)" />
          </q-card-section>
        </q-card>
      </section>
    </section>
  </section>

  <!-- Product Modal (Non-Editable) -->
  <q-dialog v-model="productModalOpen" persistent>
    <q-card>
      <q-card-section>
        <div class="text-h6">Product Details</div>
        <div>
          <div><strong>Name:</strong> {{ selectedProduct.name }}</div>
          <div><strong>Description:</strong> {{ selectedProduct.description }}</div>
          <div><strong>Price:</strong> {{ formatCurrency(selectedProduct.price) }}</div>
        </div>
      </q-card-section>
      <q-card-actions>
        <q-btn label="Close" @click="closeModal" flat />
      </q-card-actions>
    </q-card>
  </q-dialog>

  <!-- Loading and Error Messages -->
  <div v-if="loading" class="text-center">Loading data...</div>
  <div v-else-if="error" class="text-center text-negative">Error loading data.</div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import { api } from 'src/boot/axios'

const data = reactive({
  total_products_active: 0,
  total_products_deleted: 0,
  total_stocks_value: 0,
  total_categories: 0,
  total_stocks: 0,
  low_stock_products: [],
  out_of_stock_products: [],
  total_products_by_category: [],
  total_inventory_values_by_category: {},
  recently_added_products: [],
  recently_updated_products: [],
})

/** @type{import('apexcharts').ApexOptions}*/
const totalProductsByCategoryChart = reactive({
  series: [],
  chartOptions: {
    chart: {
      type: 'bar',
      height: 350,
    },
    plotOptions: {
      bar: {
        borderRadius: 4,
        borderRadiusApplication: 'end',
        horizontal: true,
      },
    },
    dataLabels: { enabled: false },
  },
})

const totalInventoryValuesByCategoryChart = reactive({
  series: [],
  chartOptions: {
    chart: {
      type: 'bar',
      height: 350,
    },
    plotOptions: {
      bar: {
        borderRadius: 4,
        borderRadiusApplication: 'end',
      },
    },
    dataLabels: { enabled: false },
  },
})

const loading = ref(true)
const error = ref(false)
const productModalOpen = ref(false)
const selectedProduct = reactive({
  id: null,
  name: '',
  description: '',
  price: 0,
})

// Format the data as currency
const formatCurrency = (value) => {
  return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'PHP' }).format(value)
}

// Open modal and set the selected product
const viewProduct = (product) => {
  selectedProduct.id = product.id
  selectedProduct.name = product.name
  selectedProduct.description = product.description
  selectedProduct.price = product.price
  productModalOpen.value = true
}

// Close the modal
const closeModal = () => {
  productModalOpen.value = false
}

// Fetch data from the API
onMounted(() => {
  const getDashboardData = async () => {
    try {
      const res = await api.get('/dashboard')

      for (const [k, v] of Object.entries(res.data)) {
        data[k] = v
      }

      totalProductsByCategoryChart.series = [
        {
          name: 'total',
          data: res.data.total_products_by_category.map((item) => ({
            x: item.name,
            y: item.count,
          })),
        },
      ]

      totalInventoryValuesByCategoryChart.series = [
        {
          name: 'amount',
          data: res.data.total_inventory_values_by_category.map((item) => ({
            x: `C${item.id}`,
            y: item.value,
          })),
        },
      ]

      loading.value = false
    } catch (error) {
      console.error('Error fetching dashboard data:', error)
      error.value = true
      loading.value = false
    }
  }
  getDashboardData()
})
</script>

<style scoped>
.text-center {
  text-align: center;
}

.card-gap-md {
  gap: 1rem;
}

.card-gap-sm {
  gap: 0.5rem;
}
</style>

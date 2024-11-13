<template>
  <div
    :class="$q.screen.xs ? 'q-gutter-y-sm' : 'q-gutter-y-md'"
    :style="{ padding: $q.screen.xs ? '.5rem' : '1rem' }"
  >
    <!-- TOP ROW: Key Metrics Summary -->
    <section class="row" :class="$q.screen.xs ? 'card-gap-sm' : 'card-gap-md'">
      <KeyMetricsCard
        color="light-green"
        :count="data.total_products_active"
        label="Total Active Products"
        icon-name="inventory"
      />

      <KeyMetricsCard
        color="red"
        :count="data.total_products_deleted"
        label="Total Deleted Products"
        icon-name="delete"
      />

      <KeyMetricsCard
        color="green"
        :count="formatCurrency(data.total_stocks_value)"
        label="Total Stocks Value"
        icon-name="attach_money"
      />

      <KeyMetricsCard
        color="orange"
        :count="data.total_categories"
        label="Total Categories"
        icon-name="category"
      />
    </section>

    <!-- SECOND ROW: Product Inventory Insights -->
    <section class="row" :class="$q.screen.xs ? 'card-gap-sm' : 'card-gap-md'">
      <q-card class="col-grow bg-orange-1" flat>
        <q-card-section>
          <p class="q-mb-none text-subtitle1">Low Stock Products</p>
          <p class="q-mb-none text-bold text-h3">{{ data.low_stock_products }}</p>
        </q-card-section>
      </q-card>

      <q-card class="col-grow bg-red-1" flat>
        <q-card-section>
          <p class="q-mb-none text-subtitle1">Out of Stock Products</p>
          <p class="q-mb-none text-bold text-h3">{{ data.out_of_stock_products }}</p>
        </q-card-section>
      </q-card>

      <q-card class="col-grow bg-light-green-1" flat>
        <q-card-section>
          <p class="q-mb-none text-subtitle1">Total Stocks</p>
          <p class="q-mb-none text-bold text-h3">{{ data.total_stocks }}</p>
        </q-card-section>
      </q-card>
    </section>

    <!-- MIDDLE SECTION: Category Insights -->
    <section class="row" :class="$q.screen.xs ? 'card-gap-sm' : 'card-gap-md'">
      <!-- Left Panel: Product Count by Category -->
      <section class="grow">
        <q-card bordered flat>
          <q-card-section class="column text-grey" style="gap: 1.25em">
            <span class="text-h6">Product Count by Category</span>

            <apexchart
              :options="totalProductsByCategoryChart.chartOptions"
              :series="totalProductsByCategoryChart.series"
            ></apexchart>
          </q-card-section>
        </q-card>
      </section>

      <!-- Right Panel: Inventory Value by Category -->
      <section class="grow">
        <q-card bordered flat>
          <q-card-section class="column text-grey" style="gap: 1.25em">
            <span class="text-h6">Inventory Value by Category</span>

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
            <q-card-section class="row justify-between items-center">
              <p class="q-mb-none" :style="{ maxWidth: '45ch' }">
                {{ product.name }} - {{ product.description }} ({{ formatCurrency(product.price) }})
              </p>

              <q-btn
                color="grey"
                icon="arrow_forward_ios"
                size=".625rem"
                style="max-width: 2px; max-height: 2px"
                flat
                rounded
                @click="viewProduct(product)"
              />
            </q-card-section>
          </q-card>
        </section>
      </section>

      <!-- Recently Updated Products -->
      <section class="grow">
        <h3 class="text-h6 q-m-y-none">Recently Updated Products</h3>

        <section class="q-gutter-y-md">
          <q-card bordered flat v-for="product in data.recently_updated_products" :key="product.id">
            <q-card-section class="row justify-between items-center">
              <p class="q-mb-none" :style="{ maxWidth: '45ch' }">
                {{ product.name }} - {{ product.description }} ({{ formatCurrency(product.price) }})
              </p>

              <q-btn
                color="grey"
                icon="arrow_forward_ios"
                size=".625rem"
                style="max-width: 2px; max-height: 2px"
                flat
                rounded
                @click="viewProduct(product)"
              />
            </q-card-section>
          </q-card>
        </section>
      </section>
    </section>

    <!-- Product Modal (Non-Editable) -->
    <q-dialog v-model="productModalOpen">
      <q-card style="width: 22.5rem">
        <q-card-section>
          <span class="text-h6">{{ selectedProduct.name }}</span>
          <p class="text-subtitle2">{{ formatCurrency(selectedProduct.price) }}</p>
          <p class="text-grey">{{ selectedProduct.description }}</p>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn color="dark" label="Close" no-caps unelevated @click="closeModal" />
        </q-card-actions>
      </q-card>
    </q-dialog>

    <!-- Loading and Error Messages -->
    <div v-if="loading" class="text-center">Loading data...</div>
    <div v-else-if="error" class="text-center text-negative">Error loading data.</div>
  </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import { api } from 'src/boot/axios'
import KeyMetricsCard from 'src/components/dashboard/KeyMetricsCard.vue'
import { colors } from 'quasar'

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
    fill: {
      colors: colors.getPaletteColor('dark'),
    },
    plotOptions: {
      bar: {
        borderRadius: 16,
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
    fill: {
      colors: colors.getPaletteColor('dark'),
    },
    plotOptions: {
      bar: {
        borderRadius: 16,
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
  selectedProduct.id = null
  selectedProduct.name = ''
  selectedProduct.description = ''
  selectedProduct.price = 0
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

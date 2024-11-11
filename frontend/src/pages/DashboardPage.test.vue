<template>
  <!-- TOP ROW: Key Metrics Summary -->
  <section class="q-gutter-sm row">
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
  <section class="q-gutter-sm row">
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
  <section class="row q-mt-md justify-center">
    <!-- Left Panel: Product Count by Category -->
    <q-card class="col-5 q-mr-md" bordered flat>
      <q-card-section>
        <h3 class="text-h6">Product Count by Category</h3>
        <!-- <ul> -->
        <!--   <li v-for="(count, category) in data.total_products_by_category" :key="category"> -->
        <!--     {{ category }} - {{ count }} products -->
        <!--   </li> -->
        <!-- </ul> -->

        <!-- <apexchart type="bar" height="350" :options="chartOptions" :series="series"></apexchart> -->
      </q-card-section>
    </q-card>

    <!-- Right Panel: Inventory Value by Category -->
    <q-card class="col-5 q-ml-md" bordered flat>
      <q-card-section>
        <h3 class="text-h6">Inventory Value by Category</h3>
        <ul>
          <li v-for="(value, category) in data.total_inventory_values_by_category" :key="category">
            {{ category }} - {{ formatCurrency(value) }}
          </li>
        </ul>
      </q-card-section>
    </q-card>
  </section>

  <!-- BOTTOM SECTION: Alerts -->
  <section class="row q-mt-md justify-center">
    <!-- Recently Added Products -->
    <q-card class="col-5 q-mr-md" bordered flat>
      <q-card-section>
        <h3 class="text-h6">Recently Added Products</h3>
        <ul>
          <li v-for="product in data.recently_added_products" :key="product.id">
            {{ product.name }} - {{ product.description }} - {{ formatCurrency(product.price) }}
            <q-btn flat label="View" @click="viewProduct(product)" />
          </li>
        </ul>
      </q-card-section>
    </q-card>

    <!-- Recently Updated Products -->
    <q-card class="col-5 q-ml-md" bordered flat>
      <q-card-section>
        <h3 class="text-h6">Recently Updated Products</h3>
        <ul>
          <li v-for="product in data.recently_updated_products" :key="product.id">
            {{ product.name }} - {{ product.description }} - {{ formatCurrency(product.price) }}
            <q-btn flat label="View" @click="viewProduct(product)" />
          </li>
        </ul>
      </q-card-section>
    </q-card>
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
  total_products_by_category: {},
  total_inventory_values_by_category: {},
  recently_added_products: [],
  recently_updated_products: [],
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
</style>

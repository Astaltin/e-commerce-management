<template>
  <q-table
    flat
    row-key="id"
    title="Products"
    :columns="data.columns"
    :filter="filter"
    :loading="isLoading"
    :pagination="{ rowsPerPage: 25 }"
    :rows="data.rows"
  >
    <template v-slot:top-left>
      <h1 class="q-ma-none text-h6">Products</h1>
    </template>

    <template v-slot:top-right>
      <div class="row" style="gap: 0.5rem">
        <q-input
          type="search"
          placeholder="Filter..."
          color="dark"
          debounce="300"
          dense
          filled
          v-model="filter"
        >
          <template v-slot:append>
            <q-icon name="search" size="1rem" />
          </template>
        </q-input>

        <q-btn label="Create" icon="add" color="dark" no-caps unelevated @click="openCreateModal" />
      </div>
    </template>

    <template v-slot:body="props">
      <q-tr :props="props">
        <q-td key="name" :props="props">
          {{ props.row.name }}
        </q-td>

        <q-td key="status" :props="props">
          <template v-if="props.row.stockLevel === 0">
            <q-badge
              class="text-weight-medium"
              color="negative"
              rounded
              :label="props.row.status"
            />
          </template>
          <template v-else-if="props.row.stockLevel < 15">
            <q-badge class="text-weight-medium" color="orange" rounded :label="props.row.status" />
          </template>
          <template v-else>
            <q-badge
              class="text-weight-medium"
              color="light-green"
              rounded
              :label="props.row.status"
            />
          </template>
        </q-td>

        <q-td key="stock" :props="props">
          {{ props.row.stockLevel }}
        </q-td>

        <q-td key="category" :props="props">
          {{ props.row.category }}
        </q-td>

        <q-td class="row" style="gap: 0.625rem" key="actions" :props="props">
          <q-btn
            class="text-primary"
            icon="edit"
            size="1em"
            dense
            round
            unelevated
            @click="openEditModal(props)"
          />
          <q-btn
            class="text-negative"
            icon="delete"
            size="1em"
            dense
            round
            unelevated
            @click="openDeleteModal(props)"
          />
        </q-td>
      </q-tr>
    </template>

    <template v-slot:no-data="{ message, filter }">
      <div
        v-if="filter && !isloading"
        class="full-width q-pt-lg column items-center text-grey"
        style="gap: 0.5em"
      >
        <q-icon name="search_off" class="text-h1" />
        <span class="text-h6">{{ message }}</span>
      </div>

      <div
        v-if="!filter && !isLoading"
        class="full-width q-pt-lg column items-center text-grey"
        style="gap: 0.5em"
      >
        <q-icon class="text-h1" name="production_quantity_limits" />
        <span class="text-h6">{{ message }}</span>
      </div>
    </template>
  </q-table>

  <!-- CREATE MODAL -->
  <q-dialog v-model="isCreateModalOpen" persistent @hide="() => (createFormData = {})">
    <q-card style="width: 30rem">
      <q-card-section class="column" style="gap: 0.625rem">
        <span class="text-h6 text-grey-7">Create Product</span>

        <section class="column" style="gap: 0.75rem">
          <q-input name label="Product Name" color="dark" debouce outlined v-model="name" />
          <q-input label="Description" color="dark" debouce outlined v-model="description" />
          <q-input type="number" label="Price" color="dark" debouce outlined v-model="price" />
          <q-input label="Category" color="dark" debouce outlined v-model="category" />
          <q-input type="number" label="Stock" color="dark" debouce outlined v-model="stock" />
        </section>
      </q-card-section>

      <q-card-actions align="right">
        <q-btn label="Cancel" no-caps unelevated v-close-popup />
        <q-btn color="dark" label="Create" no-caps unelevated v-close-popup @click="handleCreate" />
      </q-card-actions>
    </q-card>
  </q-dialog>

  <!-- EDIT MODAL -->
  <q-dialog v-model="isEditModalOpen" persistent @hide="() => (editFormData = {})">
    <q-card style="width: 30rem">
      <q-card-section class="column" style="gap: 0.625rem">
        <span class="text-h6 text-grey-7">Edit Product</span>

        <section class="column" style="gap: 0.75rem">
          <q-input
            name
            label="Product Name"
            color="dark"
            debouce
            outlined
            v-model="editFormData.name"
          />
          <q-input
            label="Description"
            color="dark"
            debouce
            outlined
            v-model="editFormData.description"
          />
          <q-input
            type="number"
            label="Price"
            color="dark"
            debouce
            outlined
            v-model="editFormData.price"
          />
          <q-input label="Category" color="dark" debouce outlined v-model="editFormData.category" />
          <q-input
            type="number"
            label="Stock"
            color="dark"
            debouce
            outlined
            v-model="editFormData.stock"
          />
        </section>
      </q-card-section>

      <q-card-actions align="right">
        <q-btn label="Cancel" no-caps unelevated v-close-popup />
        <q-btn
          color="primary"
          label="Update"
          no-caps
          unelevated
          v-close-popup
          @click="handleUpdate"
        />
      </q-card-actions>
    </q-card>
  </q-dialog>

  <!-- DELETE MODAL -->
  <q-dialog v-model="isDeleteModalOpen" persistent @hide="() => (deleteFormData = {})">
    <q-card style="width: 22.5rem">
      <q-card-section class="row" style="gap: 0.625rem">
        <span class="text-h6 text-grey-7">Delete {{ deleteFormData.name }}?</span>

        <p>Are you sure you want to delete this item?</p>
      </q-card-section>

      <q-card-actions align="right">
        <q-btn label="Cancel" no-caps unelevated v-close-popup />
        <q-btn
          color="negative"
          label="Delete"
          no-caps
          unelevated
          v-close-popup
          @click="handleDelete"
        />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script setup>
import { isAxiosError } from 'axios'
import { api } from 'src/boot/axios'
import { onMounted, reactive, ref } from 'vue'

const isDevelopment = import.meta.env.ENV

const isLoading = ref(false)
const data = reactive({
  rows: [],
  columns: [
    { name: 'name', label: 'Name of Product', field: 'name', align: 'left' },
    { name: 'status', label: 'Status', field: 'status', align: 'left' },
    { name: 'stock', label: 'Stock Level', field: 'stock', align: 'left', sortable: true },
    { name: 'category', label: 'Category', field: 'category', align: 'left' },
    { name: 'actions', align: 'left' },
  ],
})
const filter = ref('')

onMounted(() => {
  getData().catch(console.error)
})

const getData = async () => {
  isLoading.value = true

  try {
    const res = await api.get('/products')
    const products = res.data.products

    if (!Array.isArray(products) && isDevelopment) {
      throw TypeError('Expected an array but received', typeof products)
    }

    const narrowedProducts = products.map(
      ({ id, name, description, price, category, inventory }) => {
        const narrowed = {
          id,
          name,
          description,
          price,
          category: category.name,
          stockLevel: inventory.stock,
          get status() {
            return this.stockLevel === 0
              ? 'Out of Stock'
              : this.stockLevel < 15
              ? 'Low Stock'
              : 'Active'
          },
        }
        return narrowed
      }
    )

    data.rows = narrowedProducts
  } catch (error) {
    if (!isAxiosError(error)) {
      throw error
    }
    if (isDevelopment) {
      console.error('An error occured while fetching products data:', error)
    }
  } finally {
    isLoading.value = false
  }
}

// CREATE
const isCreateModalOpen = ref(false)
const name = ref('')
const description = ref(null)
const price = ref('0.00')
const category = ref('')
const stock = ref(0)

const openCreateModal = () => {
  isCreateModalOpen.value = true
}

const handleCreate = async () => {
  try {
    await api.post('/products', {
      name: name.value,
      description: description.value,
      price: price.value,
      category: category.value,
      stock: stock.value,
    })
    await getData()
  } catch (error) {
    // TODO: Implement error handling for create
  }
}

// UPDATE
const isEditModalOpen = ref(false)
const editFormData = ref({})

const openEditModal = ({ row }) => {
  isEditModalOpen.value = true
  editFormData.value = {
    id: row.id,
    name: row.name,
    description: row.description,
    price: row.price,
    category: row.category,
    stock: row.stockLevel,
  }
}

const handleUpdate = async () => {
  const data = editFormData.value

  try {
    await api.put(`/products/${data.id}`, data)
    await getData()
  } catch (error) {
    // TODO: Implement error handling for update
  }
}

// DELETE
const isDeleteModalOpen = ref(false)
const deleteFormData = ref({})

const openDeleteModal = ({ row }) => {
  isDeleteModalOpen.value = true
  deleteFormData.value = { id: row.id, name: row.name }
}

const handleDelete = async () => {
  const data = deleteFormData.value

  try {
    await api.delete(`/products/${data.id}`)
    await getData()
  } catch (error) {
    // TODO: Implement error handling for delete
  }
}
</script>

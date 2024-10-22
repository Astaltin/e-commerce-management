<template>
  <div>
    <q-table :rows="data" :columns="columns" row-key="id" flat bordered>
      <template v-slot:body-cell-actions="props">
        <div>
          <q-btn flat color="primary" label="Update" @click="openEditModal(props.row)" />
          <q-btn flat color="negative" label="Delete" @click="deleteProduct(props.row.id)" />
        </div>
      </template>
    </q-table>

    <q-dialog v-model="isEditModalOpen">
      <q-card>
        <q-card-section>
          <div class="text-h6">Edit Product</div>
        </q-card-section>

        <q-card-section>
          <q-input v-model="editForm.name" label="Product Name" />
          <q-input v-model="editForm.description" label="Description" />
          <q-input v-model="editForm.price" label="Price" type="number" />
          <q-input v-model="editForm.category" label="Category" />
          <q-input v-model="editForm.stock" label="Stock" type="number" />
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Cancel" color="primary" @click="closeEditModal" />
          <q-btn flat label="Save" color="primary" @click="updateProduct" :loading="loading" />
        </q-card-actions>
      </q-card>
    </q-dialog>

    <div>
      <q-input v-model="name" label="Product Name" />
      <q-input v-model="description" label="Description" />
      <q-input v-model="price" label="Price" type="number" />
      <q-input v-model="category" label="Category" />
      <q-input v-model="stock" label="Stock" type="number" />
      <q-btn @click="insertProduct" label="Add Product" :loading="loading" />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { api } from 'src/boot/axios'

const name = ref('')
const description = ref('')
const price = ref(0)
const category = ref('')
const stock = ref(0)

const data = ref([])
const isEditModalOpen = ref(false)
const loading = ref(false) // Track loading state
const editForm = ref({
  id: null,
  name: '',
  description: '',
  price: 0,
  category: '',
  stock: 0,
})

const columns = ref([
  { name: 'id', label: 'ID', align: 'left', field: 'id', sortable: true },
  { name: 'name', label: 'Product Name', align: 'left', field: 'name', sortable: true },
  {
    name: 'description',
    label: 'Description',
    align: 'left',
    field: 'description',
    sortable: false,
  },
  { name: 'price', label: 'Price', align: 'right', field: 'price', sortable: true },
  {
    name: 'category',
    label: 'Category',
    align: 'left',
    field: (row) => row.category.name,
    sortable: true,
  },
  {
    name: 'stock',
    label: 'Stock',
    align: 'right',
    field: (row) => row.inventory.stock,
    sortable: true,
  },
  {
    name: 'actions',
    label: 'Actions',
    align: 'right',
    field: 'actions',
    sortable: false,
  },
])

const insertProduct = async () => {
  loading.value = true
  try {
    const response = await api.post('/products', {
      name: name.value,
      description: description.value,
      price: price.value,
      category: category.value,
      stock: stock.value,
    })
    console.log('Product added successfully:', response.data)
    clearForm()
    await getData()
  } catch (error) {
    console.error('Failed to add product:', error)
  } finally {
    loading.value = false
  }
}

const openEditModal = (row) => {
  editForm.value = { ...row, category: row.category.name, stock: row.inventory.stock }
  isEditModalOpen.value = true
}

const closeEditModal = () => {
  isEditModalOpen.value = false
}

const updateProduct = async () => {
  loading.value = true
  try {
    const response = await api.put(`/products/${editForm.value.id}`, {
      name: editForm.value.name,
      description: editForm.value.description,
      price: editForm.value.price,
      category: editForm.value.category,
      stock: editForm.value.stock,
    })
    console.log('Product updated successfully:', response.data)
    closeEditModal()
    await getData()
  } catch (error) {
    console.error('Failed to update product:', error)
  } finally {
    loading.value = false
  }
}

const deleteProduct = async (id) => {
  loading.value = true
  try {
    await api.delete(`/products/${id}`)
    console.log('Product deleted successfully')
    await getData()
  } catch (error) {
    console.error('Failed to delete product:', error)
  } finally {
    loading.value = false
  }
}

const getData = async () => {
  loading.value = true
  try {
    const response = await api.get('/products')
    if (response.data && Array.isArray(response.data.products)) {
      data.value = response.data.products
    } else {
      console.error('Expected an array but received:', response.data)
      data.value = []
    }
  } catch (error) {
    console.error('Failed to fetch products:', error)
    data.value = []
  } finally {
    loading.value = false
  }
}

const clearForm = () => {
  name.value = ''
  description.value = ''
  price.value = 0
  category.value = ''
  stock.value = 0
}

onMounted(() => getData())
</script>

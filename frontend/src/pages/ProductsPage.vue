<template>
  <q-table :rows="rows" row-key="name" flat bordered />
  <div>
    <q-input v-model="name" label="Product Name" />
    <q-input v-model="description" label="Description" />
    <q-input v-model="price" label="Price" type="number" />
    <q-input v-model="category" label="Category" />
    <q-input v-model="stock" label="Stock" type="number" />
    <q-btn @click="insertProduct" label="Add Product" />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import api from 'src/boot/axios'

const name = ref('')
const description = ref('')
const price = ref(0)
const category = ref('')
const stock = ref(0)

const insertProduct = async () => {
  try {
    const response = await api.post('/products', {
      name: name.value,
      description: description.value,
      price: price.value,
      category: category.value,
      stock: stock.value,
    })
    console.log('Product added successfully:', response.data)
  } catch (error) {
    console.error('Failed to add product:', error)
  }
}

const rows = ref([
  {
    name: 'Frozen Yogurt',
    calories: 159,
    fat: 6.0,
    carbs: 24,
    protein: 4.0,
    sodium: 87,
    calcium: '14%',
    iron: '1%',
  },
  {
    name: 'Ice cream sandwich',
    calories: 237,
    fat: 9.0,
    carbs: 37,
    protein: 4.3,
    sodium: 129,
    calcium: '8%',
    iron: '1%',
  },
  {
    name: 'Eclair',
    calories: 262,
    fat: 16.0,
    carbs: 23,
    protein: 6.0,
    sodium: 337,
    calcium: '6%',
    iron: '7%',
  },
  {
    name: 'Cupcake',
    calories: 305,
    fat: 3.7,
    carbs: 67,
    protein: 4.3,
    sodium: 413,
    calcium: '3%',
    iron: '8%',
  },
  {
    name: 'Gingerbread',
    calories: 356,
    fat: 16.0,
    carbs: 49,
    protein: 3.9,
    sodium: 327,
    calcium: '7%',
    iron: '16%',
  },
  {
    name: 'Jelly bean',
    calories: 375,
    fat: 0.0,
    carbs: 94,
    protein: 0.0,
    sodium: 50,
    calcium: '0%',
    iron: '0%',
  },
  {
    name: 'Lollipop',
    calories: 392,
    fat: 0.2,
    carbs: 98,
    protein: 0,
    sodium: 38,
    calcium: '0%',
    iron: '2%',
  },
  {
    name: 'Honeycomb',
    calories: 408,
    fat: 3.2,
    carbs: 87,
    protein: 6.5,
    sodium: 562,
    calcium: '0%',
    iron: '45%',
  },
  {
    name: 'Donut',
    calories: 452,
    fat: 25.0,
    carbs: 51,
    protein: 4.9,
    sodium: 326,
    calcium: '2%',
    iron: '22%',
  },
  {
    name: 'KitKat',
    calories: 518,
    fat: 26.0,
    carbs: 65,
    protein: 7,
    sodium: 54,
    calcium: '12%',
    iron: '6%',
  },
])
</script>

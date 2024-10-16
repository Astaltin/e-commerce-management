<template>
  <section class="q-gutter-y-sm">
    <q-card v-for="item in cardItems" :key="item.title" tag="section" bordered flat>
      <q-card-section>
        <div class="row items-center justify-between">
          <span class="q-mb-none text-h6">{{ item.title }}</span>
          <q-icon :name="item.icon" class="text-h6" />
        </div>
        <span class="text-weight-bolder text-h3">{{ item.total }}</span>
      </q-card-section>
    </q-card>

    <q-card bordered flat>
      <apexchart type="bar" height="350" :options="chartOptions" :series="series"></apexchart>
    </q-card>
  </section>
</template>

<script setup>
defineOptions({
  name: 'DashboardPage',
})

const cardItems = [
  { title: 'Total Revenue', icon: 'currency_ruble', total: '₱1,213.00' },
  { title: 'Products', icon: 'add_box', total: '54,321' },
]

/** @type {import('apexcharts').ApexOptions} */
const chartOptions = {
  chart: {
    type: 'bar',
    height: 350,
  },
  plotOptions: {
    bar: {
      horizontal: false,
      columnWidth: '100%',
      endingShape: 'rounded',
    },
  },
  dataLabels: {
    enabled: false,
  },
  stroke: {
    show: true,
    width: 2,
    colors: ['transparent'],
  },
  xaxis: {
    categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
  },
  yaxis: {
    title: {
      text: '$ (thousands)',
    },
  },
  fill: {
    opacity: 1,
  },
  tooltip: {
    y: {
      formatter: function (val) {
        return '$ ' + val + ' thousands'
      },
    },
  },
}
const series = [
  {
    name: 'Net Profit',
    data: [44, 55, 57, 56, 61, 58, 63, 60, 66],
  },
  {
    name: 'Revenue',
    data: [76, 85, 101, 98, 87, 105, 91, 114, 94],
  },
  {
    name: 'Free Cash Flow',
    data: [35, 41, 36, 26, 45, 48, 52, 53, 41],
  },
]
</script>

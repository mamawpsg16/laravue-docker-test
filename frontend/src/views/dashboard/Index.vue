<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Data Table Examples</h1>
    
    <!-- Client-side Example -->
    <div class="mb-8">
      <h2 class="text-xl font-semibold mb-4">Client-side Table</h2>
      <DataTable
        :columns="columns"
        :data="clientData"
        :server-side="false"
        container-class="border rounded-lg"
        table-class="min-w-full"
        :page-size-options="[5, 10, 20]"
        :initial-page-size="5"
        empty-message="No billing records found"
      />
    </div>

    <!-- Server-side Example -->
    <!-- <div class="mb-8">
      <h2 class="text-xl font-semibold mb-4">Server-side Table</h2>
      <DataTable
        :columns="serverColumns"
        :data="serverData"
        :server-side="true"
        :total-rows="totalRows"
        :loading="loading"
        container-class="border rounded-lg shadow-sm"
        header-class="bg-blue-50"
        @update:page="handlePageChange"
        @update:page-size="handlePageSizeChange"
        @update:search="handleSearch" 
        @update:column-filters="handleColumnFilters"
        @update:sort="handleSort"
      />
    </div> -->
    <TanStackExample/>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import DataTable from '@/components/Table/TanStackTable.vue' // Adjust path as needed
import TanStackExample from './TanStackExample.vue'
// Client-side data
const clientData = ref([
  {
    name: 'Carlito Lacap',
    address: '1083 Mendiola Ext.',
    date: '2025-06-19',
    amount: '₱120.00',
    status: 'Paid',
    meter: 'AJP-15-24-099957',
    sequence: '1300',
    zone: 'Zone 7',
    remarks: 'On time',
    processed: 'Admin',
  },
  {
    name: 'Jane Doe',
    address: '123 Sample St.',
    date: '2025-06-10',
    amount: '₱98.00',
    status: 'Unpaid',
    meter: 'AJP-16-31-102039',
    sequence: '1301',
    zone: 'Zone 3',
    remarks: 'Late fee',
    processed: 'Staff A',
  },
  {
    name: 'John Smith',
    address: '456 Another Rd.',
    date: '2025-05-01',
    amount: '₱134.00',
    status: 'Paid',
    meter: 'AJP-17-45-001223',
    sequence: '1325',
    zone: 'Zone 1',
    remarks: 'Early payment',
    processed: 'Staff B',
  },
  // Add more sample data...
  {
    name: 'Maria Santos',
    address: '789 Test Ave.',
    date: '2025-05-15',
    amount: '₱156.00',
    status: 'Paid',
    meter: 'AJP-18-52-002456',
    sequence: '1350',
    zone: 'Zone 5',
    remarks: 'Regular payment',
    processed: 'Staff C',
  },
  {
    name: 'Pedro Garcia',
    address: '321 Demo Blvd.',
    date: '2025-04-20',
    amount: '₱87.50',
    status: 'Unpaid',
    meter: 'AJP-19-63-003789',
    sequence: '1375',
    zone: 'Zone 2',
    remarks: 'Overdue',
    processed: 'Staff D',
  },
])

// Server-side data
const serverData = ref([])
const totalRows = ref(0)
const loading = ref(false)

// Column definitions
const columns = ref([
  { 
    accessorKey: 'name', 
    header: 'Account Name',
    searchable: true,
    sortable: true
  },
  { 
    accessorKey: 'address', 
    header: 'Service Address',
    searchable: true
  },
  { 
    accessorKey: 'date', 
    header: 'Reading Date',
    searchable: true,
    sortable: true,
    formatter: (value) => new Date(value).toLocaleDateString()
  },
  { 
    accessorKey: 'amount', 
    header: 'Amount',
    searchable: false,
    sortable: true,
    cellClass: 'font-semibold text-green-600'
  },
  { 
    accessorKey: 'status', 
    header: 'Status',
    searchable: true,
    // Custom cell component
    cell: (props) => {
      const status = props.value
      const colorClass = status === 'Paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
      return `<span class="px-2 py-1 text-xs rounded-full ${colorClass}">${status}</span>`
    }
  },
  { 
    accessorKey: 'meter', 
    header: 'Meter No',
    searchable: true
  },
  { 
    accessorKey: 'sequence', 
    header: 'Sequence No',
    searchable: true,
    sortable: true
  },
  { 
    accessorKey: 'zone', 
    header: 'Zone',
    searchable: true
  },
  { 
    accessorKey: 'remarks', 
    header: 'Remarks',
    searchable: true
  },
  { 
    accessorKey: 'processed', 
    header: 'Processed By',
    searchable: true
  },
])

// Server-side columns with additional customization
const serverColumns = ref([
  ...columns.value,
  {
    accessorKey: 'actions',
    header: 'Actions',
    searchable: false,
    sortable: false,
    cell: () => `
      <div class="flex gap-2">
        <button class="px-2 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600">
          Edit
        </button>
        <button class="px-2 py-1 text-xs bg-red-500 text-white rounded hover:bg-red-600">
          Delete
        </button>
      </div>
    `
  }
])

// Server-side methods
const fetchServerData = async (params = {}) => {
  loading.value = true
  
  try {
    // Simulate API call delay
    await new Promise(resolve => setTimeout(resolve, 500))
    
    // Simulate API response
    const mockResponse = {
      data: clientData.value.slice(
        ((params.page || 1) - 1) * (params.pageSize || 10),
        (params.page || 1) * (params.pageSize || 10)
      ),
      total: clientData.value.length,
      page: params.page || 1,
      pageSize: params.pageSize || 10
    }
    
    serverData.value = mockResponse.data
    totalRows.value = mockResponse.total
  } catch (error) {
    console.error('Error fetching data:', error)
  } finally {
    loading.value = false
  }
}

const handlePageChange = (page) => {
  fetchServerData({ page, pageSize: 10 })
}

const handlePageSizeChange = (size) => {
  fetchServerData({ page: 1, pageSize: size })
}

const handleSearch = (searchTerm) => {
  console.log('Global search:', searchTerm)
  // Implement server-side search
  fetchServerData({ page: 1, search: searchTerm })
}

const handleColumnFilters = (filters) => {
  console.log('Column filters:', filters)
  // Implement server-side column filtering
  fetchServerData({ page: 1, filters })
}

const handleSort = (sortInfo) => {
  console.log('Sort info:', sortInfo)
  // Implement server-side sorting
  fetchServerData({ page: 1, sort: sortInfo })
}

// Initialize server data
onMounted(() => {
  fetchServerData()
})
</script>
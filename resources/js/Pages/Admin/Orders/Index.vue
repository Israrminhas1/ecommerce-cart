<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/UI/Pagination.vue';
import { ref } from 'vue';

const props = defineProps({
    orders: Object,
    filters: Object,
    statuses: Array,
});

const search = ref(props.filters.search || '');
const status = ref(props.filters.status || '');

const applyFilters = () => {
    const params = {};
    if (search.value) params.search = search.value;
    if (status.value) params.status = status.value;

    router.get(route('admin.orders.index'), params, {
        preserveState: true,
        preserveScroll: true,
    });
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(price);
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const statusConfig = {
    pending: { label: 'Pending', class: 'bg-yellow-100 text-yellow-800' },
    processing: { label: 'Processing', class: 'bg-blue-100 text-blue-800' },
    completed: { label: 'Completed', class: 'bg-green-100 text-green-800' },
    cancelled: { label: 'Cancelled', class: 'bg-red-100 text-red-800' },
};
</script>

<template>
    <Head title="Manage Orders" />

    <AdminLayout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-8">Orders</h1>

                <!-- Filters -->
                <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Search by order number or customer..."
                            @keyup.enter="applyFilters"
                            class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        />

                        <select
                            v-model="status"
                            @change="applyFilters"
                            class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">All Statuses</option>
                            <option v-for="s in statuses" :key="s" :value="s">
                                {{ statusConfig[s].label }}
                            </option>
                        </select>

                        <button
                            @click="applyFilters"
                            class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors"
                        >
                            Apply Filters
                        </button>
                    </div>
                </div>

                <!-- Orders Table -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Order
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Customer
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="order in orders.data" :key="order.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="font-medium text-gray-900">{{ order.order_number }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-gray-900">{{ order.user?.name }}</p>
                                    <p class="text-sm text-gray-500">{{ order.user?.email }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        :class="statusConfig[order.status].class"
                                        class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium"
                                    >
                                        {{ statusConfig[order.status].label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                    {{ formatPrice(order.total) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ formatDate(order.created_at) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <Link
                                        :href="route('admin.orders.show', order.id)"
                                        class="text-indigo-600 hover:text-indigo-900"
                                    >
                                        View Details
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    <Pagination :links="orders.links" />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

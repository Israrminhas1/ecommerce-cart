<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    stats: Object,
    stockSummary: Object,
    lowStockProducts: Array,
    outOfStockProducts: Array,
    recentOrders: Array,
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(price);
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
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
    <Head title="Admin Dashboard" />

    <AdminLayout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-8">Admin Dashboard</h1>

                <!-- Stats Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <p class="text-sm text-gray-500 mb-1">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900">{{ stats.total_users }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <p class="text-sm text-gray-500 mb-1">Total Products</p>
                        <p class="text-2xl font-bold text-gray-900">{{ stats.total_products }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <p class="text-sm text-gray-500 mb-1">Orders Today</p>
                        <p class="text-2xl font-bold text-gray-900">{{ stats.orders_today }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <p class="text-sm text-gray-500 mb-1">Revenue Today</p>
                        <p class="text-2xl font-bold text-green-600">{{ formatPrice(stats.revenue_today) }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Stock Summary -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Stock Overview</h2>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-green-500"></span>
                                    <span class="text-gray-600">In Stock</span>
                                </div>
                                <span class="font-semibold text-gray-900">{{ stockSummary.in_stock }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                                    <span class="text-gray-600">Low Stock</span>
                                </div>
                                <span class="font-semibold text-amber-600">{{ stockSummary.low_stock }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-red-500"></span>
                                    <span class="text-gray-600">Out of Stock</span>
                                </div>
                                <span class="font-semibold text-red-600">{{ stockSummary.out_of_stock }}</span>
                            </div>
                        </div>

                        <!-- Low Stock Products -->
                        <div v-if="lowStockProducts.length > 0" class="mt-6 pt-6 border-t">
                            <h3 class="text-sm font-medium text-gray-900 mb-3">Low Stock Alert</h3>
                            <div class="space-y-2">
                                <div
                                    v-for="product in lowStockProducts"
                                    :key="product.id"
                                    class="flex items-center justify-between text-sm"
                                >
                                    <span class="text-gray-600 truncate">{{ product.name }}</span>
                                    <span class="text-amber-600 font-medium">{{ product.stock_quantity }} left</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Recent Orders</h2>
                            <Link
                                :href="route('admin.orders.index')"
                                class="text-sm text-indigo-600 hover:text-indigo-700 font-medium"
                            >
                                View All
                            </Link>
                        </div>

                        <div class="space-y-4">
                            <div
                                v-for="order in recentOrders"
                                :key="order.id"
                                class="flex items-center justify-between py-3 border-b last:border-0"
                            >
                                <div>
                                    <p class="font-medium text-gray-900">{{ order.order_number }}</p>
                                    <p class="text-sm text-gray-500">{{ order.user?.name }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">{{ formatPrice(order.total) }}</p>
                                    <span
                                        :class="statusConfig[order.status].class"
                                        class="inline-block px-2 py-0.5 rounded text-xs font-medium"
                                    >
                                        {{ statusConfig[order.status].label }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-4">
                    <Link
                        :href="route('admin.products.index')"
                        class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow flex items-center gap-4"
                    >
                        <div class="w-12 h-12 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Products</p>
                            <p class="text-sm text-gray-500">Manage inventory</p>
                        </div>
                    </Link>

                    <Link
                        :href="route('admin.orders.index')"
                        class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow flex items-center gap-4"
                    >
                        <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Orders</p>
                            <p class="text-sm text-gray-500">{{ stats.pending_orders }} pending</p>
                        </div>
                    </Link>

                    <Link
                        :href="route('admin.reports.index')"
                        class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow flex items-center gap-4"
                    >
                        <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Reports</p>
                            <p class="text-sm text-gray-500">View analytics</p>
                        </div>
                    </Link>

                    <Link
                        :href="route('products.index')"
                        class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow flex items-center gap-4"
                    >
                        <div class="w-12 h-12 rounded-lg bg-amber-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Storefront</p>
                            <p class="text-sm text-gray-500">View store</p>
                        </div>
                    </Link>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

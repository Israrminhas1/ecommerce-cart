<script setup>
import { Head, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    summary: Object,
    dailyData: Object,
    topProducts: Array,
    todaysSales: Object,
    filters: Object,
});

const startDate = ref(props.filters.start_date);
const endDate = ref(props.filters.end_date);
const isGenerating = ref(false);

const applyFilters = () => {
    router.get(route('admin.reports.index'), {
        start_date: startDate.value,
        end_date: endDate.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const generateReport = () => {
    isGenerating.value = true;
    router.post(route('admin.reports.generate'), {}, {
        preserveScroll: true,
        onFinish: () => {
            isGenerating.value = false;
        },
    });
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(price);
};
</script>

<template>
    <Head title="Sales Reports" />

    <AdminLayout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">Sales Reports</h1>
                    <button
                        @click="generateReport"
                        :disabled="isGenerating"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 disabled:bg-indigo-400 transition-colors"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" />
                        </svg>
                        {{ isGenerating ? 'Generating...' : 'Email Daily Report' }}
                    </button>
                </div>

                <!-- Date Filters -->
                <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
                    <div class="flex flex-wrap items-center gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input
                                v-model="startDate"
                                type="date"
                                class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input
                                v-model="endDate"
                                type="date"
                                class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            />
                        </div>
                        <div class="pt-6">
                            <button
                                @click="applyFilters"
                                class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors"
                            >
                                Apply
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Today's Summary -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl p-6 mb-6 text-white">
                    <h2 class="text-lg font-semibold mb-4">Today's Sales</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-indigo-200 text-sm">Revenue</p>
                            <p class="text-2xl font-bold">{{ formatPrice(todaysSales.total_revenue) }}</p>
                        </div>
                        <div>
                            <p class="text-indigo-200 text-sm">Orders</p>
                            <p class="text-2xl font-bold">{{ todaysSales.total_orders }}</p>
                        </div>
                        <div>
                            <p class="text-indigo-200 text-sm">Items Sold</p>
                            <p class="text-2xl font-bold">{{ todaysSales.total_items_sold }}</p>
                        </div>
                        <div>
                            <p class="text-indigo-200 text-sm">Avg. Order</p>
                            <p class="text-2xl font-bold">
                                {{ todaysSales.total_orders > 0 ? formatPrice(todaysSales.total_revenue / todaysSales.total_orders) : '$0.00' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Period Summary -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <p class="text-sm text-gray-500 mb-1">Total Revenue</p>
                        <p class="text-2xl font-bold text-green-600">{{ formatPrice(summary.total_revenue) }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <p class="text-sm text-gray-500 mb-1">Total Orders</p>
                        <p class="text-2xl font-bold text-gray-900">{{ summary.total_orders }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <p class="text-sm text-gray-500 mb-1">Items Sold</p>
                        <p class="text-2xl font-bold text-gray-900">{{ summary.total_items }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <p class="text-sm text-gray-500 mb-1">Avg. Order Value</p>
                        <p class="text-2xl font-bold text-gray-900">{{ formatPrice(summary.average_order_value) }}</p>
                    </div>
                </div>

                <!-- Top Products -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Top Selling Products</h2>

                    <div v-if="topProducts.length > 0" class="space-y-4">
                        <div
                            v-for="(product, index) in topProducts"
                            :key="index"
                            class="flex items-center justify-between py-3 border-b last:border-0"
                        >
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 font-semibold flex items-center justify-center text-sm">
                                    {{ index + 1 }}
                                </span>
                                <div>
                                    <p class="font-medium text-gray-900">{{ product.name }}</p>
                                    <p class="text-sm text-gray-500">{{ product.quantity }} units sold</p>
                                </div>
                            </div>
                            <p class="font-semibold text-gray-900">{{ formatPrice(product.revenue) }}</p>
                        </div>
                    </div>

                    <p v-else class="text-gray-500 text-center py-8">
                        No sales data for this period.
                    </p>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

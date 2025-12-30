<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/UI/Pagination.vue';
import EmptyState from '@/Components/UI/EmptyState.vue';

const props = defineProps({
    orders: Object,
});

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
    <Head title="My Orders" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">My Orders</h1>

                <div v-if="orders?.data?.length > 0" class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="order in orders.data" :key="order.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-medium text-gray-900">{{ order.order_number }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ formatDate(order.created_at) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        :class="statusConfig[order.status].class"
                                        class="px-2.5 py-0.5 rounded-full text-xs font-medium"
                                    >
                                        {{ statusConfig[order.status].label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900">
                                    {{ formatPrice(order.total) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <Link
                                        :href="route('orders.show', order.id)"
                                        class="text-indigo-600 hover:text-indigo-900 font-medium text-sm"
                                    >
                                        View
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="px-6 py-4 border-t">
                        <Pagination :links="orders.links" />
                    </div>
                </div>

                <EmptyState
                    v-else
                    title="No orders yet"
                    description="Start shopping to see your orders here."
                    icon="order"
                    action-label="Start Shopping"
                    :action-route="route('products.index')"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

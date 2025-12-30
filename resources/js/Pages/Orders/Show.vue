<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Breadcrumb from '@/Components/UI/Breadcrumb.vue';

const props = defineProps({
    order: Object,
});

const breadcrumbs = [
    { label: 'My Orders', href: route('orders.index') },
    { label: props.order.order_number },
];

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(price);
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const statusConfig = {
    pending: { label: 'Pending', class: 'bg-yellow-100 text-yellow-800', icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' },
    processing: { label: 'Processing', class: 'bg-blue-100 text-blue-800', icon: 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15' },
    completed: { label: 'Completed', class: 'bg-green-100 text-green-800', icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' },
    cancelled: { label: 'Cancelled', class: 'bg-red-100 text-red-800', icon: 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z' },
};
</script>

<template>
    <Head :title="`Order ${order.order_number}`" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumb -->
                <div class="mb-6">
                    <Breadcrumb :items="breadcrumbs" />
                </div>

                <!-- Order Header -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">
                                {{ order.order_number }}
                            </h1>
                            <p class="text-gray-500 mt-1">
                                Placed on {{ formatDate(order.created_at) }}
                            </p>
                        </div>

                        <span
                            :class="statusConfig[order.status].class"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" :d="statusConfig[order.status].icon" />
                            </svg>
                            {{ statusConfig[order.status].label }}
                        </span>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                    <div class="p-6 border-b">
                        <h2 class="text-lg font-semibold text-gray-900">Order Items</h2>
                    </div>

                    <div class="divide-y">
                        <div
                            v-for="item in order.items"
                            :key="item.id"
                            class="p-6 flex items-center gap-4"
                        >
                            <img
                                :src="item.product?.image || 'https://via.placeholder.com/80'"
                                :alt="item.product_name"
                                class="w-20 h-20 object-cover rounded-lg"
                            />

                            <div class="flex-1 min-w-0">
                                <Link
                                    v-if="item.product"
                                    :href="route('products.show', item.product.slug)"
                                    class="font-medium text-gray-900 hover:text-indigo-600"
                                >
                                    {{ item.product_name }}
                                </Link>
                                <p v-else class="font-medium text-gray-900">
                                    {{ item.product_name }}
                                </p>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ formatPrice(item.price) }} x {{ item.quantity }}
                                </p>
                            </div>

                            <p class="font-semibold text-gray-900">
                                {{ formatPrice(item.price * item.quantity) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>

                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium text-gray-900">{{ formatPrice(order.subtotal) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-medium text-green-600">FREE</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax</span>
                            <span class="font-medium text-gray-900">{{ formatPrice(order.tax) }}</span>
                        </div>
                        <div class="border-t pt-3">
                            <div class="flex justify-between">
                                <span class="text-lg font-semibold text-gray-900">Total</span>
                                <span class="text-xl font-bold text-gray-900">{{ formatPrice(order.total) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Notes -->
                <div v-if="order.notes" class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-2">Order Notes</h2>
                    <p class="text-gray-600">{{ order.notes }}</p>
                </div>

                <!-- Actions -->
                <div class="flex justify-between">
                    <Link
                        :href="route('orders.index')"
                        class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-700 font-medium"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Back to Orders
                    </Link>

                    <Link
                        :href="route('products.index')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors"
                    >
                        Continue Shopping
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

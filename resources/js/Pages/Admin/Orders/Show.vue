<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    order: Object,
    statuses: Array,
});

const selectedStatus = ref(props.order.status);
const isUpdating = ref(false);

const updateStatus = () => {
    isUpdating.value = true;
    router.patch(route('admin.orders.update', props.order.id), {
        status: selectedStatus.value,
    }, {
        preserveScroll: true,
        onFinish: () => {
            isUpdating.value = false;
        },
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
        month: 'long',
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
    <Head :title="`Order ${order.order_number}`" />

    <AdminLayout>
        <div class="py-8">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-8">
                    <Link
                        :href="route('admin.orders.index')"
                        class="text-indigo-600 hover:text-indigo-700 font-medium flex items-center gap-2"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Back to Orders
                    </Link>
                </div>

                <!-- Order Header -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ order.order_number }}</h1>
                            <p class="text-gray-500 mt-1">{{ formatDate(order.created_at) }}</p>
                        </div>

                        <div class="flex items-center gap-4">
                            <select
                                v-model="selectedStatus"
                                class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option v-for="s in statuses" :key="s" :value="s">
                                    {{ statusConfig[s].label }}
                                </option>
                            </select>
                            <button
                                @click="updateStatus"
                                :disabled="isUpdating || selectedStatus === order.status"
                                class="px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors"
                            >
                                {{ isUpdating ? 'Updating...' : 'Update Status' }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Name</p>
                            <p class="font-medium text-gray-900">{{ order.user?.name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium text-gray-900">{{ order.user?.email }}</p>
                        </div>
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
                                class="w-16 h-16 object-cover rounded-lg"
                            />
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ item.product_name }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ formatPrice(item.price) }} x {{ item.quantity }}
                                </p>
                            </div>
                            <p class="font-semibold text-gray-900">
                                {{ formatPrice(item.price * item.quantity) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Order Totals -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium text-gray-900">{{ formatPrice(order.subtotal) }}</span>
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
                <div v-if="order.notes" class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-2">Order Notes</h2>
                    <p class="text-gray-600">{{ order.notes }}</p>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

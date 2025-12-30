<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    order: Object,
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(price);
};
</script>

<template>
    <Head title="Order Confirmed" />

    <AuthenticatedLayout>
        <div class="py-16">
            <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <!-- Success Icon -->
                <div class="mx-auto w-24 h-24 rounded-full bg-green-100 flex items-center justify-center mb-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <!-- Success Message -->
                <h1 class="text-3xl font-bold text-gray-900 mb-4">
                    Order Confirmed!
                </h1>

                <p class="text-lg text-gray-600 mb-2">
                    Thank you for your purchase
                </p>

                <p class="text-gray-500 mb-8">
                    Your order <span class="font-semibold text-gray-900">{{ order.order_number }}</span> has been placed successfully.
                </p>

                <!-- Order Summary Card -->
                <div class="bg-gray-50 rounded-xl p-6 mb-8 text-left">
                    <h3 class="font-semibold text-gray-900 mb-4">Order Summary</h3>

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Order Number</span>
                            <span class="font-medium text-gray-900">{{ order.order_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Items</span>
                            <span class="font-medium text-gray-900">{{ order.items?.length || 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total</span>
                            <span class="font-medium text-gray-900">{{ formatPrice(order.total) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Email Notice -->
                <div class="bg-blue-50 rounded-xl p-4 mb-8">
                    <div class="flex items-center gap-3 text-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                        <p class="text-sm">
                            A confirmation email will be sent to your email address.
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <Link
                        :href="route('orders.show', order.id)"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                        </svg>
                        View Order
                    </Link>

                    <Link
                        :href="route('products.index')"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors"
                    >
                        Continue Shopping
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import OrderSummary from '@/Components/Shop/OrderSummary.vue';

const props = defineProps({
    cart: Object,
    items: Array,
    totals: Object,
    stockErrors: Array,
});

const notes = ref('');
const isProcessing = ref(false);

const placeOrder = () => {
    isProcessing.value = true;
    router.post(route('orders.store'), {
        notes: notes.value,
    }, {
        onFinish: () => {
            isProcessing.value = false;
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
    <Head title="Checkout" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-8">Checkout</h1>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Order Details -->
                    <div class="lg:col-span-2">
                        <!-- Order Items Summary -->
                        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h2>

                            <div class="divide-y">
                                <div
                                    v-for="item in items"
                                    :key="item.id"
                                    class="flex items-center gap-4 py-4 first:pt-0 last:pb-0"
                                >
                                    <img
                                        :src="item.product?.image || 'https://via.placeholder.com/80'"
                                        :alt="item.product?.name"
                                        class="w-16 h-16 object-cover rounded-lg"
                                    />
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 truncate">
                                            {{ item.product?.name || 'Product' }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Qty: {{ item.quantity }} x {{ formatPrice(item.price_at_addition) }}
                                        </p>
                                    </div>
                                    <p class="font-semibold text-gray-900">
                                        {{ formatPrice(item.quantity * item.price_at_addition) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Order Notes -->
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Notes (Optional)</h2>
                            <textarea
                                v-model="notes"
                                rows="3"
                                placeholder="Add any special instructions or notes for your order..."
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            ></textarea>
                        </div>
                    </div>

                    <!-- Order Summary & Payment -->
                    <div class="lg:col-span-1">
                        <div class="bg-gray-50 rounded-xl p-6 sticky top-24">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>

                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal ({{ totals.item_count }} items)</span>
                                    <span class="font-medium text-gray-900">{{ formatPrice(totals.subtotal) }}</span>
                                </div>

                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Shipping</span>
                                    <span class="font-medium text-green-600">FREE</span>
                                </div>

                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Tax (10%)</span>
                                    <span class="font-medium text-gray-900">{{ formatPrice(totals.tax) }}</span>
                                </div>

                                <div class="border-t pt-3 mt-3">
                                    <div class="flex justify-between">
                                        <span class="text-base font-semibold text-gray-900">Total</span>
                                        <span class="text-xl font-bold text-gray-900">{{ formatPrice(totals.total) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6">
                                <button
                                    @click="placeOrder"
                                    :disabled="isProcessing || stockErrors.length > 0"
                                    class="w-full py-3 px-4 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors flex items-center justify-center gap-2"
                                >
                                    <svg v-if="isProcessing" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                    {{ isProcessing ? 'Processing...' : 'Place Order' }}
                                </button>

                                <Link
                                    :href="route('cart.index')"
                                    class="block mt-3 text-center text-sm text-indigo-600 hover:text-indigo-700 font-medium"
                                >
                                    Back to Cart
                                </Link>
                            </div>

                            <p class="mt-4 text-center text-xs text-gray-500">
                                By placing your order, you agree to our terms and conditions.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

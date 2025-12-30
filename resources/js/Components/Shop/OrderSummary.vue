<script setup>
import { computed } from 'vue';

const props = defineProps({
    totals: {
        type: Object,
        required: true,
    },
    showCheckout: {
        type: Boolean,
        default: true,
    },
    hasErrors: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['checkout']);

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(price);
};
</script>

<template>
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

        <div v-if="showCheckout" class="mt-6">
            <button
                @click="$emit('checkout')"
                :disabled="hasErrors || totals.item_count === 0"
                class="w-full py-3 px-4 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors flex items-center justify-center gap-2"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                </svg>
                Proceed to Checkout
            </button>

            <p class="mt-3 text-center text-xs text-gray-500">
                Secure checkout powered by our platform
            </p>
        </div>

        <!-- Trust Badges -->
        <div class="mt-6 pt-6 border-t">
            <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                Free shipping on all orders
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                30-day return policy
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                Secure payment processing
            </div>
        </div>
    </div>
</template>

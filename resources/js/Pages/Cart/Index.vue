<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CartItem from '@/Components/Shop/CartItem.vue';
import OrderSummary from '@/Components/Shop/OrderSummary.vue';
import EmptyState from '@/Components/UI/EmptyState.vue';
import { computed } from 'vue';

const props = defineProps({
    cart: Object,
    items: Array,
    totals: Object,
    stockErrors: Array,
});

const hasErrors = computed(() => props.stockErrors.length > 0);

const getStockError = (itemId) => {
    return props.stockErrors.find(error => error.item_id === itemId);
};

const goToCheckout = () => {
    router.visit(route('checkout'));
};

const clearCart = () => {
    if (confirm('Are you sure you want to clear your cart?')) {
        router.delete(route('cart.clear'));
    }
};
</script>

<template>
    <Head title="Shopping Cart" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-8">
                    Shopping Cart
                    <span v-if="totals.item_count > 0" class="text-gray-500 font-normal">
                        ({{ totals.item_count }} {{ totals.item_count === 1 ? 'item' : 'items' }})
                    </span>
                </h1>

                <!-- Cart with items -->
                <div v-if="items.length > 0" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Cart Items -->
                    <div class="lg:col-span-2 space-y-4">
                        <!-- Stock Errors Warning -->
                        <div v-if="hasErrors" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                            <div class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-red-800">
                                        Some items in your cart have stock issues
                                    </p>
                                    <p class="text-sm text-red-600 mt-1">
                                        Please update quantities or remove unavailable items before checkout.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <CartItem
                            v-for="item in items"
                            :key="item.id"
                            :item="item"
                            :stock-error="getStockError(item.id)"
                        />

                        <!-- Actions -->
                        <div class="flex items-center justify-between pt-4">
                            <Link
                                :href="route('products.index')"
                                class="text-indigo-600 hover:text-indigo-700 font-medium flex items-center gap-2"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                                </svg>
                                Continue Shopping
                            </Link>

                            <button
                                @click="clearCart"
                                class="text-red-600 hover:text-red-700 font-medium flex items-center gap-2"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                Clear Cart
                            </button>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <OrderSummary
                            :totals="totals"
                            :has-errors="hasErrors"
                            @checkout="goToCheckout"
                        />
                    </div>
                </div>

                <!-- Empty Cart -->
                <EmptyState
                    v-else
                    title="Your cart is empty"
                    description="Looks like you haven't added anything to your cart yet."
                    icon="cart"
                    action-label="Start Shopping"
                    :action-route="route('products.index')"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

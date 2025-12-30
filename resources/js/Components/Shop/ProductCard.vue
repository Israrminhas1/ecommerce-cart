<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import WishlistButton from './WishlistButton.vue';
import StockBadge from './StockBadge.vue';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
    isInWishlist: {
        type: Boolean,
        default: false,
    },
});

const isAddingToCart = ref(false);

const addToCart = () => {
    isAddingToCart.value = true;
    router.post(route('cart.items.store'), {
        product_id: props.product.id,
        quantity: 1,
    }, {
        preserveScroll: true,
        onFinish: () => {
            isAddingToCart.value = false;
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
    <div class="group bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-100">
        <!-- Product Image -->
        <div class="relative aspect-square overflow-hidden bg-gray-100">
            <Link :href="route('products.show', product.slug)">
                <img
                    :src="product.image || 'https://via.placeholder.com/400'"
                    :alt="product.name"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                />
            </Link>

            <!-- Wishlist Button -->
            <div class="absolute top-3 right-3">
                <WishlistButton :product="product" :is-in-wishlist="isInWishlist" />
            </div>

            <!-- Stock Badge -->
            <div class="absolute top-3 left-3">
                <StockBadge :stock="product.stock_quantity" :threshold="product.low_stock_threshold" />
            </div>

            <!-- Quick Add Button (appears on hover) -->
            <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <button
                    @click="addToCart"
                    :disabled="isAddingToCart || product.stock_quantity === 0"
                    class="w-full py-2.5 px-4 bg-white text-gray-900 font-semibold rounded-lg hover:bg-gray-100 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors flex items-center justify-center gap-2"
                >
                    <svg v-if="isAddingToCart" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                    </svg>
                    <span v-if="product.stock_quantity === 0">Out of Stock</span>
                    <span v-else>Add to Cart</span>
                </button>
            </div>
        </div>

        <!-- Product Info -->
        <div class="p-4">
            <!-- Category -->
            <p v-if="product.category" class="text-xs text-indigo-600 font-medium uppercase tracking-wide mb-1">
                {{ product.category.name }}
            </p>

            <!-- Name -->
            <Link :href="route('products.show', product.slug)" class="block">
                <h3 class="font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-2">
                    {{ product.name }}
                </h3>
            </Link>

            <!-- Price -->
            <div class="mt-2 flex items-center justify-between">
                <p class="text-lg font-bold text-gray-900">
                    {{ formatPrice(product.price) }}
                </p>
                <p v-if="product.stock_quantity > 0 && product.stock_quantity <= 10" class="text-xs text-amber-600 font-medium">
                    Only {{ product.stock_quantity }} left
                </p>
            </div>
        </div>
    </div>
</template>

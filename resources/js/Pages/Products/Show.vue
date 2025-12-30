<script setup>
import { ref } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import ShopLayout from '@/Layouts/ShopLayout.vue';
import ProductCard from '@/Components/Shop/ProductCard.vue';
import QuantitySelector from '@/Components/Shop/QuantitySelector.vue';
import StockBadge from '@/Components/Shop/StockBadge.vue';
import WishlistButton from '@/Components/Shop/WishlistButton.vue';
import Breadcrumb from '@/Components/UI/Breadcrumb.vue';

const props = defineProps({
    product: Object,
    relatedProducts: Array,
    isInWishlist: Boolean,
});

const page = usePage();
const quantity = ref(1);
const isAddingToCart = ref(false);

const breadcrumbs = [
    { label: 'Products', href: route('products.index') },
    { label: props.product.category?.name, href: route('products.index', { category: props.product.category?.slug }) },
    { label: props.product.name },
];

const addToCart = () => {
    if (!page.props.auth.user) {
        router.visit(route('login'));
        return;
    }

    isAddingToCart.value = true;
    router.post(route('cart.items.store'), {
        product_id: props.product.id,
        quantity: quantity.value,
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
    <Head :title="product.name" />

    <ShopLayout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumb -->
                <div class="mb-6">
                    <Breadcrumb :items="breadcrumbs" />
                </div>

                <!-- Product Details -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-6 lg:p-8">
                        <!-- Product Image -->
                        <div class="aspect-square bg-gray-100 rounded-xl overflow-hidden">
                            <img
                                :src="product.image || 'https://via.placeholder.com/600'"
                                :alt="product.name"
                                class="w-full h-full object-cover"
                            />
                        </div>

                        <!-- Product Info -->
                        <div class="flex flex-col">
                            <!-- Category -->
                            <p v-if="product.category" class="text-sm text-indigo-600 font-medium uppercase tracking-wide mb-2">
                                {{ product.category.name }}
                            </p>

                            <!-- Name -->
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4">
                                {{ product.name }}
                            </h1>

                            <!-- Price -->
                            <p class="text-3xl font-bold text-gray-900 mb-4">
                                {{ formatPrice(product.price) }}
                            </p>

                            <!-- Stock Status -->
                            <div class="mb-6">
                                <StockBadge
                                    :stock="product.stock_quantity"
                                    :threshold="product.low_stock_threshold"
                                    :show-count="true"
                                />
                            </div>

                            <!-- Quantity & Add to Cart -->
                            <div v-if="product.stock_quantity > 0" class="flex flex-col sm:flex-row gap-4 mb-6">
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-medium text-gray-700">Quantity:</span>
                                    <QuantitySelector
                                        v-model="quantity"
                                        :max="product.stock_quantity"
                                    />
                                </div>

                                <button
                                    @click="addToCart"
                                    :disabled="isAddingToCart"
                                    class="flex-1 sm:flex-none px-8 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 disabled:bg-indigo-400 transition-colors flex items-center justify-center gap-2"
                                >
                                    <svg v-if="isAddingToCart" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                    </svg>
                                    Add to Cart
                                </button>
                            </div>

                            <!-- Out of Stock Message -->
                            <div v-else class="mb-6">
                                <button
                                    disabled
                                    class="w-full sm:w-auto px-8 py-3 bg-gray-300 text-gray-500 font-semibold rounded-lg cursor-not-allowed"
                                >
                                    Out of Stock
                                </button>
                            </div>

                            <!-- Wishlist Button -->
                            <div class="flex items-center gap-2 mb-6">
                                <WishlistButton :product="product" :is-in-wishlist="isInWishlist" size="lg" />
                                <span class="text-sm text-gray-600">
                                    {{ isInWishlist ? 'Remove from wishlist' : 'Add to wishlist' }}
                                </span>
                            </div>

                            <!-- Features -->
                            <div class="border-t pt-6 space-y-3">
                                <div class="flex items-center gap-3 text-sm text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Free shipping on orders over $50
                                </div>
                                <div class="flex items-center gap-3 text-sm text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    30-day return policy
                                </div>
                                <div class="flex items-center gap-3 text-sm text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Secure checkout
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="border-t px-6 lg:px-8 py-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Description</h2>
                        <div class="prose prose-gray max-w-none">
                            <p class="text-gray-600 whitespace-pre-line">{{ product.description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Related Products -->
                <div v-if="relatedProducts.length > 0" class="mt-12">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Related Products</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                        <ProductCard
                            v-for="related in relatedProducts"
                            :key="related.id"
                            :product="related"
                        />
                    </div>
                </div>
            </div>
        </div>
    </ShopLayout>
</template>

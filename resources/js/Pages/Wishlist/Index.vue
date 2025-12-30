<script setup>
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/UI/Pagination.vue';
import EmptyState from '@/Components/UI/EmptyState.vue';
import StockBadge from '@/Components/Shop/StockBadge.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    items: Object,
});

const removeFromWishlist = (product) => {
    router.post(route('wishlist.toggle', product.slug), {}, {
        preserveScroll: true,
    });
};

const moveToCart = (product) => {
    router.post(route('wishlist.move-to-cart', product.slug), {}, {
        preserveScroll: true,
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
    <Head title="Wishlist" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-8">
                    My Wishlist
                    <span v-if="items.data.length > 0" class="text-gray-500 font-normal">
                        ({{ items.total }} {{ items.total === 1 ? 'item' : 'items' }})
                    </span>
                </h1>

                <div v-if="items.data.length > 0">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        <div
                            v-for="item in items.data"
                            :key="item.id"
                            class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group"
                        >
                            <!-- Product Image -->
                            <Link :href="route('products.show', item.product.slug)" class="block aspect-square bg-gray-100 overflow-hidden">
                                <img
                                    :src="item.product.image || 'https://via.placeholder.com/400'"
                                    :alt="item.product.name"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                />
                            </Link>

                            <!-- Product Info -->
                            <div class="p-4">
                                <Link :href="route('products.show', item.product.slug)">
                                    <h3 class="font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-2 mb-2">
                                        {{ item.product.name }}
                                    </h3>
                                </Link>

                                <p class="text-lg font-bold text-gray-900 mb-3">
                                    {{ formatPrice(item.product.price) }}
                                </p>

                                <StockBadge
                                    :stock="item.product.stock_quantity"
                                    :threshold="item.product.low_stock_threshold"
                                />

                                <!-- Actions -->
                                <div class="mt-4 flex gap-2">
                                    <button
                                        @click="moveToCart(item.product)"
                                        :disabled="item.product.stock_quantity === 0"
                                        class="flex-1 py-2 px-3 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors flex items-center justify-center gap-1"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                        </svg>
                                        Add to Cart
                                    </button>

                                    <button
                                        @click="removeFromWishlist(item.product)"
                                        class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                        title="Remove from wishlist"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <Pagination :links="items.links" />
                    </div>
                </div>

                <EmptyState
                    v-else
                    title="Your wishlist is empty"
                    description="Save items you love by clicking the heart icon on any product."
                    icon="heart"
                    action-label="Start Shopping"
                    :action-route="route('products.index')"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

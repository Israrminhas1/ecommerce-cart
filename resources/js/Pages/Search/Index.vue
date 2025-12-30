<script setup>
import { Head } from '@inertiajs/vue3';
import ShopLayout from '@/Layouts/ShopLayout.vue';
import ProductCard from '@/Components/Shop/ProductCard.vue';
import Pagination from '@/Components/UI/Pagination.vue';
import EmptyState from '@/Components/UI/EmptyState.vue';
import SearchBar from '@/Components/Shop/SearchBar.vue';

const props = defineProps({
    products: Object,
    query: String,
    wishlistIds: Array,
});

const isInWishlist = (productId) => {
    return props.wishlistIds?.includes(productId) || false;
};
</script>

<template>
    <Head :title="`Search: ${query}`" />

    <ShopLayout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Search Header -->
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-900 mb-4">Search Results</h1>
                    <div class="max-w-lg">
                        <SearchBar :initial-query="query" />
                    </div>
                </div>

                <!-- Results Info -->
                <div v-if="query" class="mb-6">
                    <p class="text-gray-600">
                        <span v-if="products.data?.length > 0">
                            Found <span class="font-semibold">{{ products.total }}</span> results for
                        </span>
                        <span v-else>No results for</span>
                        "<span class="font-semibold">{{ query }}</span>"
                    </p>
                </div>

                <!-- Products Grid -->
                <div v-if="products.data?.length > 0" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                    <ProductCard
                        v-for="product in products.data"
                        :key="product.id"
                        :product="product"
                        :is-in-wishlist="isInWishlist(product.id)"
                    />
                </div>

                <!-- Empty State -->
                <EmptyState
                    v-else-if="query"
                    title="No products found"
                    description="Try a different search term or browse our categories."
                    icon="search"
                    action-label="Browse Products"
                    :action-route="route('products.index')"
                />

                <!-- Initial State -->
                <EmptyState
                    v-else
                    title="Search for products"
                    description="Enter a search term to find products in our catalog."
                    icon="search"
                />

                <!-- Pagination -->
                <div v-if="products.links" class="mt-8">
                    <Pagination :links="products.links" />
                </div>
            </div>
        </div>
    </ShopLayout>
</template>

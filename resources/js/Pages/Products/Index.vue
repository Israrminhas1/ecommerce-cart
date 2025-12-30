<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import ShopLayout from '@/Layouts/ShopLayout.vue';
import ProductCard from '@/Components/Shop/ProductCard.vue';
import CategoryFilter from '@/Components/Shop/CategoryFilter.vue';
import Pagination from '@/Components/UI/Pagination.vue';
import EmptyState from '@/Components/UI/EmptyState.vue';
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    products: Object,
    categories: Array,
    filters: Object,
    wishlistIds: Array,
});

const page = usePage();

const sortOptions = [
    { value: 'created_at-desc', label: 'Newest' },
    { value: 'price-asc', label: 'Price: Low to High' },
    { value: 'price-desc', label: 'Price: High to Low' },
    { value: 'name-asc', label: 'Name: A to Z' },
];

const currentSort = computed(() => {
    return `${props.filters.sort || 'created_at'}-${props.filters.direction || 'desc'}`;
});

const handleSort = (event) => {
    const [sort, direction] = event.target.value.split('-');
    const params = new URLSearchParams(window.location.search);
    params.set('sort', sort);
    params.set('direction', direction);
    params.delete('page');

    router.get(route('products.index'), Object.fromEntries(params), {
        preserveState: true,
        preserveScroll: true,
    });
};

const isInWishlist = (productId) => {
    return props.wishlistIds?.includes(productId) || false;
};
</script>

<template>
    <Head title="Shop" />

    <ShopLayout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Hero Section -->
                <div class="relative rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 overflow-hidden mb-8">
                    <div class="absolute inset-0 bg-black opacity-10"></div>
                    <div class="relative px-8 py-12 sm:px-12 sm:py-16">
                        <h1 class="text-3xl sm:text-4xl font-bold text-white mb-4">
                            Discover Amazing Products
                        </h1>
                        <p class="text-lg text-indigo-100 max-w-xl">
                            Shop our curated collection of quality products at unbeatable prices.
                        </p>
                    </div>
                </div>

                <!-- Filters -->
                <div class="mb-6">
                    <CategoryFilter
                        :categories="categories"
                        :current-category="filters.category"
                    />
                </div>

                <!-- Sort & Results Info -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <p class="text-gray-600">
                        Showing <span class="font-semibold">{{ products.data.length }}</span> of
                        <span class="font-semibold">{{ products.total }}</span> products
                    </p>

                    <div class="flex items-center gap-2">
                        <label for="sort" class="text-sm text-gray-600">Sort by:</label>
                        <select
                            id="sort"
                            :value="currentSort"
                            @change="handleSort"
                            class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option v-for="option in sortOptions" :key="option.value" :value="option.value">
                                {{ option.label }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Products Grid -->
                <div v-if="products.data.length > 0" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                    <ProductCard
                        v-for="product in products.data"
                        :key="product.id"
                        :product="product"
                        :is-in-wishlist="isInWishlist(product.id)"
                    />
                </div>

                <!-- Empty State -->
                <EmptyState
                    v-else
                    title="No products found"
                    description="Try adjusting your filters or search criteria."
                    icon="box"
                />

                <!-- Pagination -->
                <div class="mt-8">
                    <Pagination :links="products.links" />
                </div>
            </div>
        </div>
    </ShopLayout>
</template>

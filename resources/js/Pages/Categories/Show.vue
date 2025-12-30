<script setup>
import { Head, router, usePage } from '@inertiajs/vue3';
import ShopLayout from '@/Layouts/ShopLayout.vue';
import ProductCard from '@/Components/Shop/ProductCard.vue';
import Pagination from '@/Components/UI/Pagination.vue';
import Breadcrumb from '@/Components/UI/Breadcrumb.vue';
import EmptyState from '@/Components/UI/EmptyState.vue';
import { computed } from 'vue';

const props = defineProps({
    category: Object,
    products: Object,
    wishlistIds: Array,
    filters: Object,
});

const page = usePage();

const breadcrumbs = [
    { label: 'Categories', href: route('categories.index') },
    { label: props.category.name },
];

const sortOptions = [
    { value: 'created_at-desc', label: 'Newest' },
    { value: 'price-asc', label: 'Price: Low to High' },
    { value: 'price-desc', label: 'Price: High to Low' },
    { value: 'name-asc', label: 'Name: A to Z' },
];

const currentSort = computed(() => {
    return `${props.filters?.sort || 'created_at'}-${props.filters?.direction || 'desc'}`;
});

const handleSort = (event) => {
    const [sort, direction] = event.target.value.split('-');
    router.get(route('categories.show', props.category.slug), {
        sort,
        direction,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const isInWishlist = (productId) => {
    return props.wishlistIds?.includes(productId) || false;
};
</script>

<template>
    <Head :title="category.name" />

    <ShopLayout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumb -->
                <div class="mb-6">
                    <Breadcrumb :items="breadcrumbs" />
                </div>

                <!-- Category Header -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl overflow-hidden mb-8">
                    <div class="px-8 py-12 sm:px-12">
                        <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2">
                            {{ category.name }}
                        </h1>
                        <p v-if="category.description" class="text-lg text-indigo-100 max-w-xl">
                            {{ category.description }}
                        </p>
                        <p class="text-indigo-200 mt-4">
                            {{ products.total }} {{ products.total === 1 ? 'product' : 'products' }} available
                        </p>
                    </div>
                </div>

                <!-- Sort -->
                <div v-if="products.data?.length > 0" class="flex justify-end mb-6">
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
                    v-else
                    title="No products in this category"
                    description="Check back soon for new products."
                    icon="box"
                    action-label="Browse All Products"
                    :action-route="route('products.index')"
                />

                <!-- Pagination -->
                <div v-if="products.links" class="mt-8">
                    <Pagination :links="products.links" />
                </div>
            </div>
        </div>
    </ShopLayout>
</template>

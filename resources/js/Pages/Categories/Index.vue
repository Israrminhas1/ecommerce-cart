<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ShopLayout from '@/Layouts/ShopLayout.vue';

const props = defineProps({
    categories: Array,
});
</script>

<template>
    <Head title="Categories" />

    <ShopLayout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-8">Browse Categories</h1>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                    <Link
                        v-for="category in categories"
                        :key="category.id"
                        :href="route('categories.show', category.slug)"
                        class="group relative bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow"
                    >
                        <!-- Category Image -->
                        <div class="aspect-square bg-gradient-to-br from-indigo-100 to-purple-100 relative overflow-hidden">
                            <img
                                v-if="category.image"
                                :src="category.image"
                                :alt="category.name"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            />
                            <div v-else class="w-full h-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        </div>

                        <!-- Category Info -->
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                {{ category.name }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ category.products_count }} {{ category.products_count === 1 ? 'product' : 'products' }}
                            </p>
                        </div>
                    </Link>
                </div>

                <!-- Empty State -->
                <div v-if="categories.length === 0" class="text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No categories yet</h3>
                    <p class="text-gray-500">Check back soon for new categories.</p>
                </div>
            </div>
        </div>
    </ShopLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/UI/Pagination.vue';
import StockBadge from '@/Components/Shop/StockBadge.vue';
import { ref } from 'vue';

const props = defineProps({
    products: Object,
    categories: Array,
    filters: Object,
});

const search = ref(props.filters.search || '');
const category = ref(props.filters.category || '');
const stock = ref(props.filters.stock || '');

const applyFilters = () => {
    const params = {};
    if (search.value) params.search = search.value;
    if (category.value) params.category = category.value;
    if (stock.value) params.stock = stock.value;

    router.get(route('admin.products.index'), params, {
        preserveState: true,
        preserveScroll: true,
    });
};

const clearFilters = () => {
    search.value = '';
    category.value = '';
    stock.value = '';
    router.get(route('admin.products.index'), {}, {
        preserveState: true,
        preserveScroll: true,
    });
};

const hasFilters = () => {
    return search.value || category.value || stock.value;
};

const deleteProduct = (product) => {
    if (confirm(`Are you sure you want to delete "${product.name}"?`)) {
        router.delete(route('admin.products.destroy', product.id));
    }
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(price);
};
</script>

<template>
    <Head title="Manage Products" />

    <AdminLayout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">Products</h1>
                    <Link
                        :href="route('admin.products.create')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Add Product
                    </Link>
                </div>

                <!-- Filters -->
                <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
                    <div class="flex flex-wrap items-center gap-4">
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Search products..."
                            @input="applyFilters"
                            class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        />

                        <select
                            v-model="category"
                            @change="applyFilters"
                            class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">All Categories</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                {{ cat.name }}
                            </option>
                        </select>

                        <select
                            v-model="stock"
                            @change="applyFilters"
                            class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">All Stock Status</option>
                            <option value="in_stock">In Stock</option>
                            <option value="low_stock">Low Stock</option>
                            <option value="out_of_stock">Out of Stock</option>
                        </select>

                        <button
                            v-if="hasFilters()"
                            @click="clearFilters"
                            class="px-4 py-2 text-gray-600 hover:text-gray-900 font-medium transition-colors"
                        >
                            Clear
                        </button>
                    </div>
                </div>

                <!-- Products Table -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Product
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Category
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Price
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Stock
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="product in products.data" :key="product.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <img
                                            :src="product.image || 'https://via.placeholder.com/48'"
                                            :alt="product.name"
                                            class="w-12 h-12 rounded-lg object-cover"
                                        />
                                        <div>
                                            <p class="font-medium text-gray-900">{{ product.name }}</p>
                                            <p class="text-sm text-gray-500">{{ product.slug }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ product.category?.name || 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ formatPrice(product.price) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span :class="product.stock_quantity <= product.low_stock_threshold ? 'text-amber-600' : 'text-gray-900'">
                                        {{ product.stock_quantity }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <StockBadge
                                        :stock="product.stock_quantity"
                                        :threshold="product.low_stock_threshold"
                                    />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <Link
                                        :href="route('admin.products.edit', product.id)"
                                        class="text-indigo-600 hover:text-indigo-900 mr-4"
                                    >
                                        Edit
                                    </Link>
                                    <button
                                        @click="deleteProduct(product)"
                                        class="text-red-600 hover:text-red-900"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    <Pagination :links="products.links" />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

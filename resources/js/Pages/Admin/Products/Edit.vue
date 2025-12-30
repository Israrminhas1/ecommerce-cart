<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    product: Object,
    categories: Array,
});

const form = useForm({
    name: props.product.name,
    category_id: props.product.category_id,
    description: props.product.description || '',
    price: props.product.price,
    stock_quantity: props.product.stock_quantity,
    low_stock_threshold: props.product.low_stock_threshold,
    image: props.product.image || '',
    is_active: props.product.is_active,
});

const submit = () => {
    form.patch(route('admin.products.update', props.product.id));
};
</script>

<template>
    <Head :title="`Edit: ${product.name}`" />

    <AdminLayout>
        <div class="py-8">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-8">
                    <Link
                        :href="route('admin.products.index')"
                        class="text-indigo-600 hover:text-indigo-700 font-medium flex items-center gap-2"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Back to Products
                    </Link>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Product</h1>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Product Name *
                            </label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                required
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            />
                            <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">
                                Category *
                            </label>
                            <select
                                id="category"
                                v-model="form.category_id"
                                required
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Select a category</option>
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                    {{ cat.name }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                Description
                            </label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="4"
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            ></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                                    Price *
                                </label>
                                <input
                                    id="price"
                                    v-model="form.price"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                />
                            </div>

                            <div>
                                <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">
                                    Stock Quantity *
                                </label>
                                <input
                                    id="stock"
                                    v-model="form.stock_quantity"
                                    type="number"
                                    min="0"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                />
                            </div>
                        </div>

                        <div>
                            <label for="threshold" class="block text-sm font-medium text-gray-700 mb-1">
                                Low Stock Threshold
                            </label>
                            <input
                                id="threshold"
                                v-model="form.low_stock_threshold"
                                type="number"
                                min="0"
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            />
                        </div>

                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-1">
                                Image URL
                            </label>
                            <input
                                id="image"
                                v-model="form.image"
                                type="url"
                                placeholder="https://example.com/image.jpg"
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            />
                            <div v-if="form.image" class="mt-2">
                                <img :src="form.image" alt="Preview" class="w-24 h-24 object-cover rounded-lg" />
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <input
                                id="active"
                                v-model="form.is_active"
                                type="checkbox"
                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                            />
                            <label for="active" class="text-sm text-gray-700">
                                Active (visible to customers)
                            </label>
                        </div>

                        <div class="flex justify-end gap-4 pt-4">
                            <Link
                                :href="route('admin.products.index')"
                                class="px-4 py-2 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors"
                            >
                                Cancel
                            </Link>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 disabled:bg-indigo-400 transition-colors"
                            >
                                {{ form.processing ? 'Saving...' : 'Save Changes' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import QuantitySelector from './QuantitySelector.vue';
import StockBadge from './StockBadge.vue';

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
    stockError: {
        type: Object,
        default: null,
    },
});

const isUpdating = ref(false);
const isRemoving = ref(false);

const updateQuantity = (newQuantity) => {
    isUpdating.value = true;
    router.patch(route('cart.items.update', props.item.id), {
        quantity: newQuantity,
    }, {
        preserveScroll: true,
        onFinish: () => {
            isUpdating.value = false;
        },
    });
};

const removeItem = () => {
    isRemoving.value = true;
    router.delete(route('cart.items.destroy', props.item.id), {
        preserveScroll: true,
        onFinish: () => {
            isRemoving.value = false;
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
    <div
        :class="[
            'flex gap-4 p-4 bg-white rounded-lg border',
            stockError ? 'border-red-200 bg-red-50' : 'border-gray-200'
        ]"
    >
        <!-- Product Image -->
        <Link
            v-if="item.product"
            :href="route('products.show', item.product.slug)"
            class="shrink-0"
        >
            <img
                :src="item.product.image || 'https://via.placeholder.com/100'"
                :alt="item.product.name"
                class="w-24 h-24 object-cover rounded-lg"
            />
        </Link>
        <div v-else class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
            <span class="text-gray-400 text-xs">No image</span>
        </div>

        <!-- Product Details -->
        <div class="flex-1 min-w-0">
            <div class="flex justify-between gap-4">
                <div>
                    <Link
                        v-if="item.product"
                        :href="route('products.show', item.product.slug)"
                        class="font-semibold text-gray-900 hover:text-indigo-600 transition-colors line-clamp-2"
                    >
                        {{ item.product.name }}
                    </Link>
                    <p v-else class="font-semibold text-gray-500">
                        Product unavailable
                    </p>

                    <p v-if="item.product?.category" class="text-sm text-gray-500 mt-0.5">
                        {{ item.product.category.name }}
                    </p>
                </div>

                <!-- Remove Button -->
                <button
                    @click="removeItem"
                    :disabled="isRemoving"
                    class="shrink-0 p-2 text-gray-400 hover:text-red-500 transition-colors disabled:opacity-50"
                    title="Remove item"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <!-- Stock Warning -->
            <div v-if="stockError" class="mt-2">
                <p class="text-sm text-red-600 font-medium">
                    {{ stockError.message }}
                </p>
            </div>

            <!-- Stock Badge -->
            <div v-else-if="item.product" class="mt-2">
                <StockBadge
                    :stock="item.product.stock_quantity"
                    :threshold="item.product.low_stock_threshold"
                    :show-count="item.product.stock_quantity <= 10"
                />
            </div>

            <!-- Quantity and Price -->
            <div class="mt-3 flex items-center justify-between">
                <QuantitySelector
                    v-if="item.product"
                    :model-value="item.quantity"
                    :max="item.product.stock_quantity"
                    :disabled="isUpdating"
                    size="sm"
                    @change="updateQuantity"
                />

                <div class="text-right">
                    <p class="text-sm text-gray-500">
                        {{ formatPrice(item.price_at_addition) }} each
                    </p>
                    <p class="text-lg font-bold text-gray-900">
                        {{ formatPrice(item.quantity * item.price_at_addition) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

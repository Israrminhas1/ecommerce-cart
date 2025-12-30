<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const cartCount = computed(() => page.props.cart?.count || 0);
const cartTotal = computed(() => page.props.cart?.total || 0);

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(price);
};
</script>

<template>
    <Link
        :href="route('cart.index')"
        class="relative flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors group"
    >
        <div class="relative">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 group-hover:text-indigo-600 transition-colors" viewBox="0 0 20 20" fill="currentColor">
                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
            </svg>

            <!-- Badge -->
            <span
                v-if="cartCount > 0"
                class="absolute -top-2 -right-2 h-5 w-5 flex items-center justify-center bg-indigo-600 text-white text-xs font-bold rounded-full"
            >
                {{ cartCount > 99 ? '99+' : cartCount }}
            </span>
        </div>

        <!-- Total (hidden on mobile) -->
        <span v-if="cartCount > 0" class="hidden md:block text-sm font-medium text-gray-700">
            {{ formatPrice(cartTotal) }}
        </span>
    </Link>
</template>

<script setup>
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    categories: {
        type: Array,
        required: true,
    },
    currentCategory: {
        type: String,
        default: null,
    },
});

const selectCategory = (slug) => {
    const params = new URLSearchParams(window.location.search);

    if (slug) {
        params.set('category', slug);
    } else {
        params.delete('category');
    }

    // Reset to page 1 when changing category
    params.delete('page');

    router.get(route('products.index'), Object.fromEntries(params), {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <div class="flex flex-wrap gap-2">
        <button
            @click="selectCategory(null)"
            :class="[
                'px-4 py-2 rounded-full text-sm font-medium transition-all duration-200',
                !currentCategory
                    ? 'bg-indigo-600 text-white shadow-md'
                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
            ]"
        >
            All Products
        </button>

        <button
            v-for="category in categories"
            :key="category.id"
            @click="selectCategory(category.slug)"
            :class="[
                'px-4 py-2 rounded-full text-sm font-medium transition-all duration-200',
                currentCategory === category.slug
                    ? 'bg-indigo-600 text-white shadow-md'
                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
            ]"
        >
            {{ category.name }}
        </button>
    </div>
</template>

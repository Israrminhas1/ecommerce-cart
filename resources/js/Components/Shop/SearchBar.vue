<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import { debounce } from 'lodash-es';

const props = defineProps({
    initialQuery: {
        type: String,
        default: '',
    },
});

const query = ref(props.initialQuery);
const suggestions = ref([]);
const showSuggestions = ref(false);
const isLoading = ref(false);
const searchInput = ref(null);

const fetchSuggestions = debounce(async (searchQuery) => {
    if (searchQuery.length < 2) {
        suggestions.value = [];
        return;
    }

    isLoading.value = true;

    try {
        const response = await fetch(route('search.suggestions') + `?q=${encodeURIComponent(searchQuery)}`);
        suggestions.value = await response.json();
    } catch (error) {
        console.error('Failed to fetch suggestions:', error);
        suggestions.value = [];
    } finally {
        isLoading.value = false;
    }
}, 300);

watch(query, (newQuery) => {
    fetchSuggestions(newQuery);
});

const handleSearch = () => {
    if (query.value.trim()) {
        showSuggestions.value = false;
        router.get(route('search'), { q: query.value });
    }
};

const selectSuggestion = (product) => {
    showSuggestions.value = false;
    router.visit(route('products.show', product.slug));
};

const handleClickOutside = (event) => {
    if (searchInput.value && !searchInput.value.contains(event.target)) {
        showSuggestions.value = false;
    }
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(price);
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
    <div ref="searchInput" class="relative w-full max-w-lg">
        <form @submit.prevent="handleSearch" class="relative">
            <input
                v-model="query"
                type="text"
                placeholder="Search products..."
                @focus="showSuggestions = true"
                class="w-full pl-10 pr-4 py-2.5 bg-gray-100 border-0 rounded-lg focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all"
            />

            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>

            <div v-if="isLoading" class="absolute inset-y-0 right-0 flex items-center pr-3">
                <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </form>

        <!-- Suggestions Dropdown -->
        <div
            v-if="showSuggestions && suggestions.length > 0"
            class="absolute z-50 top-full left-0 right-0 mt-2 bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden"
        >
            <div
                v-for="product in suggestions"
                :key="product.id"
                @click="selectSuggestion(product)"
                class="flex items-center gap-3 p-3 hover:bg-gray-50 cursor-pointer transition-colors"
            >
                <img
                    :src="product.image || 'https://via.placeholder.com/48'"
                    :alt="product.name"
                    class="w-12 h-12 object-cover rounded-lg"
                />
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ product.name }}</p>
                    <p class="text-sm text-indigo-600 font-semibold">{{ formatPrice(product.price) }}</p>
                </div>
            </div>

            <Link
                :href="route('search', { q: query })"
                class="block p-3 text-center text-sm text-indigo-600 font-medium hover:bg-indigo-50 border-t"
            >
                View all results
            </Link>
        </div>
    </div>
</template>

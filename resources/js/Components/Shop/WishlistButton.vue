<script setup>
import { ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
    isInWishlist: {
        type: Boolean,
        default: false,
    },
    size: {
        type: String,
        default: 'md', // sm, md, lg
    },
});

const page = usePage();
const isLoading = ref(false);
const localIsInWishlist = ref(props.isInWishlist);

const toggleWishlist = () => {
    if (!page.props.auth.user) {
        router.visit(route('login'));
        return;
    }

    isLoading.value = true;
    localIsInWishlist.value = !localIsInWishlist.value;

    router.post(route('wishlist.toggle', props.product.slug), {}, {
        preserveScroll: true,
        onFinish: () => {
            isLoading.value = false;
        },
        onError: () => {
            localIsInWishlist.value = !localIsInWishlist.value;
        },
    });
};

const sizeClasses = {
    sm: 'p-1.5',
    md: 'p-2',
    lg: 'p-3',
};

const iconSizes = {
    sm: 'h-4 w-4',
    md: 'h-5 w-5',
    lg: 'h-6 w-6',
};
</script>

<template>
    <button
        @click.prevent="toggleWishlist"
        :disabled="isLoading"
        :class="[
            sizeClasses[size],
            'rounded-full transition-all duration-200',
            localIsInWishlist
                ? 'bg-red-100 text-red-500 hover:bg-red-200'
                : 'bg-white/90 text-gray-600 hover:bg-white hover:text-red-500'
        ]"
        :title="localIsInWishlist ? 'Remove from wishlist' : 'Add to wishlist'"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            :class="iconSizes[size]"
            :fill="localIsInWishlist ? 'currentColor' : 'none'"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
    </button>
</template>

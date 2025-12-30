<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <nav v-if="links.length > 3" class="flex items-center justify-center gap-1">
        <template v-for="(link, index) in links" :key="index">
            <Link
                v-if="link.url"
                :href="link.url"
                :class="[
                    'px-3 py-2 text-sm font-medium rounded-lg transition-colors',
                    link.active
                        ? 'bg-indigo-600 text-white'
                        : 'text-gray-700 hover:bg-gray-100'
                ]"
                v-html="link.label"
                preserve-scroll
            />
            <span
                v-else
                :class="[
                    'px-3 py-2 text-sm font-medium rounded-lg',
                    'text-gray-400 cursor-not-allowed'
                ]"
                v-html="link.label"
            />
        </template>
    </nav>
</template>

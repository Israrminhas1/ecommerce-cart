<script setup>
import { computed } from 'vue';

const props = defineProps({
    stock: {
        type: Number,
        required: true,
    },
    threshold: {
        type: Number,
        default: 10,
    },
    showCount: {
        type: Boolean,
        default: false,
    },
});

const status = computed(() => {
    if (props.stock <= 0) return 'out';
    if (props.stock <= props.threshold) return 'low';
    return 'in';
});

const statusConfig = {
    out: {
        label: 'Out of Stock',
        bgClass: 'bg-red-100',
        textClass: 'text-red-700',
        dotClass: 'bg-red-500',
    },
    low: {
        label: props.showCount ? `Only ${props.stock} left` : 'Low Stock',
        bgClass: 'bg-amber-100',
        textClass: 'text-amber-700',
        dotClass: 'bg-amber-500',
    },
    in: {
        label: 'In Stock',
        bgClass: 'bg-green-100',
        textClass: 'text-green-700',
        dotClass: 'bg-green-500',
    },
};

const config = computed(() => statusConfig[status.value]);
</script>

<template>
    <span
        :class="[config.bgClass, config.textClass]"
        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium"
    >
        <span :class="[config.dotClass]" class="w-1.5 h-1.5 rounded-full"></span>
        {{ config.label }}
    </span>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    modelValue: {
        type: Number,
        default: 1,
    },
    min: {
        type: Number,
        default: 1,
    },
    max: {
        type: Number,
        default: 99,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    size: {
        type: String,
        default: 'md', // sm, md, lg
    },
});

const emit = defineEmits(['update:modelValue', 'change']);

const quantity = ref(props.modelValue);

watch(() => props.modelValue, (newValue) => {
    quantity.value = newValue;
});

const decrement = () => {
    if (quantity.value > props.min) {
        quantity.value--;
        emit('update:modelValue', quantity.value);
        emit('change', quantity.value);
    }
};

const increment = () => {
    if (quantity.value < props.max) {
        quantity.value++;
        emit('update:modelValue', quantity.value);
        emit('change', quantity.value);
    }
};

const handleInput = (event) => {
    let value = parseInt(event.target.value) || props.min;
    value = Math.max(props.min, Math.min(props.max, value));
    quantity.value = value;
    emit('update:modelValue', value);
    emit('change', value);
};

const sizeClasses = {
    sm: {
        button: 'w-7 h-7',
        input: 'w-10 h-7 text-sm',
    },
    md: {
        button: 'w-9 h-9',
        input: 'w-14 h-9',
    },
    lg: {
        button: 'w-11 h-11',
        input: 'w-16 h-11 text-lg',
    },
};
</script>

<template>
    <div class="inline-flex items-center border border-gray-300 rounded-lg overflow-hidden">
        <button
            type="button"
            @click="decrement"
            :disabled="disabled || quantity <= min"
            :class="[
                sizeClasses[size].button,
                'flex items-center justify-center bg-gray-50 hover:bg-gray-100 text-gray-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed'
            ]"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
            </svg>
        </button>

        <input
            type="text"
            inputmode="numeric"
            pattern="[0-9]*"
            :value="quantity"
            @input="handleInput"
            :disabled="disabled"
            :class="[
                sizeClasses[size].input,
                'text-center border-x border-gray-300 font-medium text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 disabled:bg-gray-100'
            ]"
        />

        <button
            type="button"
            @click="increment"
            :disabled="disabled || quantity >= max"
            :class="[
                sizeClasses[size].button,
                'flex items-center justify-center bg-gray-50 hover:bg-gray-100 text-gray-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed'
            ]"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
</template>

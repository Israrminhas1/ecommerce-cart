<script setup>
import { ref, watch, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

const show = ref(false);
const message = ref('');
const type = ref('success'); // success, error, warning, info

const typeConfig = {
    success: {
        bgClass: 'bg-green-50 border-green-200',
        textClass: 'text-green-800',
        iconClass: 'text-green-400',
        icon: 'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z',
    },
    error: {
        bgClass: 'bg-red-50 border-red-200',
        textClass: 'text-red-800',
        iconClass: 'text-red-400',
        icon: 'M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z',
    },
    warning: {
        bgClass: 'bg-amber-50 border-amber-200',
        textClass: 'text-amber-800',
        iconClass: 'text-amber-400',
        icon: 'M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z',
    },
    info: {
        bgClass: 'bg-blue-50 border-blue-200',
        textClass: 'text-blue-800',
        iconClass: 'text-blue-400',
        icon: 'M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z',
    },
};

const showToast = (msg, toastType = 'success') => {
    message.value = msg;
    type.value = toastType;
    show.value = true;

    setTimeout(() => {
        show.value = false;
    }, 5000);
};

const closeToast = () => {
    show.value = false;
};

watch(() => page.props.flash, (flash) => {
    if (flash?.success) {
        showToast(flash.success, 'success');
    }
    if (flash?.error) {
        showToast(flash.error, 'error');
    }
}, { immediate: true, deep: true });

const config = () => typeConfig[type.value];
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="translate-y-2 opacity-0"
            enter-to-class="translate-y-0 opacity-100"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="translate-y-0 opacity-100"
            leave-to-class="translate-y-2 opacity-0"
        >
            <div
                v-if="show"
                class="fixed bottom-4 right-4 z-50 max-w-sm w-full"
            >
                <div
                    :class="[config().bgClass]"
                    class="rounded-lg border p-4 shadow-lg"
                >
                    <div class="flex items-start gap-3">
                        <svg
                            :class="config().iconClass"
                            class="h-5 w-5 shrink-0 mt-0.5"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path fill-rule="evenodd" :d="config().icon" clip-rule="evenodd" />
                        </svg>

                        <p :class="config().textClass" class="flex-1 text-sm font-medium">
                            {{ message }}
                        </p>

                        <button
                            @click="closeToast"
                            class="shrink-0 text-gray-400 hover:text-gray-600"
                        >
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

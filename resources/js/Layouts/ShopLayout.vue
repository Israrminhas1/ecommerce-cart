<script setup>
import { ref, computed } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import CartIcon from '@/Components/Shop/CartIcon.vue';
import Toast from '@/Components/UI/Toast.vue';

const showingNavigationDropdown = ref(false);
const page = usePage();

const isAuthenticated = computed(() => !!page.props.auth?.user);
const isAdmin = computed(() => page.props.isAdmin);
const flash = computed(() => page.props.flash || {});
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100 flex flex-col">
            <nav class="border-b border-gray-100 bg-white shadow-sm">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <div class="flex shrink-0 items-center">
                                <Link :href="route('products.index')">
                                    <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                                </Link>
                            </div>

                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex sm:items-center">
                                <Link
                                    :href="route('products.index')"
                                    class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-900 focus:outline-none transition duration-150 ease-in-out"
                                    :class="{ 'border-b-2 border-indigo-400': route().current('products.index') || route().current('home') }"
                                >
                                    Shop
                                </Link>
                                <Link
                                    :href="route('categories.index')"
                                    class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 focus:outline-none transition duration-150 ease-in-out"
                                    :class="{ 'border-b-2 border-indigo-400 text-gray-900': route().current('categories.*') }"
                                >
                                    Categories
                                </Link>
                                <Link
                                    :href="route('search')"
                                    class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 focus:outline-none transition duration-150 ease-in-out"
                                    :class="{ 'border-b-2 border-indigo-400 text-gray-900': route().current('search') }"
                                >
                                    Search
                                </Link>
                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center gap-4">
                            <Link
                                :href="route('search')"
                                class="p-2 text-gray-500 hover:text-gray-700 transition-colors"
                                title="Search"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </Link>

                            <CartIcon v-if="isAuthenticated" />

                            <template v-if="isAuthenticated">
                                <div class="relative ms-3">
                                    <Dropdown align="right" width="48">
                                        <template #trigger>
                                            <span class="inline-flex rounded-md">
                                                <button
                                                    type="button"
                                                    class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"
                                                >
                                                    {{ $page.props.auth.user.name }}
                                                    <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </span>
                                        </template>

                                        <template #content>
                                            <DropdownLink :href="route('cart.index')">
                                                Cart
                                            </DropdownLink>
                                            <DropdownLink :href="route('wishlist.index')">
                                                Wishlist
                                            </DropdownLink>
                                            <DropdownLink :href="route('orders.index')">
                                                My Orders
                                            </DropdownLink>
                                            <div class="border-t border-gray-100"></div>
                                            <DropdownLink :href="route('profile.edit')">
                                                Profile
                                            </DropdownLink>
                                            <template v-if="isAdmin">
                                                <div class="border-t border-gray-100"></div>
                                                <DropdownLink :href="route('admin.dashboard')">
                                                    Admin Dashboard
                                                </DropdownLink>
                                            </template>
                                            <div class="border-t border-gray-100"></div>
                                            <DropdownLink
                                                :href="route('logout')"
                                                method="post"
                                                as="button"
                                            >
                                                Log Out
                                            </DropdownLink>
                                        </template>
                                    </Dropdown>
                                </div>
                            </template>

                            <template v-else>
                                <Link
                                    :href="route('login')"
                                    class="text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors"
                                >
                                    Log in
                                </Link>
                                <Link
                                    :href="route('register')"
                                    class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors"
                                >
                                    Sign up
                                </Link>
                            </template>
                        </div>

                        <div class="-me-2 flex items-center sm:hidden gap-2">
                            <CartIcon v-if="isAuthenticated" />

                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden"
                >
                    <div class="space-y-1 pb-3 pt-2">
                        <Link
                            :href="route('products.index')"
                            class="block w-full py-2 pe-4 ps-3 text-base font-medium text-gray-600 transition duration-150 ease-in-out hover:bg-gray-50 hover:text-gray-800 focus:bg-gray-50 focus:text-gray-800 focus:outline-none"
                            :class="{ 'bg-indigo-50 border-l-4 border-indigo-400 text-indigo-700': route().current('products.index') }"
                        >
                            Shop
                        </Link>
                        <Link
                            :href="route('categories.index')"
                            class="block w-full py-2 pe-4 ps-3 text-base font-medium text-gray-600 transition duration-150 ease-in-out hover:bg-gray-50 hover:text-gray-800 focus:bg-gray-50 focus:text-gray-800 focus:outline-none"
                            :class="{ 'bg-indigo-50 border-l-4 border-indigo-400 text-indigo-700': route().current('categories.*') }"
                        >
                            Categories
                        </Link>
                        <Link
                            :href="route('search')"
                            class="block w-full py-2 pe-4 ps-3 text-base font-medium text-gray-600 transition duration-150 ease-in-out hover:bg-gray-50 hover:text-gray-800 focus:bg-gray-50 focus:text-gray-800 focus:outline-none"
                            :class="{ 'bg-indigo-50 border-l-4 border-indigo-400 text-indigo-700': route().current('search') }"
                        >
                            Search
                        </Link>
                    </div>

                    <template v-if="isAuthenticated">
                        <div class="border-t border-gray-200 pb-3 pt-2">
                            <Link
                                :href="route('cart.index')"
                                class="block w-full py-2 pe-4 ps-3 text-base font-medium text-gray-600"
                            >
                                Cart
                            </Link>
                            <Link
                                :href="route('wishlist.index')"
                                class="block w-full py-2 pe-4 ps-3 text-base font-medium text-gray-600"
                            >
                                Wishlist
                            </Link>
                            <Link
                                :href="route('orders.index')"
                                class="block w-full py-2 pe-4 ps-3 text-base font-medium text-gray-600"
                            >
                                My Orders
                            </Link>
                        </div>

                        <div v-if="isAdmin" class="border-t border-gray-200 pb-3 pt-2">
                            <div class="px-4 py-2">
                                <span class="text-xs font-semibold uppercase text-gray-400">Admin</span>
                            </div>
                            <Link
                                :href="route('admin.dashboard')"
                                class="block w-full py-2 pe-4 ps-3 text-base font-medium text-gray-600"
                            >
                                Dashboard
                            </Link>
                        </div>

                        <div class="border-t border-gray-200 pb-1 pt-4">
                            <div class="px-4">
                                <div class="text-base font-medium text-gray-800">
                                    {{ $page.props.auth.user.name }}
                                </div>
                                <div class="text-sm font-medium text-gray-500">
                                    {{ $page.props.auth.user.email }}
                                </div>
                            </div>

                            <div class="mt-3 space-y-1">
                                <Link
                                    :href="route('profile.edit')"
                                    class="block w-full py-2 pe-4 ps-3 text-base font-medium text-gray-600"
                                >
                                    Profile
                                </Link>
                                <Link
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                    class="block w-full py-2 pe-4 ps-3 text-start text-base font-medium text-gray-600"
                                >
                                    Log Out
                                </Link>
                            </div>
                        </div>
                    </template>

                    <template v-else>
                        <div class="border-t border-gray-200 pb-3 pt-4">
                            <div class="space-y-1">
                                <Link
                                    :href="route('login')"
                                    class="block w-full py-2 pe-4 ps-3 text-base font-medium text-gray-600"
                                >
                                    Log in
                                </Link>
                                <Link
                                    :href="route('register')"
                                    class="block w-full py-2 pe-4 ps-3 text-base font-medium text-indigo-600"
                                >
                                    Sign up
                                </Link>
                            </div>
                        </div>
                    </template>
                </div>
            </nav>

            <main class="flex-grow">
                <slot />
            </main>

            <footer class="bg-white border-t">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <div class="text-center text-gray-500 text-sm">
                        <p>&copy; {{ new Date().getFullYear() }} E-commerce Store. All rights reserved.</p>
                    </div>
                </div>
            </footer>

            <Toast
                v-if="flash.success"
                type="success"
                :message="flash.success"
            />
            <Toast
                v-if="flash.error"
                type="error"
                :message="flash.error"
            />
        </div>
    </div>
</template>
